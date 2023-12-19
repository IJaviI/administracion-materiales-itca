<?php
session_start();

require_once("../controladores/cls_marcas.php");
$obj_marcas = new cls_marcas;

// Mostrar tabla
if(isset($_GET["tabla"])) {
    $result = $obj_marcas -> consult();
    getData($result);
}

// Insertar marca
$addingMarca = "";
if(isset($_GET["marca_nameAdd"])) {
    $addingMarca.= $obj_marcas -> insert($_GET["marca_nameAdd"]);
}

// Borrar marca
$yes_or_not = "";
if(isset($_GET["marca_idRemove"])) {
    $yes_or_not.= $obj_marcas -> delete($_GET["marca_idRemove"]);
}

// Filtrar marcas por nombre
if(isset($_GET["filterMarcaName"])) {
    $result = $obj_marcas->filterMarcaName($_GET["filterMarcaName"]);
    getData($result);
}

// Mostrar tabla
function getData($result) {
    $tabla= "
    <table class='page__table' id='table-marcas'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>
                <th style='width: 100%'>Nombre de la marca</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";

if(mysqli_num_rows($result) >= 1) {
    foreach($result as $fila) {
    $tabla.= "
            <tr class='page__table-row'>
                <td>$fila[marca]</td>
                <td class='no-padding'>
                    <div>
                        <button id='eliminar' class='accion' onClick='btnDeleteClicked($fila[id_marca])' style='margin-right: .2rem'>Eliminar</button>
                        <button id='editar' class='accion' onClick='btnEditClicked($fila[id_marca])'>Editar</button>
                    </div>
                </td>
            </tr>
            ";
    }
} else {
    $tabla.= "
        <tr class='page__table-row'>
            <th class='hidden'>1</th>
            <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ninguna marca.</td>
        </tr>
        ";
}

    $tabla.= "
            </tbody>
        </table>
        
        <script>
            $('#table-marcas').DataTable({
                'searching': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos las']],
                'language': {
                    'lengthMenu': 'Mostrar _MENU_ marcas',
                    'info': 'Mostrando _START_ a _END_ de _TOTAL_ marcas',
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

// Si el boton edit es clickeado
$sendToEdit = "";
if(isset($_GET["marca_idEdit"])) {
    $_SESSION["marca"]["id"] = $_GET["marca_idEdit"];

    if(isset($_SESSION["marca"])){
        $marca = $obj_marcas -> consultMarca($_SESSION["marca"]["id"]);
        $marca_name = $marca -> fetch_assoc();

        $_SESSION["marca"]["marca_name"] = $marca_name["marca"];
    }
    
    $sendToEdit.= "<script>window.location.href='editar-marca'</script>";
}

$marcaEdit = "";
// Actualizar marca
if(isset($_GET["marca_idUpdate"]) && isset($_GET["marca_nameEdit"])) {
    $id_marca = $_GET["marca_idUpdate"];
    $marca_name = $_GET["marca_nameEdit"];
    $marcaEdit.= $obj_marcas -> update($id_marca, $marca_name);
}

// Agregando registros atraves de un csv
$csv_marcas="";
if(array_key_exists('csv_marcas', $_FILES)){
   $file_name = $_FILES["csv_marcas"]["name"];
   $file_type = $_FILES["csv_marcas"]["type"];
   $tmp_name = $_FILES["csv_marcas"]["tmp_name"];
   $file_size = $_FILES["csv_marcas"]["size"];
   $folder = "../../recursos/csv-files/". $file_name;
   
   $file["name"] = $file_name;
   $file["type"] = $file_type;
   $file["tmp_name"] = $tmp_name;
   $file["size"] = $file_size;
   $file["folder"] = $folder;

   $csv_marcas.= $obj_marcas -> csvMarcas($file);
}
echo $csv_marcas;

echo $addingMarca;
echo $yes_or_not;
echo $sendToEdit;
echo $marcaEdit;
?>