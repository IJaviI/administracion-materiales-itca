<?php
session_start();
require_once("../controladores/cls_usuarios.php");
require_once("../controladores/cls_departamentos.php");
$obj_usuarios = new cls_usuarios;
$obj_departamentos = new cls_departamentos;

// Mostrar tabla
if(isset($_GET["tabla"])) {
    $result = $obj_usuarios->consultDocentesNoAdmin($_SESSION["userProfile"]["id"]);
    getData($result);
}

// Creando primer usuario (admin)
$adminCreated = "";
if(isset($_POST["carnetAdmin"]) && isset($_POST["namesAdmin"]) && isset($_POST["lastNamesAdmin"]) && isset($_POST["typeAdmin"]) && isset($_POST["housePhoneAdmin"]) && isset($_POST["phoneAdmin"]) && isset($_POST["emailAdmin"]) && isset($_POST["passwordAdmin"])) {
    $_SESSION["carnetAdmin"] = $_POST["carnetAdmin"];
    $_SESSION["namesAdmin"] = $_POST["namesAdmin"];
    $_SESSION["lastnamesAdmin"] = $_POST["lastNamesAdmin"];
    $_SESSION["typeAdmin"] = $_POST["typeAdmin"];
    $_SESSION["housePhoneAdmin"] = $_POST["housePhoneAdmin"];
    $_SESSION["phoneAdmin"] = $_POST["phoneAdmin"];
    $_SESSION["emailAdmin"] = $_POST["emailAdmin"];
    $_SESSION["passwordAdmin"] = $_POST["passwordAdmin"];
}

if(array_key_exists('admin_image', $_FILES)) {
    $img_name = $_FILES['admin_image']['name'];
    $img_size = $_FILES['admin_image']['size'];
    $img_type = $_FILES['admin_image']['type'];
    $tmp_name = $_FILES['admin_image']['tmp_name'];
    $folder = "../../img/app-photos/usuarios/" . $img_name;

    $image["name"] = $img_name;
    $image["type"] = $img_type;
    $image["tmp_name"] = $tmp_name;
    $image["folder"] = $folder;

    $adminCreated.= $obj_usuarios -> crearAdmin($_SESSION["carnetAdmin"], $_SESSION["namesAdmin"], $_SESSION["lastnamesAdmin"], $_SESSION["typeAdmin"], $_SESSION["housePhoneAdmin"], $_SESSION["phoneAdmin"], $_SESSION["emailAdmin"], $_SESSION["passwordAdmin"], $image, $img_size);
} 

if(isset($_POST['noAdminImage'])) {
    $imgSize = 0;
    $image["name"] = "NULL";

    $adminCreated.= $obj_usuarios -> crearAdmin($_SESSION["carnetAdmin"], $_SESSION["namesAdmin"], $_SESSION["lastnamesAdmin"], $_SESSION["typeAdmin"], $_SESSION["housePhoneAdmin"], $_SESSION["phoneAdmin"], $_SESSION["emailAdmin"], $_SESSION["passwordAdmin"], $image, $imgSize);
}

// Creando usuario nuevo
$userCreated = "";

if(isset($_POST["carnetUser"]) && isset($_POST["namesUser"]) && isset($_POST["lastNamesUser"]) && isset($_POST["typeUser"]) && isset($_POST["housePhoneUser"]) && isset($_POST["phoneUser"]) && isset($_POST["emailUser"]) && isset($_POST["passwordUser"]) && isset($_POST["deptoUser"]) && isset($_POST["typeOfUser"])) {
    $_SESSION["carnetUser"] = $_POST["carnetUser"];
    $_SESSION["namesUser"] = $_POST["namesUser"];
    $_SESSION["lastnamesUser"] = $_POST["lastNamesUser"];
    $_SESSION["typeUser"] = $_POST["typeUser"];
    $_SESSION["housePhoneUser"] = $_POST["housePhoneUser"];
    $_SESSION["phoneUser"] = $_POST["phoneUser"];
    $_SESSION["emailUser"] = $_POST["emailUser"];
    $_SESSION["passwordUser"] = $_POST["passwordUser"];
    $_SESSION["deptoUser"] = $_POST["deptoUser"];
    $_SESSION["typeOfUser"] = $_POST["typeOfUser"];
}


