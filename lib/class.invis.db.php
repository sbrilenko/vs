<?php
    /*
     * Версия 1.05.08
	 *   - добавлен метод select()
	 * 1.05.05
	 *   - добавлен метод setCharset()
	 *   - добавлены методы store() и restore()
	 * 1.04.18
     *   - добавлен метод last()
     */
	require_once 'config.php';
    interface Idb{
        public static function getInstance();
		
		public function debug();
        
		public function query($query);
    	public function insert($table, $insertArray);
		public function update($table, $updateArray, $whereArray);
		public function delete($table, $whereArray);
		public function select($table, $whereArray, $fieldsArray);
        
        public function getValue();
        public function getArray();
        public function getRow();
        public function getColumn($columnName);
        public function getResource();
		public function getCount();
        public function last();
		
		public function store($label);
		public function restore($label);
        
		public function setCharset($charset);
        public function truncate($table);
    }

    class db /*implements Idb*/{
    	/** 
		 * 	объект класса db (реализация singleton) 
		 *  @access private
		 */
        private static $_instance = NULL;
		
        /**
		 * Кодировка страниц пользователя
		 * @access private
		 */
        private $_charset = 'utf8';
		
		/**
		 * переменная-флажок для включения/выключения режима отладки
		 */
    	private $_debug = false;
		
		/**
		 * Переменная, которая содержит результат (ресурс) выполнения запросов
		 */
        private $_result;     
		
		/**
		 * Количество записей в предыдущем выполненном запросе
		 */
        private $_count; 
		
		/**
		 * Последний вставленный id (или другое поле) для таблиц с автоинкрементом
		 */
        private $_last;             
        
		/**
		 * Стек для хранения результатов запросов, помеченных меткой
		 * [label] => resource
		 */
        private $_savedResources;
    	
		
		/** Параметры подключения хранятся в файле config.php - для совместимости со стаными проектами */
		private function __construct($dbName=const_dbName,$hostName=const_hostName,$userName=const_userName,$password=const_password){
		    if(!mysql_connect($hostName,$userName,$password)){
               echo "<b>Не удалось подключиться к базе данных!</b>";
            } else {
                mysql_select_db($dbName) or die(mysql_error());
                $this -> setCharset($this -> _charset);
            }
            
		}
		
		/** запрещаем клонирование объекта класса - singleton */
        private function __clone(){}
        
        public static function getInstance(){
            if (self :: $_instance == NULL){
                self :: $_instance = new self();
            }
            return self :: $_instance;
        }
        
        /**
         * Функция служит для включения и выключения режима отладки
         * В режиме отладки все запросы выводятся на экран
         * @param $state - включает / выключает режим отладки (true / false)
         */
        public function debug($state = true){
        	$this -> _debug = $state;
        }
        
        /**
         * Фунция выполняет запрос к БД
         * @param $query - строка sql-запроса
         */
        public function query($query){
        	if ($this -> _debug) echo "<pre>".$query."</pre>";
            $this -> _result = mysql_query($query) or die(mysql_error());
            $this -> _count = ($this -> _result) ? @mysql_num_rows($this -> _result) : 0;
            return $this;
        }   // конец функции query
        
        /** установка кодировки 
		 *  @param $charset - кодировка, например - utf8 или cp1251
		 */
		public function setCharset($charset){
			$this -> _charset = $charset;
			$this -> query('SET NAMES ' . $charset);
			return $this;
		}
		
		/**
	 	* Функция вставляет данные из insertArray в указанную таблицу $tableName Базы данных
	 	* $insertArray - ассоциативный массив, где ключи - названия полей в БД
	 	*/
		public function insert($table, $insertArray) {
			$sql = "INSERT INTO $table SET ".$this -> set($insertArray);
			$this -> query($sql);
            return $this;
		}	// end of function insert
	
		/**
	 	* Функция обновляет данные из updateArray в указанную таблицу $tableName Базы данных
	 	* $insertArray - ассоциативный массив, где ключи - названия полей в БД
	 	* $whereArray - ассоциативный массив условий
	 	*/
		public function update($table, $updateArray, $whereArray) {
			$sql = "UPDATE $table SET ".$this -> set($updateArray)." WHERE ".$this -> where($whereArray);
			$this -> query($sql);
			return $this;
		}	// end of function update
	
		/** 
         *  Функция удаляет данные из таблицы 
		 * 	$table - имя таблицы
		 * 	$whereArray - ассоциативный массив условий
		 */
		public function delete($table, $whereArray){
			$sql = "DELETE FROM $table WHERE ".$this -> where($whereArray);
			$this -> query($sql);
		} /* конец функции delete */
		
		/**
		 * Обертка для простых запросов SELECT
		 * $table - таблица
		 * $whereArray - ассоциативный массив параметров, по которым будет осуществляться поиск (если NULL - where будет отсутствовать)
		 * $fields - поля, которые будут выведены в результате: строка или массив строк
		 * $additional - возможность корректирования запросов: ORDER BY, LIMIT и т.п. - добавляется в конец (после WHERE блока)
		 */
		public function select($table, $whereArray, $fields = NULL, $additional = NULL){
			if ($fields == NULL){
				$fields = '*';
			} else {
				if (!is_array($fields)) $fields = array($fields);
				$fields = implode(', ',$fields);
			}
			$sql = "SELECT {$fields} FROM $table";
			if ($whereArray != null){
				$sql .= " WHERE " . $this -> where($whereArray);
			}
			if ($additional != NULL){
				$sql .= ' '.$additional;
			}
			$this -> query($sql);
			return $this;
		}
		
		/** возвращает результат в виде значения */
		public function getValue(){
		    return ($this -> _result) ?  mysql_result($this -> _result,0,0) : false;
		} /* конец фунцкии getValue() */
		
		
		/** функция возвращает результат запроса query() в виде массива */
		public function getArray(){
		    if ($this -> _result){
		        while ($row = mysql_fetch_assoc($this -> _result)){
		            $result[] = $row;
		        }
                return $result;
		    } else {
		        return false;
		    }
		} /* конец фунции getArray() */
		
		/**
		 * Функция возвращает строку из полученного запроса в виде ассоциативного массива
		 * @see scripts/Idb#getRow()
		 */
		public function getRow(){
			if ($this -> _result){
				$row = mysql_fetch_assoc($this -> _result);
				return $row;
			} else {
				return false;
			}
		}
		
		/** функция возвращает результат запроса query в виде массива значений выбранной колонки */
		public function getColumn($columnName){
		    if ($this -> _result){
		        while ($row = mysql_fetch_assoc($this -> _result)){
		            $result[] = $row[$columnName];
		        }
                return $result;
		    } else {
		        return false;
		    }
		}
		
		/** функция возвращает результат запроса query() в виде ресурса */
		public function getResource(){
		    return $this -> _result;
		}
        
        /** возвращает последний вставленный ID записи (только для таблиц с автоинкрементом) */
        public function last(){
            return mysql_insert_id();
        }
		
		/** функция очищает таблицу */
		public function truncate($table){
		    $sql = "TRUNCATE TABLE $table";
            $this -> query($sql);
			return $this;
		} /* конец функции truncate */
	
		/** Возвращает кол-во строк в последнем удачном запросе */
		public function getCount(){
		    return $this -> _count;
		}
		
		/** сохраняем результат с меткой */
		public function store($label = 'temp'){
			$this -> _savedResources[$label] = $this -> getResource();
			return $this;
		}
		
		public function restore($label = 'temp'){
			$this -> _result = $this -> _savedResources[$label];
			unset ($this -> _savedResources[$label]);
			return $this;
		}
		
		
		//
		// приватные функции
		//
		
		
		/** функция формирует условие для WHERE из ассоциативного массива */
		private function where($whereArray){
			foreach ($whereArray AS $key => $value){
				$result .= " AND ".$key."=".$this -> quote($value);
			}
			return mb_substr($result,5);
		} /* конец функции where */
		
		
		/**
		 * функция формирует SET из ассоциативного массива
		 * Пример: val1 = 'val1', val2 = 'val2'
		 */
		private function set($setArray){
			foreach ($setArray AS $key => $value){
				$result .= ", ".$key."=".$this -> quote($value);
			}
			return mb_substr($result,2);
		} /* конец функции set */
	
		/**
	 	* Служебная функция - приводит значения вставляемых элементов к правильному виду (mysql_real_escape_string)
	 	* и заключает их в кавычки
		* принимаемый аргумент может быть как массивом, так и просто переменной
	 	*/
		private function quote($valuesArray) {
			if (is_array($valuesArray)){
				foreach($valuesArray AS $key => $value) {
					$valuesArray[$key] = "'" . mysql_real_escape_string($value) . "'";
				}
				return $valuesArray;	
			} else {
				return "'".mysql_real_escape_string($valuesArray)."'";
			}
		}	// end of function sqlQuote
        /*
        *
        */
        public function getai($table)
        {
            $sql = "SHOW TABLE STATUS LIKE '{$table}'";
			$this -> query($sql);($sql);
			$arr=$this -> getArray();  
			return $arr[0]['Auto_increment'];
        }
    
    }   //конец класса db
?>