<?php
session_start();

require_once("../controladores/cls_unidades.php");
$obj_unidades = new cls_unidades;

// Mostrar tabla
if(isset($_GET["tabla"])) {
    $result = $obj_unidades -> consult();
    getData($result);
}

// Insertar unidad
$addingUnidad = "";
if(isset($_GET["unidad_nameAdd"])) {
    $addingUnidad.= $obj_unidades -> insert($_GET["unidad_nameAdd"]);
}

// Borrar unidades
$yes_or_not = "";
if(isset($_GET["unidad_idRemove"])) {
    $yes_or_not.= $obj_unidades -> delete($_GET["unidad_idRemove"]);
}

// Filtrar unidades por nombre
if(isset($_GET["filterUnidadName"])) {
    $result = $obj_unidades->filterUnidadName($_GET["filterUnidadName"]);
    getData($result);
}

function getData($result) {
    $tabla= "
    <table class='page__table' id='table-unidades'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>    
                <th>Nombre de la unidad</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";

if(mysqli_num_rows($result) >= 1) {
    while($fila = $result->fetch_assoc()) {
    $tabla.= "
            <tr class='page__table-row'>
                <td>$fila[unidad]</td>
                <td class='no-padding'>
                    <div>
                        <button id='eliminar' onClick='btnDeleteClicked($fila[id_unidad])' style='margin-right: .2rem'>Eliminar</button>
                        <button id='editar' onClick='btnEditClicked($fila[id_unidad])'>Editar</button>
                    </div>
                </td>
            </tr>
            ";
    }
} else {
    $tabla.= "
        <tr class='page__table-row'>
            <td class='hidden'>1</td>
            <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ninguna unidad.</td>
        </tr>
        ";
}

    $tabla.= "
            </tbody>
        </table>


        <script>
            $('#table-deptos').DataTable({
                'searching': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos los']],
                'language': {
                    'lengthMenu': 'Mostrar _MENU_ unidades',
                    'info': 'Mostrando _START_ a _END_ de _TOTAL_ unidades',
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

// Si el boton edit es clickeado mandar a la pagina edit
$sendToEdit = "";
if(isset($_GET["unidad_idEdit"])) {
    $_SESSION["unidad"]["id"] = $_GET["unidad_idEdit"];

    if(isset($_SESSION["unidad"])){
        $unidad = $obj_unidades -> consultUnidad($_SESSION["unidad"]["id"]);
        $unidad_name = $unidad -> fetch_assoc();

        $_SESSION["unidad"]["unidad_name"] = $unidad_name["unidad"];
    }
    
    $sendToEdit.= "<script>window.location.href='editar-unidad'</script>";
}

$unidadEdit = "";
// Actualizar unidad
if(isset($_GET["unidad_idUpdate"]) && isset($_GET["unidad_nameEdit"])) {
    $id_unidad = $_GET["unidad_idUpdate"];
    $unidad_name = $_GET["unidad_nameEdit"];
    $unidadEdit.= $obj_unidades -> update($id_unidad, $unidad_name);
}

// Agregando registros atraves de un csv
$csv_unidades="";
if(array_key_exists('csv_unidades', $_FILES)){
   $file_name = $_FILES["csv_unidades"]["name"];
   $file_type = $_FILES["csv_unidades"]["type"];
   $tmp_name = $_FILES["csv_unidades"]["tmp_name"];
   $file_size = $_FILES["csv_unidades"]["size"];
   $folder = "../../recursos/csv-files/". $file_name;
   
   $file["name"] = $file_name;
   $file["type"] = $file_type;
   $file["tmp_name"] = $tmp_name;
   $file["size"] = $file_size;
   $file["folder"] = $folder;

   $csv_unidades.= $obj_unidades -> csvUnidades($file);
}
echo $csv_unidades;


echo $addingUnidad;
echo $yes_or_not;
echo $sendToEdit;
echo $unidadEdit;
?>