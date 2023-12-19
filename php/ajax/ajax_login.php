<?php
session_start();

require_once("../controladores/cls_login.php");
$obj_login = new cls_login();

// Iniciando sesion
$userLogin = "";
if(isset($_GET["userEmail"]) && isset($_GET["userPassword"])) {
    $userEmail = $_GET["userEmail"];
    $userPassword = $_GET["userPassword"];

    $userLogin.= $obj_login -> login($userEmail, $userPassword);
}

echo $userLogin;
?>