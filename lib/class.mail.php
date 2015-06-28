<?php
/*

 * 
 */

$to = $_POST['mail_to']; 

// тема письма 

$message = 'Имя сотрудника: '.$_POST['name'].'<br />Должность: '.$_POST['dolzhn'].' <br />Отзыв: '.$_POST['otziv']; 

$header="Content-type: text/html; charset=\"utf-8\"";
$header.="From: ".$subject." ".$to;
$header.="Subject: ".$subject;
$header.="Content-type: text/html; charset=\"utf-8\"";

//$message = '=?utf-8?B?'.base64_encode($message).'?=';
$subject = '=?utf-8?B?'.base64_encode("Отзыв о сотруднике").'?=';
$headers = "From: ".$subject." mail ".$to." \n";
mail("banana-art@mail.ru", $subject, $message, $header);
?>
