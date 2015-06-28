<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root."/lib/class.invis.db.php";
$db = db :: getInstance();
if($_POST['action'] == 'check')
{
    $db -> query("SELECT new FROM `orders` WHERE new = 1");
    $count = $db -> getCount();
    if ($count) echo $count;
}
else
{
    return false;
}
?>