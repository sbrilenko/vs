<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance();
$editorClass = new editorClass();
if(!empty($_POST))
{
    print_r($_POST);
}
else
{
    print "<span style='color:red;'>Введите данные</span>";
}
?>
 
