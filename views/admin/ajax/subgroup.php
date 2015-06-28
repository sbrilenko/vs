<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/include.php";
$db = db :: getInstance(); 
//$editorClass = new editorClass();
if(!empty($_POST))
{
    $name = strip_tags($_POST['name']);
    $id = (int)$_POST['id'];
    if ($_POST['action'] == 'editsubgroup')
    {
        print "UPDATE ssubgroups SET name = '".$name."', idUserUpdate = '".$_SESSION['userID']."', dtUpdate = '".date('Y-m-d H:i:s')."' WHERE id = ".$id;
        $db -> query("UPDATE sSubgroups SET name = '".$name."', idUserUpdate = '".$_SESSION['userID']."', dtUpdate = '".date('Y-m-d H:i:s')."' WHERE id = ".$id);
        echo $id;
    }
    if ($_POST['action'] == 'addsubgroup')
    {
        $db -> query("INSERT INTO ssubgroups (name,idUserCreate,dtCreate,idUserUpdate,dtUpdate) VALUES ('".$name."', '".$_SESSION['userID']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['userID']."', '".date('Y-m-d H:i:s')."')");
        echo $id;
    }
    //print_r($_POST);
}
else
{
    print "<span style='color:red;' title='error'>Введите данные</span>";
}
?>
 
