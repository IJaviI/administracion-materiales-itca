<?php
// Carga de clases
require_once("cn.php");
require_once("cls_encriptar_desencriptar.php");
require_once("cls_departamentos.php");

class cls_usuarios extends cn {
    public function consult() {
        $sql = "SELECT * FROM usuario"; 
        return $this -> cn() -> query($sql);
    } 

    // Cerrar sesion
    public function cerrarSesion($id){
        $sql="UPDATE usuario SET estado = 'Inactivo' WHERE id_usuario = $id";
        $this -> cn() -> query($sql);
        session_destroy();
    }

    public function consultDocentesNoAdmin($creator_id) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE esadministrador != 1 AND created_by = $creator_id"; 
        return $this -> cn() -> query($sql);
    }

    public function consultAllUsersButCurrent($current_user_id) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE id_usuario != $current_user_id"; 
        return $this -> cn() -> query($sql);
    }

    // Consultando usuario en base a id
    public function consultDocente($id) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = $id"; 
        return $this -> cn() -> query($sql);
    }

    // Consultando usuario en base a carnet
    public function consultDocenteCarnet($carnet) {
        $sql = "SELECT * FROM usuario WHERE carnet = '$carnet'"; 
        return $this -> cn() -> query($sql);
    }

    // Filtros a usuarios

    // Filtrando usuarios por todo
    public function filterByAll($carnet, $names, $lastNames, $email) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.carnet LIKE '%$carnet%' AND usuario.nom_usuario LIKE '%$names%' AND usuario.ape_usuario LIKE '%$lastNames%' AND usuario.email LIKE '%$email%' AND usuario.esadministrador = 0";
        $result = $this -> cn() -> query($sql);
        return $result;
    }

    public function filterCarnetEmail($carnet, $email) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.carnet LIKE '%$carnet%' AND usuario.email LIKE '%$email%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterNamesLastNames($names, $lastNames) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.nom_usuario LIKE '%$names%' AND usuario.ape_usuario LIKE '%$lastNames%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterCarnetNames($carnet, $names) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.carnet LIKE '%$carnet%' AND usuario.nom_usuario LIKE '%$names%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEmailLastNames($email, $lastNames) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.email LIKE '%$email%' AND usuario.ape_usuario LIKE '%$lastNames%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterCarnetLastNames($carnet, $lastNames) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.carnet LIKE '%$carnet%' AND usuario.ape_usuario LIKE '%$lastNames%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEmailNames($email, $names) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.email LIKE '%$email%' AND usuario.nom_usuario LIKE '%$names%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterCarnet($carnet) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.carnet LIKE '%$carnet%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEmail($email) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.email LIKE '%$email%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterNames($names) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.nom_usuario LIKE '%$names%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }

    public function filterLastNames($lastNames) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario, usuario.ape_usuario, usuario.tipo, usuario.telcasa, usuario.celular, usuario.email, usuario.estado, usuario.clave, usuario.imagen, depto.depto, usuario.accesosistemas, usuario.esadministrador FROM usuario INNER JOIN depto ON usuario.id_depto = depto.id_depto WHERE usuario.ape_usuario LIKE '%$lastNames%' AND usuario.esadministrador = 0"; 
        return $this -> cn() -> query($sql);
    }
    
    // Deshabilitar usuario
    public function deshabilitarUsuario($id_usuario) {
        $sql = "UPDATE usuario SET accesosistemas = 0, estado = 'Deshabilitado' WHERE id_usuario = $id_usuario"; 
        $this -> cn() -> query($sql);
    }

    // Habilitar usuario
    public function habilitarUsuario($id_usuario) {
        $sql = "UPDATE usuario SET accesosistemas = 1, estado = 'Inactivo' WHERE id_usuario = $id_usuario"; 
        $this -> cn() -> query($sql);
    }

    // Eliminar usuario
    public function eliminarUsuario($id_usuario, $name) {
        $sql = "SELECT * FROM prestamo WHERE id_usuario = $id_usuario"; 
        $result = $this -> cn() -> query($sql);

        if(mysqli_num_rows($result) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout(function(){ alerts('Error, no es posible eliminar al usuario ($name) ya que este ya posee prestamos en su historial.'); }, 10);
                mostrarTabla();
            </script>";
        } else {
            $sql = "DELETE FROM usuario WHERE id_usuario = $id_usuario"; 
            $this -> cn() -> query($sql);
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout(function(){ alerts('El usuario ($name) ha sido eliminado con exito.'); }, 10);
                mostrarTabla();
            </script>";
        }
    }

    // Restablecer usuario
    public function restablecerUsuario($id_usuario, $name) {
        $obj_encriptar_desencriptar = new cls_encriptar_desencriptar;
        $encriptedPassword = $obj_encriptar_desencriptar -> encriptar_desencriptar('encriptar', 'itca123');

        $sql = "UPDATE usuario SET clave = '$encriptedPassword' WHERE id_usuario = $id_usuario"; 
        $result = $this -> cn() -> query($sql);

        return "
        <script>
            hideAlert();
            alertGreen();
            setTimeout(function(){ alerts(`La contraseña del usuario ($name) ha sido restablecida con exito a la contraseña predeterminada (itca123).`); }, 10);
            mostrarTabla();
        </script>";
    }

    // Editar usuario
    public function editUserNoImage($id, $carnet, $names, $lastnames, $type, $telcasa, $celular, $email, $depto) {
        $sql = "UPDATE usuario SET carnet = '$carnet' WHERE id_usuario = $id"; 
        $result = $this -> cn() -> query($sql);

        return "
        <script>
            hideAlert();
            alertGreen();
            setTimeout(function(){ alerts(`El usuario ($names $lastnames) ha sido editado con exito.`); }, 10);
            mostrarTabla();
        </script>";
    }

    public function crearAdmin($carnetAdmin, $namesAdmin, $lastNamesAdmin, $typeAdmin, $housePhoneAdmin, $phoneAdmin, $emailAdmin, $passwordAdmin, $imageAdmin, $imgSize) {
        $obj_encriptar_desencriptar = new cls_encriptar_desencriptar;

        $result = $this -> consult();        
        if(mysqli_num_rows($result) == 0) {
            $encriptedPassword = $obj_encriptar_desencriptar -> encriptar_desencriptar('encriptar', $passwordAdmin);

            if($imgSize == 0) {
                $sqlAdmin = "INSERT INTO usuario (carnet, nom_usuario, ape_usuario, tipo, telcasa, celular, email, estado, clave, imagen, accesosistemas, esadministrador) VALUES ('$carnetAdmin', '$namesAdmin', '$lastNamesAdmin', '$typeAdmin', '$housePhoneAdmin', '$phoneAdmin', '$emailAdmin', 'Activo', '$encriptedPassword', '$imageAdmin[name]', 1, 1)";
                $this -> cn() -> query($sqlAdmin);
                $usuario = $this -> consultDocenteCarnet($carnetAdmin);
                $data = $usuario -> fetch_assoc(); 

                $_SESSION["userProfile"]["id"] = $data["id_usuario"];
                $_SESSION["userProfile"]["carnet"] = $data["carnet"];
                $_SESSION["userProfile"]["names"] = $data["nom_usuario"];
                $_SESSION["userProfile"]["lastnames"] = $data["ape_usuario"];
                $_SESSION["userProfile"]["type"] = $data["tipo"];
                $_SESSION["userProfile"]["housePhone"] = $data["telcasa"];
                $_SESSION["userProfile"]["phone"] = $data["celular"];
                $_SESSION["userProfile"]["email"] = $data["email"];

                $_SESSION["admin_or_user"] = "admin";

                return "
                <script>
                    location.reload();
                </script>;
                ";
            } else if($imgSize < 2000000) {
                //Revisamos que el tipo de archivo sea una imagen tipo jpg, jpeg o png
                if($imageAdmin["type"] != "image/jpeg" && $imageAdmin["type"] != "image/png") {
                    return "
                    <script>
                        hideAlert();
                        alertRed();
                        setTimeout( function() { alerts('Error, el archivo ingresado debe ser una imagen de tipo jpg, jpeg o png.'); }, 1);
                    </script>;
                    ";
                } else {
                    $sqlAdmin = "INSERT INTO usuario (carnet, nom_usuario, ape_usuario, tipo, telcasa, celular, email, estado, clave, imagen, accesosistemas, esadministrador) VALUES ('$carnetAdmin', '$namesAdmin', '$lastNamesAdmin', '$typeAdmin', '$housePhoneAdmin', '$phoneAdmin', '$emailAdmin', 'Activo', '$encriptedPassword', '$imageAdmin[name]', 1, 1)";
                    $this -> cn() -> query($sqlAdmin);
                    $usuario = $this -> consultDocenteCarnet($carnetAdmin);
                    $data = $usuario -> fetch_assoc();

                    move_uploaded_file($imageAdmin["tmp_name"], $imageAdmin["folder"]);
    
                    $_SESSION["userProfile"]["id"] = $data["id_usuario"];
                    $_SESSION["userProfile"]["carnet"] = $data["carnet"];
                    $_SESSION["userProfile"]["names"] = $data["nom_usuario"];
                    $_SESSION["userProfile"]["lastnames"] = $data["ape_usuario"];
                    $_SESSION["userProfile"]["type"] = $data["tipo"];
                    $_SESSION["userProfile"]["housePhone"] = $data["telcasa"];
                    $_SESSION["userProfile"]["phone"] = $data["celular"];
                    $_SESSION["userProfile"]["email"] = $data["email"];
                    $_SESSION["userProfile"]["photo"] = $data["imagen"];
                    
                    $_SESSION["admin_or_user"] = "admin";
    
                    return "
                    <script>
                        location.reload();
                    </script>;
                    ";
                    // // Revisamos si la imagen ya existe
                    // if(!file_exists($imageAdmin["folder"])) {
                        
                    // } else {
                    //     $type = "";
                    //     if($imageAdmin["type"] == "image/jpeg") {
                    //         $type = "jpg";
                    //     } else if ($imageAdmin["type"] == "image/png") {
                    //         $type = "png";
                    //     } 

                    //     return "
                    //     <script>
                    //         hideAlert();
                    //         alertRed();
                    //         setTimeout( function() { alerts('Error, ya existe una imagen tipo ($type) con un nombre identico, ingrese un nombre diferente a la imagen.'); }, 1);
                    //     </script>
                    // ";
                    // }   
                }
            } else {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, el peso del archivo no puede ser mayor a 2mb.'); }, 1);
                </script>
                ";
            }
        }
    }

    public function existsCarnet($carnet) {
        $sql = "SELECT * FROM usuario WHERE carnet = '$carnet'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsFullName($names, $lastNames) {
        $sql = "SELECT * FROM usuario WHERE nom_usuario = '$names' AND ape_usuario = '$lastNames'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsHousePhone($housePhoneUser) {
        $sql = "SELECT * FROM usuario WHERE telcasa = '$housePhoneUser'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsPhone($phoneUser) {
        $sql = "SELECT * FROM usuario WHERE celular = '$phoneUser'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsEmail($emailUser) {
        $sql = "SELECT * FROM usuario WHERE email = '$emailUser'"; 
        return $this -> cn() -> query($sql);
    }

    public function sameCargo($id, $cargo) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = $id AND tipo = '$cargo'"; 
        return $this -> cn() -> query($sql);
    }

    public function sameDepto($id, $depto) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = $id AND id_depto = $depto"; 
        return $this -> cn() -> query($sql);
    }

    public function samePassword($id, $password) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = $id AND clave = '$password'"; 
        return $this -> cn() -> query($sql);
    }

    public function crearUser($carnetUser, $namesUser, $lastNamesUser, $typeUser, $housePhoneUser, $phoneUser, $emailUser, $passwordUser, $imageUser, $deptoUser, $imgSize, $admin, $creator_id) {
        $obj_encriptar_desencriptar = new cls_encriptar_desencriptar;
        $result = $this -> existsCarnet($carnetUser);
        if(mysqli_num_rows($result) == 0) {
            $result = $this -> existsFullName($namesUser, $lastNamesUser);
            if(mysqli_num_rows($result) == 0) {
                $result = $this -> existsHousePhone($housePhoneUser);
                if(mysqli_num_rows($result) == 0) {
                    $result = $this -> existsPhone($phoneUser);
                    if(mysqli_num_rows($result) == 0) {
                        $result = $this -> existsEmail($emailUser);
                        if(mysqli_num_rows($result) == 0) {
                            $encriptedPassword = $obj_encriptar_desencriptar -> encriptar_desencriptar('encriptar', $passwordUser);

                            if($imgSize == 0) {
                                $sqlUser = "INSERT INTO usuario (carnet, nom_usuario, ape_usuario, tipo, telcasa, celular, email, estado, clave, imagen, id_depto, accesosistemas, esadministrador, created_by) VALUES ('$carnetUser', '$namesUser', '$lastNamesUser', '$typeUser', '$housePhoneUser', '$phoneUser', '$emailUser', 'Inactivo', '$encriptedPassword', '$imageUser[name]', '$deptoUser', 1, $admin, $creator_id)";
                                $this -> cn() -> query($sqlUser);

                                return "
                                <script>
                                    hideAlert();
                                    alertGreen();
                                    setTimeout( function() { alerts('Usuario ($namesUser $lastNamesUser) agregado con exito.'); }, 1);
                                </script>
                                ";
                            } else if($imgSize < 2000000) {
                                //Revisamos que el tipo de archivo sea una imagen tipo jpg, jpeg o png
                                if($imageUser["type"] != "image/jpeg" && $imageUser["type"] != "image/png") {
                                    return "
                                    <script>
                                        hideAlert();
                                        alertRed();
                                        setTimeout( function() { alerts('Error, el archivo ingresado debe ser una imagen de tipo jpg, jpeg o png.'); }, 1);
                                    </script>
                                    ";
                                } else {
                                    $sqlUser = "INSERT INTO usuario (carnet, nom_usuario, ape_usuario, tipo, telcasa, celular, email, estado, clave, imagen, id_depto, accesosistemas, esadministrador, created_by) VALUES ('$carnetUser', '$namesUser', '$lastNamesUser', '$typeUser', '$housePhoneUser', '$phoneUser', '$emailUser', 'Inactivo', '$encriptedPassword', '$imageUser[name]', '$deptoUser', 1, 0, $creator_id)";
                                    $this -> cn() -> query($sqlUser);

                                    move_uploaded_file($imageUser["tmp_name"], $imageUser["folder"]);

                                    return "
                                    <script>
                                        hideAlert();
                                        alertGreen();
                                        setTimeout( function() { alerts('Usuario ($namesUser $lastNamesUser) agregado con exito.'); }, 1);
                                    </script>
                                    ";
                                    // Descartado (imagenes pueden repetirse)
                                    // Revisamos si la imagen ya existe
                                    // if(!file_exists($imageUser["folder"])) {
                                        
                                    // } else {
                                    //     $type = "";
                                    //     if($imageUser["type"] == "image/jpeg") {
                                    //         $type = "jpg";
                                    //     } else if ($imageUser["type"] == "image/png") {
                                    //         $type = "png";
                                    //     } 

                                    //     return "
                                    //     <script>
                                    //         hideAlert();
                                    //         alertRed();
                                    //         setTimeout( function() { alerts('Error, ya existe una imagen tipo ($type) con un nombre identico, ingrese un nombre diferente a la imagen.'); }, 1);
                                    //     </script>
                                    // ";
                                    // }   
                                }
                            } else {
                                return "
                                <script>
                                    hideAlert();
                                    alertRed();
                                    setTimeout( function() { alerts('Error, el peso del archivo no puede ser mayor a 2mb.'); }, 1);
                                </script>
                                ";
                            }
                        } else {
                            return "
                            <script>
                                hideAlert();
                                alertRed();
                                setTimeout( function() { alerts('Error, este email ya se encuentra asignado a otro usuario.'); }, 1);
                                $('#usuario_email1').css('borderColor', 'var(--color-wrong)');
                            </script>
                            ";
                        }
                    } else {
                        return "
                        <script>
                            hideAlert();
                            alertRed();
                            setTimeout( function() { alerts('Error, este celular ya se encuentra asignado a otro usuario.'); }, 1);
                            $('#usuario_phone1').css('borderColor', 'var(--color-wrong)');
                        </script>
                        ";
                    }
                } else {
                    return "
                    <script>
                        hideAlert();
                        alertRed();
                        setTimeout( function() { alerts('Error, este telefono de casa ya se encuentra asignado a otro usuario.'); }, 1);
                        $('#usuario_house-phone1').css('borderColor', 'var(--color-wrong)');
                    </script>
                    ";
                }
            } else {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, existe un usuario con nombres y apellidos identicos.'); }, 1);
                    $('#usuario_names1').css('borderColor', 'var(--color-wrong)');
                    $('#usuario_lastnames1').css('borderColor', 'var(--color-wrong)');
                </script>
                ";
            }
        } else {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('Error, este carnet ya se encuentra afiliado a un usuario.'); }, 1);
                $('#usuario_carnet1').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }

    public function actualizarCarnet($id, $carnet) {
        $exists = $this -> existsCarnet($carnet);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET carnet = '$carnet' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["carnet"] = $carnet;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tu carnet con exito a ($carnet).'); }, 1);
                $('#carnet').html('$carnet')
            </script>
            ";
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Error, este carnet ya se encuentra afiliado a un usuario.'); }, 1);
                $('#usuario_carnet').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }

    public function actualizarNombres($id, $nombres) {
        $exists = $this -> existsFullName($nombres, $_SESSION["userProfile"]["lastnames"]);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET nom_usuario = '$nombres' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["names"] = $nombres;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tus nombres con exito a ($nombres).'); }, 1);
                $('#nombres').html('$nombres')
                $('#userWelcome').html('$nombres')
            </script>
            ";
        } else {
            $apellidos = $_SESSION["userProfile"]["lastnames"];
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya existe un usuario con los nombres ($nombres) y tus apellidos ($apellidos).'); }, 1);
                $('#usuario_names').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }

    public function actualizarApellidos($id, $apellidos) {
        $exists = $this -> existsFullName($_SESSION["userProfile"]["names"], $apellidos);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET ape_usuario = '$apellidos' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["lastnames"] = $apellidos;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tus apellidos con exito a ($apellidos).'); }, 1);
                $('#apellidos').html('$apellidos')
            </script>
            ";
        } else {
            $nombres = $_SESSION["userProfile"]["names"];
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya existe un usuario con los apellidos ($apellidos) y tus nombres ($nombres).'); }, 1);
                $('#usuario_lastnames').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }

    public function actualizarEmail($id, $email) {
        $exists = $this -> existsEmail($email);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET email = '$email' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["email"] = $email;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tu email con exito a ($email).'); }, 1);
                $('#emailUser').html('$email')
                $('#email').html('$email')
            </script>
            ";
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya existe un usuario con el email ($email).'); }, 1);
                $('#usuario_email').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }

    public function actualizarTelCasa($id, $tel) {
        $exists = $this -> existsHousePhone($tel);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET telcasa = '$tel' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["housePhone"] = $tel;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tu telefono de casa con exito a ($tel).'); }, 1);
                $('#telefonoCasa').html('$tel')
            </script>
            ";
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya existe un usuario con el telefono de casa ($tel).'); }, 1);
                $('#usuario_housePhone').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }
    
    public function actualizarCelular($id, $celular) {
        $exists = $this -> existsPhone($celular);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET celular = '$celular' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["phone"] = $celular;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tu celular con exito a ($celular).'); }, 1);
                $('#telefono').html('$celular')
            </script>
            ";
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya existe un usuario con el celular ($celular).'); }, 1);
                $('#usuario_housePhone').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }

    public function actualizarCargo($id, $cargo) {
        $exists = $this -> sameCargo($id, $cargo);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET tipo = '$cargo' WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $_SESSION["userProfile"]["type"] = $cargo;
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tu cargo con exito a ($cargo).'); }, 1);
                $('#tipo').html('$cargo')
            </script>
            ";
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya posees el cargo ($cargo).'); }, 1);
            </script>
            ";
        }
    }

    public function actualizarDepto($id, $depto) {
        $exists = $this -> sameDepto($id, $depto);
        if(mysqli_num_rows($exists) == 0) {
            $sql = "UPDATE usuario SET id_depto = $depto WHERE id_usuario = $id";
            $this -> cn() -> query($sql);

            $obj_departamentos = new cls_departamentos;
            $result = $obj_departamentos -> consultDepto($depto);
            $departamento = $result -> fetch_assoc();

            $_SESSION["userProfile"]["depto"] = $departamento["depto"];
            return "
            <script>
                hideAlert2();
                alertGreen2();
                setTimeout( function() { alertsUserMenu('Has editado tu cargo con exito a ($departamento[depto]).'); }, 1);
                $('#depto').html('$departamento[depto]')
            </script>
            ";
        } else {
            $obj_departamentos = new cls_departamentos;
            $result = $obj_departamentos -> consultDepto($depto);
            $departamento = $result -> fetch_assoc();

            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Ya perteneces al departamento ($departamento[depto]).'); }, 1);
            </script>
            ";
        }
    }

    public function actualizarPassword($id, $lastpassword, $password) {
        $obj_encriptar_desencriptar = new cls_encriptar_desencriptar;
        $lastpassword = $obj_encriptar_desencriptar -> encriptar_desencriptar('encriptar', $lastpassword);
        $encriptedPassword = $obj_encriptar_desencriptar -> encriptar_desencriptar('encriptar', $password);

        $existsLastPassword = $this -> samePassword($id, $lastpassword);

        if(mysqli_num_rows($existsLastPassword) == 1) {    
            $exists = $this -> samePassword($id, $encriptedPassword);
            if(mysqli_num_rows($exists) == 0) {    
                $sql = "UPDATE usuario SET clave = '$encriptedPassword' WHERE id_usuario = $id";
                $this -> cn() -> query($sql);

                return "
                <script>
                    hideAlert2();
                    alertGreen2();
                    setTimeout( function() { alertsUserMenu('Has editado tu contraseña con exito.'); }, 1);
                </script>
                ";
            } else {
                return "
                <script>
                    hideAlert2();
                    alertRed2();
                    setTimeout( function() { alertsUserMenu('Introduzca una contraseña diferente a la actual.'); }, 1);
                    $('#usuario_password').css('borderColor', 'var(--color-wrong)');
                    $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
                </script>
                ";
            }
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Contraseña actual incorrecta.'); }, 1);
                $('#usuario_passwordLast').css('borderColor', 'var(--color-wrong)');
            </script>
            ";
        }
    }
    
    public function actualizarImagen($id, $imagen, $imgSize) {
        if($imgSize < 2000000) {
            //Revisamos que el tipo de archivo sea una imagen tipo jpg, jpeg o png
            if($imagen["type"] != "image/jpeg" && $imagen["type"] != "image/png") {
                return "
                <script>
                    hideAlert2();
                    alertRed2();
                    setTimeout( function() { alertsUserMenu('Error, el archivo ingresado debe ser una imagen de tipo jpg, jpeg o png.'); }, 1);
                    $('#newPhotoProfile').val(null);
                </script>;
                ";
            } else {
                // Revisamos si la imagen ya existe
                $sql = "UPDATE usuario SET imagen = '$imagen[name]' WHERE id_usuario = $id";
                $this -> cn() -> query($sql);

                move_uploaded_file($imagen["tmp_name"], $imagen["folder"]);
    
                $_SESSION["userProfile"]["photo"] = $imagen["name"];

                return "
                <script>
                    hideAlert2();
                    alertGreen2();
                    setTimeout( function() { alertsUserMenu('Has editado tu foto de perfil.'); }, 1);
                    $('#imgProfile').attr('src', 'img/app-photos/usuarios/$imagen[name]');
                    $('#imgProfile').removeClass('hidden');
                    $('#icon_modal').addClass('hidden');

                    $('#image_menu').attr('src', 'img/app-photos/usuarios/$imagen[name]');
                    $('#image_menu').removeClass('hidden');
                    $('#icon_menu').addClass('hidden');

                    $('#imgWelcome').attr('src', 'img/app-photos/usuarios/$imagen[name]');
                    $('#imgWelcome').removeClass('hidden');
                    $('#icon_welcome').addClass('hidden');

                    $('#newPhotoProfile').val(null);
                </script>
                "; 
            }
        } else {
            return "
            <script>
                hideAlert2();
                alertRed2();
                setTimeout( function() { alertsUserMenu('Error, el peso del archivo no puede ser mayor a 2mb.'); }, 1);
                $('#newPhotoProfile').val(null);
            </script>
            ";   
        }
    }

    public function csvUsuarios($file) {
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($file["type"], $mimes)) {

            move_uploaded_file($file["tmp_name"], $file["folder"]);

            $usuarios = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE){
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $usuarios[$encoded_row[0]] = $encoded_row;

                        // Another way of inserting the csv rows
                        // $inserting = $this -> cn() -> prepare("INSERT INTO marca (marca) VALUES (?)");
                        // $inserting -> bind_param("s", $row[1]);
                        // $inserting -> execute();
                    }

                    $data_repits = 0; 
                    foreach($usuarios as $usuario){
                        $carnetRepits = mysqli_num_rows($this -> existsCarnet($usuario[0]));
                        $fullNameRepits = mysqli_num_rows($this -> existsFullName($usuario[1], $usuario[2]));
                        $housePhoneRepits = mysqli_num_rows($this -> existsHousePhone($usuario[4]));
                        $phoneRepits = mysqli_num_rows($this -> existsPhone($usuario[5]));
                        $emailRepits = mysqli_num_rows($result = $this -> existsEmail($usuario[6]));

                        if($carnetRepits == 0 && $fullNameRepits == 0 && $housePhoneRepits == 0 && $phoneRepits == 0 && $emailRepits == 0) {
                            $sql = "INSERT INTO usuario (carnet, nom_usuario, ape_usuario, tipo, telcasa, celular, email, estado, clave, imagen, id_depto, accesosistemas, esadministrador, created_by) VALUES ('$usuario[0]', '$usuario[1]', '$usuario[2]', '$usuario[3]', '$usuario[4]', '$usuario[5]', '$usuario[6]', '$usuario[7]', '$usuario[8]', '$usuario[9]', '$usuario[10]', $usuario[11], $usuario[12], $usuario[13])";
                            $this -> cn() -> query($sql);
                        } else {
                            $data_repits++;        
                        }
                    }

                    if($data_repits == 0) {
                        return "
                        <script>
                            $('#csv_marcas').val(null);
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de usuarios fue insertado con exito.'); }, 1);
                        </script>
                        "; 
                    } else {
                        return "
                        <script>
                            $('#csv_marcas').val(null);
                            hideAlert();
                            $('#alert').css('background-color', '#ffe979');
                            $('#alert').css('color', 'var(--color-dark)');
                            setTimeout( function() { alerts('El archivo csv ha sido leido, algunos usuarios o datos de usuarios existentes se repiten por lo que solo se han agregado los que no se repiten (si todos se repiten la insercion no se llevara acabo).'); }, 1);
                        </script>
                        "; 
                    }
                }    
            } else {
                return "
                <script>
                    $('#csv_marcas').val(null);
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('El archivo csv esta vacio, porfavor verifique los datos del archivo.'); }, 1);
                </script>
                ";    
            }
        } else {
         return "
         <script>
            $('#csv_deptos').val(null);
            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Error, el archivo ingresado debe ser de tipo csv.'); }, 1);
         </script>
         ";    
        }
    }
}
?>