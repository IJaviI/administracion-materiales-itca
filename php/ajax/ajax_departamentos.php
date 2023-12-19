<?php
session_start();

require_once("../controladores/cls_departamentos.php");
$obj_departamentos = new cls_departamentos;

// Mostrar tabla
if(isset($_GET["tabla"])) {
    $result = $obj_departamentos -> consult();
    getData($result);
}

// Insertar departamento
$addingDepto = "";
if(isset($_GET["depto_nameAdd"])) {
    $addingDepto.= $obj_departamentos -> insert($_GET["depto_nameAdd"]);
}

// Borrar departamentos
$yes_or_not = "";
if(isset($_GET["depto_idRemove"])) {
    $yes_or_not.= $obj_departamentos -> delete($_GET["depto_idRemove"]);
}

// Filtrar departamentos por nombre
if(isset($_GET["filterDeptoName"])) {
    $result = $obj_departamentos->filterDeptoName($_GET["filterDeptoName"]);
    getData($result);
}

function getData($result) {
    $tabla= "
    <table class='page__table' id='table-deptos'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>    
                <th>Nombre del departamento</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";

if(mysqli_num_rows($result) >= 1) {
    while($fila = $result->fetch_assoc()) {
    $tabla.= "
            <tr class='page__table-row'>
                <td>$fila[depto]</td>
                <td class='no-padding'>
                    <div>
                        <button id='eliminar' onClick='btnDeleteClicked($fila[id_depto])' style='margin-right: .2rem'>Eliminar</button>
                        <button id='editar' onClick='btnEditClicked($fila[id_depto])'>Editar</button>
                    </div>
                </td>
            </tr>
            ";
    }
} else {
    $tabla.= "
        <tr class='page__table-row'>
            <td class='hidden'>1</td>
            <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ningun departamento.</td>
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
                    'lengthMenu': 'Mostrar _MENU_ departamentos',
                    'info': 'Mostrando _START_ a _END_ de _TOTAL_ departamentos',
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
if(isset($_GET["depto_idEdit"])) {
    $_SESSION["depto"]["id"] = $_GET["depto_idEdit"];

    if(isset($_SESSION["depto"])){
        $depto = $obj_departamentos -> consultDepto($_SESSION["depto"]["id"]);
        $depto_name = $depto -> fetch_assoc();

        $_SESSION["depto"]["depto_name"] = $depto_name["depto"];
    }
    
    $sendToEdit.= "<script>window.location.href='editar-departamento'</script>";
}

$deptoEdit = "";
// Actualizar departamento
if(isset($_GET["depto_idUpdate"]) && isset($_GET["depto_nameEdit"])) {
    $id_depto = $_GET["depto_idUpdate"];
    $depto_name = $_GET["depto_nameEdit"];
    $deptoEdit.= $obj_departamentos -> update($id_depto, $depto_name);
}

// Agregando registros atraves de un csv
$csv_deptos="";
if(array_key_exists('csv_deptos', $_FILES)){
   $file_name = $_FILES["csv_deptos"]["name"];
   $file_type = $_FILES["csv_deptos"]["type"];
   $tmp_name = $_FILES["csv_deptos"]["tmp_name"];
   $file_size = $_FILES["csv_deptos"]["size"];
   $folder = "../../recursos/csv-files/". $file_name;
   
   $file["name"] = $file_name;
   $file["type"] = $file_type;
   $file["tmp_name"] = $tmp_name;
   $file["size"] = $file_size;
   $file["folder"] = $folder;

   $csv_deptos.= $obj_departamentos -> csvDeptos($file);
}
echo $csv_deptos;


echo $addingDepto;
echo $yes_or_not;
echo $sendToEdit;
echo $deptoEdit;
?>