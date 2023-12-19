<?php
session_start();

require_once("../controladores/cls_departamentos.php");
require_once("../controladores/cls_usuarios.php");
$obj_departamentos = new cls_departamentos;
$obj_usuarios = new cls_usuarios;
$departamentos = $obj_departamentos -> consult();

// Haciendo el cambio de claro a oscuro y viseversa permanente
if(isset($_GET["dark"])){
    if(!isset($_SESSION["theme-dark"])) {
        unset($_SESSION["theme-light"]);
        $_SESSION["theme-dark"] = 1;
    }    
}

if(isset($_GET["light"])) {
    if(!isset($_SESSION["theme-light"])) {
        unset($_SESSION["theme-dark"]);
        $_SESSION["theme-light"] = 1;
    }
}

// Cerrando sesion
if(isset($_GET["logOut"])) {
    $obj_usuarios->cerrarSesion($_SESSION["userProfile"]["id"]);
}

$deptos = "";
if(isset($_POST["showDepto"])){
    foreach($departamentos as $fila){
        $deptos.= "
        <script>
            $('#usuarioDepto').append(new Option('$fila[depto]', '$fila[id_depto]'));
        </script>";
    }
}

echo $deptos;
?>