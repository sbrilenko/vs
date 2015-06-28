<?php
/**
 * @author S.Brilenko
 * @version 1
 */

class sms {
	private $_client;               
    private $_result;
    private $_login='';
    private $_pass='';
    
	
	function __construct() {
	    $this -> _client =new SoapClient ('http://turbosms.in.ua/api/wsdl.html');
	}
    /* авторизация на сайте
    */
    public function auth()
    {
        $auth = Array ( 
                'login' => $this->_login, 
                'password' => $this->_pass
            ); 
        // Авторизируемся на сервере 
        $result = $this -> _client->Auth ($auth);
        if($result->AuthResult) return true;
        else return false;
    }
	/*устанавливаем логин*/
    public function setLogin($login){
        	$this -> _login = $login;
		return $this;
    }
    /*устанавливаем пароль*/
    public function setPass($pass){
        	$this -> _pass = $pass;
		return $this;
    }
    /*достаем баланс
    * возвращает строку,нужно привести к типу (float), (int) 
    * если не авторизован то выдаст ноль
    */
    public function getBalans(){
        return $this->_client->GetCreditBalance()->GetCreditBalanceResult;  
    }
    /*отправляем смс
    * $from - зарегистрированная на сайте подпись, если не зарегистрированно, то не отправится
    * $numbers - номер/a(через запятую, без пробела) в формате '+380XXXXXXXXX'
    * $text - Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
    */
    public function sendsms($from,$numbers,$text)
    {
        $sms = Array ( 
                'sender' => $from, 
                'destination' => $numbers, 
                'text' => $text 
            );
         return $this->_result=$this->_client->SendSMS ($sms);
        
    }
    /* Отправляем сообщение с WAPPush ссылкой. 
    * Ссылка должна включать http:// 
    * $from - зарегистрированная на сайте подпись, если не зарегистрированно, то не отправится
    * $numbers - номер/a(через запятую, без пробела) в формате '+380XXXXXXXXX'
    * $text - Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
    * $wappush - например 'http://super-site.com';
    */
    public function sendwappush($from,$numbers,$text,$wappush)
    {
        $sms = Array ( 
                'sender' => $from, 
                'destination' => $numbers, 
                'text' => $text, 
                'wappush' => $wappush 
            ); 
        return $this->_result=$this->_client->SendSMS ($sms);
    }
    /* ID сообщения по номеру
    * с нулем  - Выводим результат отправки 
    * от цифры 1 и до количества смс id конкретного сообщения
    */
    public function smsresultbynumb($numb)
    {
      return $this->_result->SendSMSResult->ResultArray[$numb];
    }
    /*Запрашиваем статус конкретного сообщения по ID 
    * $smsid - пример 'c9482a41-27d1-44f8-bd5c-d34104ca5ba9'
    */
    public function GetMessageStatusResult($smsid)
    {
        $sms = Array ('MessageId' =>$smsid); 
        return  $this->_client->GetMessageStatus ($sms)->GetMessageStatusResult; 
        
    }
    /* 
    * Запрашиваем массив ID сообщений, у которых неизвестен статус отправки 
    * вовращает массив
    */
    public function getmessages()
    {
        $this->_client->GetNewMessages();
    }
    /*выводим список статусов всех неизвестных статусов смс
    * $res - вызов функции getmessages() 
    */
    public function getAllStatus($res)
    {
        $mass=array();
        // Есть сообщения 
        if (!empty ($res->GetNewMessagesResult->ResultArray)) { 
            // Запрашиваем статус каждого сообщения по ID 
            foreach ($this->_client->GetNewMessagesResult->ResultArray as $msg_id) { 
                $sms = Array ('MessageId' => $msg_id); 
                $status = $this->_client->GetMessageStatus ($sms); 
                print_r($status->GetMessageStatusResult); 
            } 
        }
    }
    

}
?>
