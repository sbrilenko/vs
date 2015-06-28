<?php
session_start();
if ($_COOKIE['login']) $_SESSION['user_login'] = $_COOKIE['login'];
if ($_COOKIE['password']) $_SESSION['user_pass'] = $_COOKIE['password'];
if ($_COOKIE['UID']) $_SESSION['userID']  = $_COOKIE['UID'];
 
$isAdmin = mysql_fetch_array(mysql_query("SELECT id FROM admin WHERE login = '{$_SESSION['user_login']}' AND pass = '{$_SESSION['user_pass']}' AND id = {$_SESSION['userID']}"));

if (!isset($_SESSION['user_login']) or !isset($_SESSION['user_pass']) or !isset($_SESSION['userID']) or !$isAdmin['id'])
{
    Header ("Location: /admin/in");
    exit;
}

?>