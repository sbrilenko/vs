<?php
/**
 * @author S.Brilenko
 * @version 1
 */

class cl {
    private $_db;
	private $_client;               
    private $_result;
    private $_code;
    private $_login='';
    private $_pass='';
        
    function __construct() {
	    $this -> _db =  db :: getInstance();
	}
    /* генерирует 6 значный состоит из цифр и лат букв высокого регистра */
    public function gencode()
    {
        return mb_strtoupper(substr(uniqid(), -6, 6));
    }

}
?>