if(array_key_exists('user_image', $_FILES)) {
    $img_name = $_FILES['user_image']['name'];
    $img_size = $_FILES['user_image']['size'];
    $img_type = $_FILES['user_image']['type'];
    $tmp_name = $_FILES['user_image']['tmp_name'];
    $folder = "../../img/app-photos/usuarios/" . $img_name;

    $image["name"] = $img_name;
    $image["type"] = $img_type;
    $image["tmp_name"] = $tmp_name;
    $image["folder"] = $folder;

    $userCreated.= $obj_usuarios -> crearUser($_SESSION["carnetUser"], $_SESSION["namesUser"], $_SESSION["lastnamesUser"], $_SESSION["typeUser"], $_SESSION["housePhoneUser"], $_SESSION["phoneUser"], $_SESSION["emailUser"], $_SESSION["passwordUser"], $image, $_SESSION["deptoUser"], $img_size, $_SESSION["esadmin"], $_SESSION["userProfile"]["id"]);
} 

if(isset($_POST['noUserImage'])) {
    $imgSize = 0;
    $image["name"] = "NULL";

    $userCreated.= $obj_usuarios -> crearUser($_SESSION["carnetUser"], $_SESSION["namesUser"], $_SESSION["lastnamesUser"], $_SESSION["typeUser"], $_SESSION["housePhoneUser"], $_SESSION["phoneUser"], $_SESSION["emailUser"], $_SESSION["passwordUser"], $image, $_SESSION["deptoUser"], $imgSize, $_SESSION["typeOfUser"], $_SESSION["userProfile"]["id"]);
}

// Editando usuario
$editUser = "";
if(isset($_GET["editUser"]) && isset($_GET["user_id"]) && isset($_GET["user_carnet"]) && isset($_GET["user_names"]) && isset($_GET["user_lastnames"]) && isset($_GET["user_type"]) && isset($_GET["user_telcasa"]) && isset($_GET["user_celular"]) && isset($_GET["user_email"]) && isset($_GET["user_depto"])) {
    $id= $_GET["user_id"];
    $carnet = $_GET["user_carnet"];
    $names = $_GET["editCarnet"];
    $lastnames = $_GET["editCarnet"];
    $type = $_GET["editCarnet"];
    $telcasa = $_GET["editCarnet"];
    $celular = $_GET["editCarnet"];
    $email = $_GET["editCarnet"];
    $depto = $_GET["editCarnet"];

    $editUser.= $obj_usuarios -> editUserNoImage($id, $carnet, $names, $lastnames, $type, $telcasa, $celular, $email, $depto); 
}
echo $editUser;



// Editando en perfil de usuario

$editCarnet = "";
if(isset($_GET["editCarnet"])) {
    $id= $_SESSION["userProfile"]["id"];
    $carnet = $_GET["editCarnet"];

    $editCarnet.= $obj_usuarios -> actualizarCarnet($id, $carnet); 
}

$editNames = "";
if(isset($_GET["editNames"])) {
    $id= $_SESSION["userProfile"]["id"];
    $nombres = $_GET["editNames"];

    $editNames.= $obj_usuarios -> actualizarNombres($id, $nombres); 
}

$editLastNames = "";
if(isset($_GET["editLastNames"])) {
    $id= $_SESSION["userProfile"]["id"];
    $apellidos = $_GET["editLastNames"];

    $editLastNames.= $obj_usuarios -> actualizarApellidos($id, $apellidos); 
}

$editEmail = "";
if(isset($_GET["editEmail"])) {
    $id= $_SESSION["userProfile"]["id"];
    $email = $_GET["editEmail"];

    $editEmail.= $obj_usuarios -> actualizarEmail($id, $email); 
}

$editHousePhone = "";
if(isset($_GET["editHousePhone"])) {
    $id= $_SESSION["userProfile"]["id"];
    $tel = $_GET["editHousePhone"];

    $editHousePhone.= $obj_usuarios -> actualizarTelCasa($id, $tel); 
}

