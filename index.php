<?php
session_start();
// session_destroy();   

require_once("php/controladores/cls_usuarios.php");
$obj_usuarios = new cls_usuarios;

$domain = $_SERVER['HTTP_HOST'];
$resource = $_SERVER['REQUEST_URI'];
$location = explode("/", $resource) ;

define("RUTA", "http://$domain/$location[1]/");
$_SESSION["RUTA"] = RUTA;

if(!isset($_SESSION["theme-dark"]) && !isset($_SESSION["theme-light"])) {
    $_SESSION["theme-dark"] = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>Prestamos Itca</title>

    <link rel="stylesheet" href="recursos/jquery.dataTables.css">
    <link rel="stylesheet" href="style/main.min.css">
    <link rel="shortcut icon" href="img/logo purple (ITCA - FEPADE).jpg" type="image/x-icon">
    <script src="recursos/jquery-3.7.0.min.js"></script>
    
    <!-- For searchable selects -->
    <script src="recursos/selectize.min.js"></script>
    <link rel="stylesheet" href="recursos/selectize.css">
</head>
<body style="<?php 
if(isset($_SESSION["theme-dark"])) {
    echo "background-color: #2d2d2d";
} else if(isset($_SESSION["theme-light"])) {
    echo "background-color: #fff";
}
?>">

<?php
    // Temas de la app definitivos (no temporales)
    if(isset($_SESSION["theme-dark"])) {
        echo "
        <script>
        // Getting elements related to the app's theme
        const variablesContainer = document.querySelector('body');
        variablesContainer.classList.add('theme-dark');
        variablesContainer.classList.remove('theme-light');
        </script>";
    }
    
    if(isset($_SESSION["theme-light"])) {
        echo "
        <script>
        // Getting elements related to the app's theme
        const variablesContainer = document.querySelector('body');
        
        variablesContainer.classList.add('theme-light');
        variablesContainer.classList.remove('theme-dark');
        </script>";
    }
    
    // Aqui decidimos si instanciar el formulario de acceso si la session no existe
    if(!isset($_SESSION["admin_or_user"])) {
        require_once("php/vistas/access_view.php");
    } 
    // Aqui decidimos si abrir la app para el admin o el usuario cuando la session existe
    else if ($_SESSION["admin_or_user"] === "admin") {
        require_once("php/vistas/appAdmin_view.php");
    } else if ($_SESSION["admin_or_user"] === "user") {
        if(isset($_SESSION["userProfile"])) {
            $usuario = $obj_usuarios -> consultDocente($_SESSION["userProfile"]["id"]);
            $acceso = $usuario -> fetch_assoc();
            
            // Verificamos si el acceso a la aplicacion esta permitido
            
            // Se elimino al usuario se le remueve acceso al sistemna
            if($acceso == null) {
                session_destroy();
                require_once("php/vistas/access_view.php");
            } else if($acceso["accesosistemas"] == 1) { // Se elimino al usuario se le remueve acceso al sistemna
                require_once("php/vistas/appUser_view.php");
            } else {
                session_destroy();
                require_once("php/vistas/access_view.php");
            }
        }
    }
    ?>
    <script src="script.js"></script>
    <script src="recursos/jquery.dataTables.min.js"></script>
</body>
</html>