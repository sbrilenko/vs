<?php
/**
 * @author InviS
 * @version 1.0624
 * История изменений:
 * 1.0624
 * - добавлен метод hasParam(), - проверка существования заданного параметра страницы, передала, если массив параметров меньше дибо равно 1, то возвратит false
 * 1.0603
 * - добавлен метод getParam(), который возвращает параметр по порядковому номеру, либо по имени (ассоциативный массив)
 * 1.0601
 * - подправил метод is(), добавил возможность заменять искомые переменные звездочками
 * 1.0519
 * - добавлено поле $paramsArray, в котором хранятся все переменные, но в виде обычного массива
 * - метод is() проверяет теперь не только контроллер и действие, но и параметры
 * - разделитель $_delimiter теперь регистрируется также на контроллер, а не только на действие
 * 1.0427
 * - добавлена поддержка мультиязычных сайтов
 * 1.0406:
 * - контроллер и действие стали приватными
 * - добавлена функция проверки контроллера и действия is()
 * 1.0403:
 * - добавлена возможность создания псевдонимов (alias) для контроллеров и действий
 */


class controller {
	private $_controller;                  // контроллер
	private $_defaultController = 'index'; // контроллер по умолчанию
	private $_action;                      // действие
	private $_defaultAction = 'index';     // действие по умолчанию
	public $params;                       // ассоциативный массив параметров
	public $paramsArray;                   // массив параметров
	public $view;                          // страница вида

	private $_uri;
	private $_delimiter = '!';
    private $_alias;                       // зарегистрированные псевдонимы
    
    private $_multiLang = false;		   // флаг устанавливается для мультиязычных приложений
	private $_lang = 'rus';				   // язык (папка из которой будут браться виды)
    
	/** 
	 * 	@param rootLevel - уровень, на котором находится папка с приложением.
	 *  по умолчанию - на нулевом уровне (прям на хосте)
	 *  Например, для http://example.com/develop/... уровень будет 1
	 */
	function __construct($rootLevel = 0) {
	    $this -> _alias = array();
		$this -> _uri = explode("/",  substr($_SERVER['REQUEST_URI'], 1,  strlen($_SERVER['REQUEST_URI'])));
		
		if (!empty($this -> _uri)){
			for ($i = 0; $i<$rootLevel; $i++)	// удаляем директории из URI
				unset($this -> _uri[$i]);
		}
		
		if (!empty($this -> _uri)){
			for($i = $rootLevel; $i < count($this -> _uri); $i++) {
				if(empty($this -> _uri[$i]))
					unset($this -> _uri[$i]);
				else
					$this -> _uri[$i] = mb_strtolower(trim($this -> _uri[$i]));
			}
		}
		
		if (!empty($this -> _uri)) $this -> _uri = array_values($this -> _uri);
	}
	
	/** функция устанавливает значение имени контроллера по умолчанию */
	public function setDefaultController($controllerName){
		$this -> _defaultController = mb_strtolower($controllerName);
		return $this;
	}
	
	/** функция устанавливает значение имени действия по умолчанию */
	public function setDefaultAction($actionName){
		$this -> _defaultAction = mb_strtolower($actionName);
		return $this;
	}
	
	/** функция устанавливает значение разделителя action и controller(вместо index) */
	public function setDelimiter($delimiter){
		$this -> _delimiter = $delimiter;
	}
    /** функция берет значение delimiter */
    public function getDelimiter(){
        return $this -> _delimiter;
    }
    /** добавляем к контроллеру и действию псевдоним (как еще данный контроллер и данное действие будет доступно)
     *  запись будет иметь вид: [aliasController.aliasAction] => array('controller', 'action')
     */
    public function registerAlias($controller, $action, $aliasController, $aliasAction){
        $key = mb_strtolower($aliasController).mb_strtolower($aliasAction);
        $value = array('controller' => mb_strtolower($controller),'action' => mb_strtolower($action));
        $this -> _alias[$key] = $value;
        return $this;
    }
    
    /** проверка контроллера и действия на псевдонимы
     *  если это псевдонимы каких либо контроллеров и действий, то изменяем контроллер и действие, 
     *  которые будут переданы на исполнение
     */
    private function isAlias(){
        $searchKey = mb_strtolower($this -> _controller) . mb_strtolower($this -> _action); 
        foreach ($this -> _alias AS $key => $value){
            if ($searchKey == $key){
                $this -> _controller = $value['controller'];
                $this -> _action = $value['action'];
            }
        }
    }
    