$editPhone = "";
if(isset($_GET["editPhone"])) {
    $id= $_SESSION["userProfile"]["id"];
    $celular = $_GET["editPhone"];

    $editPhone.= $obj_usuarios -> actualizarCelular($id, $celular); 
}

$editCargo = "";
if(isset($_GET["editCargo"])) {
    $id= $_SESSION["userProfile"]["id"];
    $cargo = $_GET["editCargo"];

    $editCargo.= $obj_usuarios -> actualizarCargo($id, $cargo); 
}

$editDepto = "";
if(isset($_GET["editDepto"])) {
    $id= $_SESSION["userProfile"]["id"];
    $depto = $_GET["editDepto"];

    $editDepto.= $obj_usuarios -> actualizarDepto($id, $depto); 
}

$editPassword = "";
if(isset($_GET["lastPassword"]) && isset($_GET["newPassword"])) {
    $id= $_SESSION["userProfile"]["id"];
    $lastPassword = $_GET["lastPassword"];
    $password = $_GET["newPassword"];

    $editPassword.= $obj_usuarios -> actualizarPassword($id, $lastPassword, $password); 
}


$editImage = "";
if(array_key_exists('editUserProfileImage', $_FILES)) {
    $id= $_SESSION["userProfile"]["id"];

    $img_name = $_FILES['editUserProfileImage']['name'];
    
    
    $img_size = $_FILES['editUserProfileImage']['size'];
    $img_type = $_FILES['editUserProfileImage']['type'];
    $tmp_name = $_FILES['editUserProfileImage']['tmp_name'];
    $folder = "../../img/app-photos/usuarios/" . $img_name;

    $image["name"] = $img_name;
    $image["type"] = $img_type;
    $image["tmp_name"] = $tmp_name;
    $image["folder"] = $folder;

    $editImage.= $obj_usuarios -> actualizarImagen($id, $image, $img_size);
}



// Agregando registros atraves de un csv
$csv_usuarios="";
if(array_key_exists('csv_usuarios', $_FILES)){
   $file_name = $_FILES["csv_usuarios"]["name"];
   $file_type = $_FILES["csv_usuarios"]["type"];
   $tmp_name = $_FILES["csv_usuarios"]["tmp_name"];
   $file_size = $_FILES["csv_usuarios"]["size"];
   $folder = "../../recursos/csv-files/". $file_name;
   
   $file["name"] = $file_name;
   $file["type"] = $file_type;
   $file["tmp_name"] = $tmp_name;
   $file["size"] = $file_size;
   $file["folder"] = $folder;

   $csv_usuarios.= $obj_usuarios -> csvUsuarios($file);
}
echo $csv_usuarios;


// Deshabilitar usuario
$deshabilitarUsuario = "";
if(isset($_GET["deshabilitarUsuario"]) && isset($_GET["id_usuario"]) && isset($_GET["usuario"])) {
    $id_usuario = $_GET["id_usuario"];
    $name = $_GET["usuario"];

    $obj_usuarios -> deshabilitarUsuario($id_usuario); 
    $deshabilitarUsuario.= "
    <script>
        hideAlert();
        alertGreen();
        setTimeout(function(){ alerts('El usuario ($name) ha sido deshabilitado con exito.'); }, 10);
        mostrarTabla();
    </script>
    ";
}
echo $deshabilitarUsuario;

// Habilitar usuario
$habilitarUsuario = "";
if(isset($_GET["habilitarUsuario"]) && isset($_GET["id_usuario"]) && isset($_GET["usuario"])) {
    $id_usuario = $_GET["id_usuario"];
    $name = $_GET["usuario"];

    $obj_usuarios -> habilitarUsuario($id_usuario); 
    $habilitarUsuario.= "
    <script>
        hideAlert();
        alertGreen();
        setTimeout(function(){ alerts('El usuario ($name) ha sido habilitado con exito.'); }, 10);
        mostrarTabla();
    </script>
    ";
}
echo $habilitarUsuario;

