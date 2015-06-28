<?php
session_name("Autentification");
session_start();
if (!isset($_SESSION['user_pass']))
{
    session_destroy();
    Header ("Location: /admin/in");
    exit;
}
else {
    $user_login = $_SESSION['user_login'];
    //$user_id = $_SESSION['user_id'];
}
?>