    /** 
     *  проверяем контроллер и действие.
     *  Функция вернет true, если введенный значения контролера (и действия) действительно
     *  являются контроллером (и действием) - нужно, например, для подсвечивания пунктов меню
     */
    public function is($controller, $action = '*'){
       	$controllerActionResult = ($controller == $this -> _controller || $controller == '*') && ($action == $this -> _action || $action == '*');
    	$n = func_num_args();
    	if ($n > 2){
    		$paramsResult = true;
			for ($i = 0; $i < $n - 2; $i++){
				if ($this -> paramsArray[$i] != func_get_arg($i + 2) && func_get_arg($i + 2) != '*') $paramsResult = false;
			}
    		return $controllerActionResult && $paramsResult;
    	} else {
    		return $controllerActionResult;
    	}
    }
    
    /** Парсим URI на контроллер, действие и параметры */
    private function parse() {
    	if ($this -> isMultiLang() && !empty($this -> _uri)){
    		$this -> setLang($this -> _uri[0]);
			unset($this -> _uri[0]);
			$this -> _uri = array_values($this -> _uri);
    	}
        $this -> _controller = (empty($this -> _uri[0]) || $this -> _uri[0] == $this -> _delimiter) ? $this -> _defaultController : mb_strtolower($this -> _uri[0]);
        $this -> _action = (empty($this -> _uri[1]) || $this -> _uri[1] == $this -> _delimiter) ? $this -> _defaultAction : mb_strtolower($this -> _uri[1]);
       
	   	/** заносим параметры в ассоциативный массив */
        for($i = 3; $i < count($this -> _uri); $i += 2) {
            $this -> params[$this -> _uri[$i - 1]] = $this -> _uri[$i];
        }
		if(empty($this -> params)){
        	$this -> params = false;
        }
		
		/** заносим параметры в массив */
		for ($i = 2; $i < count($this -> _uri); $i++){
			$this -> paramsArray []= $this -> _uri[$i];
		}
		if (empty($this -> paramsArray)){
			$this -> paramsArray = false;
		}
        
        return $this;
    }
	
	/** Получаем страничку вида для отображения - переделан для суб доменов(перенаправление)*/
	
	public function getView($sub=null){
		$this -> parse();
        $this -> isAlias();
		if ($this -> _controller == "index" && $this -> _action == "index") $filename = "index.phtml";
		else $filename = $this -> _controller . mb_strtoupper($this -> _action[0]) . mb_substr($this -> _action,1) . ".phtml";
		if(!empty($sub))
		{
			$this -> view = "views/".$sub."/" . $filename;
		if ($this -> isMultiLang()){
			$this -> view = 'views/' .$sub."/". $this -> _lang . '/' . $filename;
		}
		}
		
		else {
		$this -> view = "views/" . $filename;
		if ($this -> isMultiLang()){
			$this -> view = 'views/' . $this -> _lang . '/' . $filename;
		}
		}
		return $this -> view;
	}
	
	/** Выводим все для проверки */
	public function debug() {
		echo "<div></div>";
		echo "Lang: " . $this -> _lang . "<br />";
		echo "Controller: " . $this -> _controller . "<br />";
		echo "Action: " . $this -> _action . "<br />";
		echo "View: " . $this -> view . "<br />";
		echo "Params:";
		echo "<pre>";
		print_r($this -> params);
		echo "<pre>";
		echo "Params array:";
		echo "<pre>";
		print_r($this -> paramsArray);
		echo "<pre>";
	}
	
	/** устанавливаем режим мультиязычности */
	public function multiLang(){
		$this -> _multiLang = true;
	}
	/** */
	private function isMultiLang(){
		return $this -> _multiLang;
	}
	/** устанавливаем язык - для страницы индекса (иначе язык будет браться из URI) */
	public function setLang($lang){
		$this -> _lang = $lang;
	}
	/** выдает, какой язык используется у приложения */
	public function getLang(){
		return $this -> _lang;
	}
	
	/** возвращает исполняемый контроллер */
	public function getController(){
		return $this -> _controller;
	}
	/** возвращает исполненное действие */
	public function getAction(){
		return $this -> _action;
	}
	
	/** возвращает параметр по порядковому номеру или же по имени */
	public function getParam($parameter){
		if (is_int($parameter)){
			return isset($this -> paramsArray[$parameter-1]) ? $this -> paramsArray[$parameter-1] : false;
		} else {
			return isset($this -> params[$parameter]) ? $this -> params[$parameter] : false;
		}
	}
	/** Проверка существования параметра по имени */
	public function hasParam($param){
		if (empty($this -> paramsArray)) return false;
        if(count($this -> paramsArray)>1)
        {
            if(in_array($param,array_keys($this -> params)))
            {
                return in_array($param,array_keys($this -> params));
            }
            else return false;
        }
        else return false;
        
	}
}
?>