// Eliminar usuario
$eliminarUsuario = "";
if(isset($_GET["eliminarUsuario"]) && isset($_GET["id_usuario"]) && isset($_GET["usuario"])) {
    $id_usuario = $_GET["id_usuario"];
    $name = $_GET["usuario"];

    $eliminarUsuario.= $obj_usuarios -> eliminarUsuario($id_usuario, $name); 
}
echo $eliminarUsuario;

// Restablecer contraseña del usuario
$restablecerUsuario = "";
if(isset($_GET["restablecerUsuario"]) && isset($_GET["id_usuario"]) && isset($_GET["usuario"])) {
    $id_usuario = $_GET["id_usuario"];
    $name = $_GET["usuario"];

    $restablecerUsuario.= $obj_usuarios -> restablecerUsuario($id_usuario, $name); 
}
echo $restablecerUsuario;

// Mandar a editar usuario
$sendToEdit = "";
if(isset($_GET["editUsuario"]) && isset($_GET["id_usuario"])) {
    $_SESSION["usuario"]["id"] = $_GET["id_usuario"];

    if(isset($_SESSION["usuario"])){
        $usuario = $obj_usuarios -> consultDocente($_SESSION["usuario"]["id"]);
        $usuario_data = $usuario -> fetch_assoc();

        $_SESSION["usuario"]["usuario_carnet"] = $usuario_data["carnet"];
        $_SESSION["usuario"]["usuario_names"] = $usuario_data["nom_docente"];
        $_SESSION["usuario"]["usuario_lastnames"] = $usuario_data["ape_docente"];
        $_SESSION["usuario"]["usuario_type"] = $usuario_data["tipo"];
        $_SESSION["usuario"]["usuario_telcasa"] = $usuario_data["telcasa"];
        $_SESSION["usuario"]["usuario_celular"] = $usuario_data["celular"];
        $_SESSION["usuario"]["usuario_email"] = $usuario_data["email"];
        $_SESSION["usuario"]["usuario_depto"] = $usuario_data["id_depto"];
    }

    $sendToEdit.= "<script>window.location.href='editar-usuario'</script>"; 
}
echo $sendToEdit;

// Filtros a usuarios
if(isset($_GET["filterCarnet"]) && isset($_GET["filterEmail"]) && isset($_GET["filterNombres"]) && isset($_GET["filterApellidos"])) {
    $carnet = $_GET["filterCarnet"];
    $email = $_GET["filterEmail"];
    $names = $_GET["filterNombres"];
    $lastnames = $_GET["filterApellidos"];
    
    if($carnet != "" && $email != "" && $names != "" && $lastnames != "") {
        // Filtrar usuarios por todo
        $result = $obj_usuarios -> filterByAll($carnet, $names, $lastnames, $email);
        getData($result);
    } else if($carnet != "" && $email != "") {
        // Filtrar usuarios por carnet y email
        $result = $obj_usuarios -> filterCarnetEmail($carnet, $email);
        getData($result);
    } else if($names != "" && $lastnames != "") {
        // Filtrar usuarios por nombres y apellidos
        $result = $obj_usuarios -> filterNamesLastNames($names, $lastnames);
        getData($result);
    } else if($carnet != "" && $names != "") {
        // Filtrar usuarios por carnet y nombres
        $result = $obj_usuarios -> filterCarnetNames($carnet, $names);
        getData($result);
    } else if($email != "" && $lastnames != "") {
        // Filtrar usuarios por email y apellidos
        $result = $obj_usuarios -> filterEmailLastNames($email, $lastnames);
        getData($result);
    } else if($carnet != "" && $lastnames != "") {
        // Filtrar usuarios por carnet y apellidos
        $result = $obj_usuarios -> filterCarnetLastNames($carnet, $lastnames);
        getData($result);
    } else if($email != "" && $names != "") {
        // Filtrar usuarios por email y nombres
        $result = $obj_usuarios -> filterEmailNames($email, $names);
        getData($result);
    } else if($carnet != "") {
        // Filtrar carnet
        $result = $obj_usuarios -> filterCarnet($carnet);
        getData($result);
    } else if($email != "") {
        // Filtrar email
        $result = $obj_usuarios -> filterEmail($email);
        getData($result);
    } else if($names != "") {
        // Filtrar nombres
        $result = $obj_usuarios -> filterNames($names);
        getData($result);
    } else if($lastnames != "") {
        // Filtrar apellidos
        $result = $obj_usuarios -> filterLastNames($lastnames);
        getData($result);
    } else if($carnet == "" & $email == "" & $names == "" && $lastnames == "") {
        // Se muestran todos los usuarios
        $result = $obj_usuarios -> consultDocentesNoAdmin($_SESSION["userProfile"]["id"]);
        getData($result);
    }
}

