<?php
require_once("cn.php");
require_once("cls_encriptar_desencriptar.php");
require_once("cls_departamentos.php");

class cls_login extends cn {
    public function login($email, $clave) {
        $obj_encriptar_desencriptar = new cls_encriptar_desencriptar;
        $obj_departamentos = new cls_departamentos;

        $matchEncriptedPassword = $obj_encriptar_desencriptar ->  encriptar_desencriptar('encriptar', $clave);

        $sql = "SELECT * FROM usuario WHERE email = '$email' AND clave = '$matchEncriptedPassword'";
        $result = $this -> cn() -> query($sql);
        $data = $result -> fetch_assoc();

        // Comprobamos si existe ese usuario
        if(mysqli_num_rows($result) == 1) {
            if($data['accesosistemas'] == 1) {
                $_SESSION["userProfile"]["id"] = $data["id_usuario"];
                $_SESSION["userProfile"]["carnet"] = $data["carnet"];
                $_SESSION["userProfile"]["names"] = $data["nom_usuario"];
                $_SESSION["userProfile"]["lastnames"] = $data["ape_usuario"];
                $_SESSION["userProfile"]["type"] = $data["tipo"];
                $_SESSION["userProfile"]["housePhone"] = $data["telcasa"];
                $_SESSION["userProfile"]["phone"] = $data["celular"];
                $_SESSION["userProfile"]["email"] = $data["email"];
                
                if($data["imagen"] != "NULL") {
                    $_SESSION["userProfile"]["photo"] = $data["imagen"];
                }
                
                if($data["id_depto"] != NULL) {
                    $depto = $obj_departamentos -> consultDepto($data["id_depto"]);
                    $depto = $depto  -> fetch_assoc();
                    $_SESSION["userProfile"]["depto"] = $depto["depto"];
                }

                // Decidimos si sera usuario o administrador la session global de inicio de sesion
                if($data['esadministrador'] != 1) {
                    $_SESSION["admin_or_user"] = "user";

                    if(!isset($_SESSION["theme-dark"]) && !isset($_SESSION["theme-light"])) {
                        $_SESSION["theme-dark"] = 1;
                    }

                    $sql = "UPDATE usuario SET estado = 'Activo' WHERE id_usuario = $data[id_usuario]";
                    $this -> cn() -> query($sql);

                    //recargamos la pagina
                    echo "<script>window.location.href='$_SESSION[RUTA]'</script>";
                } else {
                    $_SESSION["admin_or_user"] = "admin";

                    if(!isset($_SESSION["theme-dark"]) && !isset($_SESSION["theme-light"])) {
                        $_SESSION["theme-dark"] = 1;
                    }

                    $sql = "UPDATE usuario SET estado = 'Activo' WHERE id_usuario = $data[id_usuario]";
                    $this -> cn() -> query($sql);
                    
                    //recargamos la pagina
                    echo "<script>window.location.href='$_SESSION[RUTA]'</script>";
                }   
            } else {
                return "
                <script>
                    hideAlert();
                    setTimeout(function(){ alerts('Lo sentimos, el acceso al sistema a sido denegado, porfavor contacte al administrador.'); }, 10);
                </script>
                ";
            }
        } else {
            return "
            <script>
                hideAlert();
                setTimeout(function(){ alerts('Usuario o contraseña incorrectos, porfavor verifique y vuelva a intentar.'); }, 10);
            </script>
            ";
        }
    }
}

?>