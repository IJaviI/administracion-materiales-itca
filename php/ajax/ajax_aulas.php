<?php
session_start();

require_once("../controladores/cls_aulas.php");
$obj_aulas = new cls_aulas;

// Mostrar tabla
if(isset($_GET["tabla"])) {
    $result = $obj_aulas->consult();
    getData($result);
}

// Insertar aula
$addingAula = "";
if(isset($_GET["aula_name"]) && isset($_GET["aula_ubication"]) && isset($_GET["aula_description"]) && isset($_GET["aula_type"])) {
    $aula_name = $_GET["aula_name"];
    $aula_ubication = $_GET["aula_ubication"];
    $aula_description = $_GET["aula_description"];
    $aula_type = $_GET["aula_type"];

    $addingAula.= $obj_aulas -> insert($aula_name, $aula_ubication, $aula_description, $aula_type);
}

// Borrar aula
$yes_or_not = "";
if(isset($_GET["aula_idRemove"])) {
    $yes_or_not.= $obj_aulas -> delete($_GET["aula_idRemove"]);
}

// Filtros a aulas
if(isset($_GET["filterAulaName"]) && isset($_GET["filterAulaType"])) {
    $name = $_GET["filterAulaName"];
    $type = $_GET["filterAulaType"];
    
    if($name != "" && $type != "") {
        // Filtrar aulas por nombre y tipo
        $result = $obj_aulas -> filterAulaNameType($name, $type);
        getData($result);
    } else if($name == "" && $type != "") {
        // Filtrar aulas por tipo
        $result = $obj_aulas -> filterAulaType($type);
        getData($result);
    } else if($name != "") {
        // Filtrar aulas por nombre
        $result = $obj_aulas -> filterAulaName($name);
        getData($result);
    } else if($name == "") {
        // Se muestran todas las aulas
        $result = $obj_aulas->consult();
        getData($result);
    }
}

// Mostrar tabla
function getData($result) {
    $tabla= "
    <table class='page__table' id='table-aulas'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>    
                <th>Nombre del aula o computo</th>
                <th>Ubicacion</th>
                <th>Descripcion</th>
                <th>Tipo</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";


if(mysqli_num_rows($result) >= 1) {
    foreach($result as $fila) {
    $aula_or_computo = $fila["tipo"] == 0 ? "Aula" : "Computo";

    $tabla.= "
            <tr class='page__table-row'>
                <td>$fila[aula]</td>
                <td style='min-width: 400px; max-width: 500px; white-space: normal'>$fila[ubicacion]</td>
                <td style='min-width: 400px; max-width: 500px; white-space: normal'>$fila[descripcion]</td>
                <td>$aula_or_computo</td>
                <td class='no-padding accion'>
                    <div>
                        <button class='btn-accion' id='eliminar' onClick='btnDeleteClicked($fila[id_aula])' style='margin-right: .2rem'><p>Eliminar</p></button>
                        <button id='editar' onClick='btnEditClicked($fila[id_aula])'>Editar</button>
                    </div>
                </td>
            </tr>
            ";
    }
} else {
    $tabla.= "
        <tr class='page__table-row'>
            <td class='hidden'>1</td>
            <td class='hidden'>2</td>
            <td class='hidden'>3</td>
            <td class='hidden'>4</td>
            <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ningun aula o computo.</td>
        </tr>
        ";
}

    $tabla.= "
        </tbody>
    </table>
    
    <script>
        $('#table-aulas').DataTable({
            'searching': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos las']],
                'language': {
                    'lengthMenu': 'Mostrar _MENU_ aulas',
                    'info': 'Mostrando _START_ a _END_ de _TOTAL_ aulas',
                    'paginate': {
                        'first':      'Primero',
                        'last':       'Ultimo',
                        'next':       'Siguiente',
                        'previous':   'Anterior'
                    },
                }
        })
    </script>";

    echo $tabla;
}

// Si el boton edit es clickeado
$sendToEdit = "";
if(isset($_GET["aula_id"])) {
    $_SESSION["aula"]["id"] = $_GET["aula_id"];

    if(isset($_SESSION["aula"])){
        $aula = $obj_aulas -> consultAula($_SESSION["aula"]["id"]);
        $aula_data = $aula -> fetch_assoc();

        $_SESSION["aula"]["aula_name"] = $aula_data["aula"];
        $_SESSION["aula"]["aula_ubication"] = $aula_data["ubicacion"];
        $_SESSION["aula"]["aula_description"] = $aula_data["descripcion"];
        $_SESSION["aula"]["aula_type"] = $aula_data["tipo"];
    }
    
    $sendToEdit.= "<script>window.location.href='editar-aula'</script>";
}

$aulaEdit = "";
// Actualizar aula
if(isset($_GET["aula_idEdit"]) && isset($_GET["aula_nameEdit"]) && isset($_GET["aula_ubicationEdit"]) && isset($_GET["aula_descriptionEdit"]) && isset($_GET["aula_typeEdit"])) {
    $aula_id = $_GET["aula_idEdit"];
    $aula_name = $_GET["aula_nameEdit"];
    $aula_ubication = $_GET["aula_ubicationEdit"];
    $aula_description = $_GET["aula_descriptionEdit"];
    $aula_type = $_GET["aula_typeEdit"];

    $aulaEdit.= $obj_aulas -> updateAll($aula_id, $aula_name, $aula_ubication, $aula_description, $aula_type);
} else if(isset($_GET["aula_idEdit"]) && isset($_GET["aula_ubicationEdit"]) && isset($_GET["aula_descriptionEdit"])) {
    $aula_id = $_GET["aula_idEdit"];
    $aula_ubication = $_GET["aula_ubicationEdit"];
    $aula_description = $_GET["aula_descriptionEdit"];

    $aulaEdit.= $obj_aulas -> updateUbicationDescription($aula_id, $aula_ubication, $aula_description);
} else if(isset($_GET["aula_idEdit"]) && isset($_GET["aula_nameEdit"]) && isset($_GET["aula_typeEdit"])) {
    $aula_id = $_GET["aula_idEdit"];
    $aula_name = $_GET["aula_nameEdit"];
    $aula_type = $_GET["aula_typeEdit"];

    $aulaEdit.= $obj_aulas -> updateNameType($aula_id, $aula_name, $aula_type);
}

// Agregando registros atraves de un csv
$csv_aulas="";
if(array_key_exists('csv_aulas', $_FILES)){
   $file_name = $_FILES["csv_aulas"]["name"];
   $file_type = $_FILES["csv_aulas"]["type"];
   $tmp_name = $_FILES["csv_aulas"]["tmp_name"];
   $file_size = $_FILES["csv_aulas"]["size"];
   $folder = "../../recursos/csv-files/". $file_name;
   
   $file["name"] = $file_name;
   $file["type"] = $file_type;
   $file["tmp_name"] = $tmp_name;
   $file["size"] = $file_size;
   $file["folder"] = $folder;

   $csv_aulas.= $obj_aulas -> csvAulas($file);
}
echo $csv_aulas;



echo $addingAula;
echo $yes_or_not;
echo $sendToEdit;
echo $aulaEdit;
?>