// Mostrar tabla
function getData($result) {
    $tabla= "
    <table class='page__table' id='table-usuarios'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>    
                <th>Carnet</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Cargo</th>
                <th>Tel Casa</th>
                <th>Celular</th>
                <th>Email</th>
                <th>Departamento</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th style='text-align: center'>Acceso</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";


if(mysqli_num_rows($result) >= 1) {
    $i = 0;
    foreach($result as $usuario) {;
    $i++;

    $tabla.= "
            <tr class='page__table-row'>
                <td>$usuario[carnet]</td>
                <td>$usuario[nom_usuario]</td>
                <td>$usuario[ape_usuario]</td>
                <td>$usuario[tipo]</td>
                <td>$usuario[telcasa]</td>
                <td>$usuario[celular]</td>
                <td>$usuario[email]</td>
                <td>$usuario[depto]</td>
                <td>$usuario[estado]</td>
                <td class='no-padding'>
                    <button type='button' class='"; if($usuario["imagen"] == 'NULL'){ $tabla.="hidden"; } $tabla.="' id='mostrar-imagen-usuario-$i' style='background-color: var(--color-show-user-image)'>Mostrar</button>
                    <button type='button' class='"; if($usuario["imagen"] != 'NULL'){ $tabla.="hidden"; } $tabla.="' style='background-color: #828282; color: var(--color-light); cursor: default'>Sin imagen</button>
                </td>
                <td class='no-padding'>
                    <button type='button' class='"; if($usuario["accesosistemas"] == 0){ $tabla.="hidden"; } $tabla.="' id='deshabilitar-usuario-$i' style='color: var(--color-light); background-color: var(--color-wrong); margin-right: .1rem'>Deshabilitar</button>
                    <button type='button' class='"; if($usuario["accesosistemas"] != 0){ $tabla.="hidden"; } $tabla.="' id='habilitar-usuario-$i' style='background-color: var(--color-save); margin-right: .2rem'>Habilitar</button>
                </td>
                <td class='no-padding'>
                    <div>
                        <button type='button' id='eliminar-usuario-$i' style='color: var(--color-light); background-color: var(--color-wrong); margin-right: .2rem'>Eliminar</button>
                        <button type='button' id='restablecer-contraseña-usuario-$i' style='background-color: var(--color-remake-password); margin-right: .2rem'>Restablecer contraseña</button>
                        <button type='button' style='display: none' id='editar-usuario-$i' style='background-color: var(--color-edit);'>Editar</button>
                    </div>
                </td>
            </tr>
            
            <script>
            $('#deshabilitar-usuario-$i').mousedown(function(){
                hideAlert();
                setTimeout( function() { $('#resp').html(
                    `<div id='alertDeshabilitarUsuario$i' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closealertDeshabilitarUsuario$i()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de deshabilitar al usuario ($usuario[nom_usuario] $usuario[ape_usuario])?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='deshabilitarUsuario$i()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, deshabilitar</button>
                            <button type='button' onmousedown='closealertDeshabilitarUsuario$i()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                ) }, 1);    
            });

            function closealertDeshabilitarUsuario$i() {
                $('#alertDeshabilitarUsuario$i').addClass('hidden');
            }

            function deshabilitarUsuario$i() {
                obj = new Object();
                obj.deshabilitarUsuario = '';
                obj.id_usuario = $usuario[id_usuario];
                obj.usuario = '$usuario[nom_usuario] $usuario[ape_usuario]';

                $.ajax({url: 'php/ajax/ajax_usuarios.php?'+ $.param(obj), success: function(response){
                    $('#resp').html(response);
                }});
            }

            $('#habilitar-usuario-$i').mousedown(function(){
                hideAlert();
                setTimeout( function() { $('#resp').html(
                    `<div id='alertHabilitarUsuario$i' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closeAlertHabilitarUsuario$i()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de habilitar al usuario ($usuario[nom_usuario] $usuario[ape_usuario])?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='habilitarUsuario$i()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, habilitar</button>
                            <button type='button' onmousedown='closeAlertHabilitarUsuario$i()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                ) }, 1);    
            });

            function closeAlertHabilitarUsuario$i() {
                $('#alertHabilitarUsuario$i').addClass('hidden');
            }

            function habilitarUsuario$i() {
                obj = new Object();
                obj.habilitarUsuario = '';
                obj.id_usuario = $usuario[id_usuario];
                obj.usuario = '$usuario[nom_usuario] $usuario[ape_usuario]';

                $.ajax({url: 'php/ajax/ajax_usuarios.php?'+ $.param(obj), success: function(response){
                    $('#resp').html(response);
                }});
            }

            $('#eliminar-usuario-$i').mousedown(function(){
                hideAlert();
                setTimeout( function() { $('#resp').html(
                    `<div id='alertEliminarUsuario$i' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closeAlertEliminarUsuario$i()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de eliminar al usuario ($usuario[nom_usuario] $usuario[ape_usuario])?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='eliminarUsuario$i()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, eliminar</button>
                            <button type='button' onmousedown='closeAlertEliminarUsuario$i()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                ) }, 1);    
            });

            function closeAlertEliminarUsuario$i() {
                $('#alertEliminarUsuario$i').addClass('hidden');
            }

            function eliminarUsuario$i() {
                obj = new Object();
                obj.eliminarUsuario = '';
                obj.id_usuario = $usuario[id_usuario];
                obj.usuario = '$usuario[nom_usuario] $usuario[ape_usuario]';

                $.ajax({url: 'php/ajax/ajax_usuarios.php?'+ $.param(obj), success: function(response){
                    $('#resp').html(response);
                }});
            }

            $('#restablecer-contraseña-usuario-$i').mousedown(function(){
                hideAlert();
                setTimeout( function() { $('#resp').html(
                    `<div id='alertRestablecerUsuario$i' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closeAlertRestablecerUsuario$i()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de restablecer la contraseña del usuario ($usuario[nom_usuario] $usuario[ape_usuario])?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='restablecerUsuario$i()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, restablecer</button>
                            <button type='button' onmousedown='closeAlertRestablecerUsuario$i()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                ) }, 1);    
            });

            function closeAlertRestablecerUsuario$i() {
                $('#alertRestablecerUsuario$i').addClass('hidden');
            }

            function restablecerUsuario$i() {
                obj = new Object();
                obj.restablecerUsuario = '';
                obj.id_usuario = $usuario[id_usuario];
                obj.usuario = '$usuario[nom_usuario] $usuario[ape_usuario]';

                $.ajax({url: 'php/ajax/ajax_usuarios.php?'+ $.param(obj), success: function(response){
                    $('#resp').html(response);
                }});
            }

            $('#mostrar-imagen-usuario-$i').mousedown(function(){
                $('#userSelectedName').text('$usuario[nom_usuario] $usuario[ape_usuario]');
                $('#userSelectedImage').attr('src', 'img/app-photos/usuarios/$usuario[imagen]');

                $('#modalFoto__overlay').removeClass('hidden');
                $('#modal-foto-usuario').removeClass('hidden');
            });

            $('#editar-usuario-$i').mousedown(function(){
                Obj = new Object();
                Obj.editUsuario = '';
                Obj.id_usuario = $usuario[id_usuario];
                
                $.ajax({url: 'php/ajax/ajax_usuarios.php?'+$.param(Obj), success: function(respuesta){
                    $('#table-responsive').html(respuesta);
                }});
            });

            </script>";
    }
} else {
    $tabla.= "
        <tr class='page__table-row'>
            <td class='hidden'>1</td>
            <td class='hidden'>2</td>
            <td class='hidden'>3</td>
            <td class='hidden'>4</td>
            <td class='hidden'>5</td>
            <td class='hidden'>6</td>
            <td class='hidden'>7</td>
            <td class='hidden'>8</td>
            <td class='hidden'>9</td>
            <td class='hidden'>10</td>
            <td class='hidden'>11</td>
            <td colspan='12' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ningun usuario.</td>
        </tr>
        ";
}

    $tabla.= "
        </tbody>
    </table>
    
    <script>
        $('#table-usuarios').DataTable({
            'searching': false,
            'order': [[2, 'asc']],
            'pageLength': 5,
            'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos los']],
            'language': {
                'lengthMenu': 'Mostrar _MENU_ usuarios',
                'info': 'Mostrando _START_ a _END_ de _TOTAL_ usuarios',
                'paginate': {
                    'first':      'Primero',
                    'last':       'Ultimo',
                    'next':       'Siguiente',
                    'previous':   'Anterior'
                },
            }
        })
    </script>
    ";

    echo $tabla;
}

// // Si el boton edit es clickeado
// $sendToEdit = "";
// if(isset($_GET["aula_id"])) {
//     $_SESSION["aula"]["id"] = $_GET["aula_id"];

//     if(isset($_SESSION["aula"])){
//         $aula = $obj_aulas -> consultAula($_SESSION["aula"]["id"]);
//         $aula_data = $aula -> fetch_assoc();

//         $_SESSION["aula"]["aula_name"] = $aula_data["aula"];
//         $_SESSION["aula"]["aula_ubication"] = $aula_data["ubicacion"];
//         $_SESSION["aula"]["aula_description"] = $aula_data["descripcion"];
//         $_SESSION["aula"]["aula_type"] = $aula_data["tipo"];
//     }
    
//     $sendToEdit.= "<script>window.location.href='editar-aula'</script>";
// }

// $aulaEdit = "";
// // Actualizar aula
// if(isset($_GET["aula_idEdit"]) && isset($_GET["aula_nameEdit"]) && isset($_GET["aula_ubicationEdit"]) && isset($_GET["aula_descriptionEdit"]) && isset($_GET["aula_typeEdit"])) {
//     $aula_id = $_GET["aula_idEdit"];
//     $aula_name = $_GET["aula_nameEdit"];
//     $aula_ubication = $_GET["aula_ubicationEdit"];
//     $aula_description = $_GET["aula_descriptionEdit"];
//     $aula_type = $_GET["aula_typeEdit"];

//     $aulaEdit.= $obj_aulas -> updateAll($aula_id, $aula_name, $aula_ubication, $aula_description, $aula_type);
// } else if(isset($_GET["aula_idEdit"]) && isset($_GET["aula_ubicationEdit"]) && isset($_GET["aula_descriptionEdit"])) {
//     $aula_id = $_GET["aula_idEdit"];
//     $aula_ubication = $_GET["aula_ubicationEdit"];
//     $aula_description = $_GET["aula_descriptionEdit"];

//     $aulaEdit.= $obj_aulas -> updateUbicationDescription($aula_id, $aula_ubication, $aula_description);
// } else if(isset($_GET["aula_idEdit"]) && isset($_GET["aula_nameEdit"]) && isset($_GET["aula_typeEdit"])) {
//     $aula_id = $_GET["aula_idEdit"];
//     $aula_name = $_GET["aula_nameEdit"];
//     $aula_type = $_GET["aula_typeEdit"];

//     $aulaEdit.= $obj_aulas -> updateNameType($aula_id, $aula_name, $aula_type);
// }


echo $adminCreated;
echo $userCreated;

echo $editCarnet;
echo $editNames;
echo $editLastNames;
echo $editEmail;
echo $editHousePhone;
echo $editPhone;
echo $editCargo;
echo $editDepto;
echo $editPassword;
echo $editImage;
?>