<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
if(!empty($_POST))
{
    $name = htmlspecialchars(strip_tags($_POST['name']));
    if ($_POST['id'])
    {
        $db -> query("UPDATE sfirms SET name = '".$name."', idUserUpdate = '".$_SESSION['userID']."', dtUpdate = '".date('Y-m-d H:i:s')."' WHERE id = ".$_POST['id']);
    }
    else
    {
        $db -> query("INSERT INTO sfirms (name,idUserCreate,dtCreate) VALUES ('".$name."', '".$_SESSION['userID']."', '".date('Y-m-d H:i:s')."')");
    }
}
else
{
    print "<span style='color:red;' title='error'>Введите данные</span>";
}
?>
 
