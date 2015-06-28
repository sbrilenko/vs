<?php
    /**
     * Автор: InviS
     * Версия 1.1507
     * 
     * История изменений:
     * 2.1 
     *   - добавлена функция для загрузки css по max-width
     * 
	 * 2.0 
	 * 	 - Добавлена func для печати addCssPrint
	 * 1.1507
	 *   - добавлена переменная $_cssMediaType и метод для ее установки setCssMediaType()
	 * 1.05.17
	 *   - добавлена переменная $_headerStart - переключатель для записи скриптов и css из файла хэдера в самое начало
	 *   - также добавлены переменные $_headerCss и $_headerJs - буфферы для css и js скриптов их хэдера
	 *   - подправлены методы addCss, addJs, addCssForIe - добавлен функционал добавления скриптов из хэдера
	 *   - подправлен метод show - до рендеринга хэдера переменная $_headerStart устанавливается в true. После - false
	 * 1.05.13
	 *   - класс переделан под паттерн SingleTon
	 *   - добавлен метод hide() для отмены отображения страницы
	 *   - добавлены методы обработки хедеров и футеров
	 *   - добавлен метод задания языка страницы (атрибута lang тега html)
     * 1.0330
     *   - переписан полностью
     *   - добавлены методы установки для каждого элемента страницы
     *   - удалена возможность устанавливать что-либо не через функции
     *   - ob_start и ob_get_clean перенесены внутрь класса
     *   - Doctype перенесены в статические константы класса
     */

    interface IPage{
    	/** получаем прототип объекта */
    	public static function getInstance();
		
		/** установка доктайпа для страницы */
		public function setDoctype($doctype);
		
    	/** устанавливаем заголовок страницы */
        public function setTitle($pageTitle);
		
		/** добавляем на страницу описание */
        public function setDescription($description);
		
		/** добавляем на страницу ключевые слова */
        public function setKeywords($keywords);
		
		/** установка базы для сайта */
        public function setBase($base);
		
		/** установка иконки для страницы */
        public function setFavicon($favicon);
        
       	/** для fsize*/
        public function addFsize($css, $id);
		/** добавляем на страницу css-файл(-ы) */
        public function addCss($css);
		
			/** добавляем на страницу css-файл(-ы) для печати */
        public function addCssPrint($css);
        
        /** добавляем на страницу css-файл(-ы) для media max-width */
		public function addCssMediaWidth($css,$from,$to);
        
		/** добавляем на страницу css-файлы(-ы) для IE */
        public function addCssForIE($statement, $css);
		
		/** добавляем на страницу javascript-файл(-ы) */
        public function addScript($script);
        
		/** добавление метрики на страницу */
		public function addMetric($metrics);
		
		/** вывод страницы на экран */
		public function show();
		
		/** отменяем вывод страницы на экран */
		public function hide();
		
		/** переадресация на заданную страницу */
        public function setRedirect($redirectLink);
		
		/** устанавливаем медиа тип для страницы */
        public function setCssMediaType($mediaTypes);
    }
    
    class Page implements IPage{
       // константы DOCTYPE
       public static $XHTML = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
       public static $HTML5 = "<!DOCTYPE html>";
      
       // Метрики
       public $googleAnalytics = false;     // название файла, содержащего код счетчика для Google Analitics
       public $yandexMetrica = false;       // название файла, содержащего код счетчика для Yandex Metrica
       
       // CSS
       private $_cssMediaType = 'screen';
       
       private $_buffer;                            // временный буфер, в который будут добавляться данные со страниц  
       private $_page;                              // содержит html-код до вывода на экран
       private $_doctype;                           // DOCTYPE страницы
       private $_charset = "utf-8";                 // кодировка страницы
       private $_keywords;                          // содержит content для вывода
       private $_description;                       // содержит описание для вывода
       private $_favicon = '';      // содержит favicon для вывода
       private $_title;                             // содержит title для вывода
       private $_js;                                // javascript - содержимое
       private $_headerJs;                                // javascript - содержимое заданное в хэдере
       private $_css;                               // css - оформление страницы
       private $_headerCss;							// css - оформление, заданное в хэдере
       private $_basehref;                          // базовая директория для сайта
       private $_metrics;                           // будет содержать коды счетчиков, которые нужно добавить
       
       private $_lang = 'ru';						// язык сайта
       
       private $_redirect = false;  	// переадресация
       private $_display = true;        // выводить ли на экран страницу - true / false
       
       /** отображать ли футер */
       private $_showFooter = false;
	   /** отображать ли хэдер */
	   private $_showHeader = false;
	   /** файл хэдера (вместе с директорией) - для require_once */
	   private $_header;
	   /** флажок - выставляется, когда добавляется хэдер - для добавления скриптов в шапку */
	   private $_headerStart;
	   /** файл футера (вместе с директорией) - для require_once */
	   private $_footer;
	   /** папка, относительно которой будет производиться require футера и хэдера */
	   private $_dir;
	   
	   private static $page = null;
	   
	   public static function getInstance(){
	   		if (self :: $page == NULL) {
	   			self :: $page = new self();
			}
			return self :: $page;
	   }

       private function __construct(){
            mb_internal_encoding("UTF-8");
            date_default_timezone_set("Europe/Kiev");
            
			$this -> setDoctype(self :: $XHTML);		// доктайп по умолчанию
            $this -> setDefaultBase();
			
            ob_start();
        }
        
        public function __destruct(){
            $this -> show();
        }
		
        /** установка заголовка страницы */
        public function setTitle($title){
            $this -> _title = $title;
            return $this;
        }
        
        /** установка ключевых слов для сайта */
        public function setKeywords($keywords){
            $this -> _keywords = $keywords;
            return $this;
        }
        
        /** установка описания для сайта */
        public function setDescription($description){
            $this -> _description = $description;
            return $this;
        }
        
        /** установка иконки для сайта */
        public function setFavicon($favicon){
            $this -> _favicon = $favicon;
        }
        
        public function setCharset($charset){
            $this -> _charset = $charset;
        }
        
        /** устанавливаем переадресацию */
        function setRedirect($redirectLink){
            $this -> _redirect = $redirectLink;
        }
        
        /** устанавливаем базовую директорию по умолчанию */
        private function setDefaultBase(){
            $base = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
            $base = mb_substr($base,0,mb_strlen($base)-9);
            $this -> _basehref = $base;
            return $this;
        }
        
        /** устанавливаем базовую директорию */
        public function setBase($base){
            $this -> _basehref = $base;
            return $this;
        }
        
        /** Добавляем скрипт или несколько скриптов на страницу */
        public function addScript($script){
            $args = func_get_args();
            foreach ($args as $script){
                $script = trim($script);
				if ($this -> _headerStart)
                	$this->_headerJs .= "<script type='text/javascript' src='{$script}'></script>";
				else 
					$this->_js .= "<script type='text/javascript' src='{$script}'></script>";
            }
            return $this;
        }
        
        /** Добавляем файлы стилей на страницу */
        public function addCss($css){
            $args = func_get_args();
            foreach ($args as $css){
                $css = trim($css);
				if ($this -> _headerStart)
					$this->_headerCss.="<link rel='stylesheet' href='{$css}' type='text/css' media='{$this -> _cssMediaType}' />";
				else
					$this->_css.="<link rel='stylesheet' href='{$css}' type='text/css' media='{$this -> _cssMediaType}' />";
            }
            return $this;
        }
		
		public function addCssPrint($css){
            $args = func_get_args();
            foreach ($args as $css){
                $css = trim($css);
				if ($this -> _headerStart)
					$this->_headerCss.="<link rel='stylesheet' href='{$css}' type='text/css' media='print' />";
				else
					$this->_css.="<link rel='stylesheet' href='{$css}' type='text/css' media='print' />";
            }
            return $this;
        }
        
        public function addCssMediaWidth($css,$from=null,$to=null)
        {
                $css = trim($css);
                if($from==null)
                {
                    $minwidth="";
                }
                else
                {
                    $minwidth="and (min-width:{$from}px)";
                }
                if($to==null)
                {
                    $maxwidth="";
                }
                else
                {
                    $maxwidth="and (max-width:{$to}px)";
                }
				if ($this -> _headerStart)
                {
                    $this->_headerCss.="<link rel='stylesheet' media='screen {$minwidth} {$maxwidth}' href='{$css}' type='text/css' />";
                }
                else
                {
                    $this->_css.="<link rel='stylesheet' media='screen {$minwidth} {$maxwidth}' href='{$css}' type='text/css' />";
                }
                
           return $this;
            /*<link rel="stylesheet" media="screen and (max-width: 1210px)" href="css/styles_1000.css" />
            <link rel="stylesheet" media="screen and (min-width: 1211px) and (max-width: 1296px)" href="css/styles_1240.css" />
            <link rel="stylesheet" media="screen and (min-width: 1297px) and (max-width: 1370px)" href="css/styles_1320.css" />
            <link rel="stylesheet" media="screen and (min-width: 1371px)" href="css/styles_1400.css" />*/
        }
        
		/*Добавляем css  с id - для fsize*/
		  public function addFsize($css,$id){
                $css = trim($css);
				$id = trim($id);
				if ($this -> _headerStart)
					$this->_headerCss.="<link rel='stylesheet' href='{$css}' id='{$id}' type='text/css' media='screen' />";
				else
					$this->_css.="<link rel='stylesheet' href='{$css}' id='{$id}' type='text/css' media='screen' />";
            return $this;
        }
		  /** Добавляем файлы стилей на страницу c параметром*/
        
        public function addCssForIE($statement, $css){
            $args = func_get_args();
            $n = func_num_args();
			$result = '';
            for ($i = 1; $i < $n; $i++){
                $css = trim(func_get_arg($i));
                $result .= "<!--[if {$statement}]>";
                $result .= "<link rel='stylesheet' href='{$css}' type='text/css' media='screen' />";
                $result .= "<![endif]-->";
            }
			if ($this -> _headerStart) 
				$this -> _headerCss .= $result;
			else 
				$this -> _css .= $result;
			return $this;
        }
        
        /** Добавление кодов счетчиков 
         *  в имени файла обязательно нужно указать директорию => ./metrics/yandex.inc
         */
        public function addMetric($metrics){
            $args = func_get_args();
            foreach($args as $filename){
                $filename = trim($filename);
                $this -> _metrics .= file_get_contents($filename);
            }
            return $this;
        }
        
        /** установка DOCTYPE */
        public function setDoctype($doctype){
            $this -> _doctype = $doctype;
            return $this;
        }
        
        /** возвращает правильный html-тег */
        private function getHtmlTag(){
            $html = "<html>";
            switch ($this -> _doctype){
                case self :: $XHTML : $html = "<html xmlns='http://www.w3.org/1999/xhtml' lang='{$this -> _lang}'>"; break;
                case self :: $HTML5 : $html = "<html lang='{$this -> _lang}'>"; break;
            }
            return $html;
        } 
        
        /** Подготовка к выводу на экран - сбрасываем все в $_page */
        private function preparePage(){
            
            // контент страницы	
            $content = ob_get_clean();
			
			// хэадер
			ob_start();
			if ($this -> _showHeader){
				$this -> _headerStart = true;
				$this -> requireFile($this -> _header);
				$this -> _headerStart = false;
			}
			$header = ob_get_clean();
			
			// футер
			ob_start();
			if ($this -> _showFooter){
				$this -> requireFile($this -> _footer);
			}
			$footer = ob_get_clean();
			
            $this -> _page = $this -> _doctype.
                              $this -> getHtmlTag().
                              "<head>".
                              "<meta http-equiv='Content-Type' content='text/html; charset={$this->_charset}' />".
                              "<title>{$this -> _title}</title>".
                              "<!--<meta content='width=320, minimum-scale=1, maximum-scale=1' name='viewport'>-->
                              <meta name='description' content='{$this -> _description}' />".
                              "<meta name='keywords' content='{$this -> _keywords}' />".
                              "<base href='{$this -> _basehref}' />".
                              "<link rel='shortcut icon' href='{$this -> _favicon}' />".
                              $this -> _headerCss.
                              $this -> _css.
                              $this -> _headerJs.
                              $this -> _js.
                              "</head><body>".
                              $this -> _metrics.
                              $header.
                              $content.
                              $footer.
                              "</body></html>";
        }
        
		/** Отменяем вывод страницы на экран */
		public function hide(){
			$this -> _display = false;
		}
		
		/** выводим страницу на экран */
        public function show(){   
            $this->preparePage();
            if ($this -> _redirect != false){
			       header("Location: {$this -> _redirect}");
            } else {
                if ($this -> _display) {
                    header("Content-Type: text/html; charset={$this -> _charset}");
                    echo iconv("utf-8", $this -> _charset, $this -> _page);                
                }    
            }
        }
		
		/** установка медиа типа для добавляемых css файлов */
		public function setCssMediaType($mediaTypes){
			$this -> _cssMediaType = $mediaTypes;
			return $this;
		}
        
        /** задание языка сайта */
        public function setLang($lang){
        	$this -> _lang = $lang;
        }
        
		/** скрываем футер */
		public function hideFooter(){
			$this -> _showFooter = false;
			return $this;
		}
		/** скрываем хэадер */
		public function hideHeader(){
			$this -> _showHeader = false;
			return $this;
		}
		/** отображаем футер */
		public function showFooter(){
			$this -> _showFooter = true;
			return $this;
		}
		/** отображаем хэадер */
		public function showHeader(){
			$this -> _showHeader = true;
			return $this;
		}
		/** указываем файл футера */
		public function setFooter($filename){
			$this -> _footer = $filename;
			return $this;
		}
		
		/** указываем файл хэадера */
		public function setHeader($filename){
			$this -> _header = $filename;	
			return $this;
		}
		
		/** указываем директорию, относительно которой будет require файлов */
		public function setDir($dir){
			$this -> _dir = $dir;
			return $this;
		}
		
		/** возвращает директорию, относительно которой будет производиться require */
		private function getDir(){
			return $this -> _dir;
		}
		
		/** добавляем файл в проект (для футера и хэадера) */
		private function requireFile($filename){
			$filename = $this -> getDir() . '/' . $filename;
			if (file_exists($filename)){
				require_once ($filename);
			}
		}
    }
