<?php
session_start();

require_once("../../controladores/cls_equipos.php");
require_once("../../controladores/cls_marcas.php");
require_once("../../controladores/cls_departamentos.php");
require_once("../../controladores/cls_aulas.php");
require_once("../../controladores/cls_prestamos.php");
require_once("../../controladores/cls_usuarios.php");
$obj_equipos = new cls_equipos;
$obj_prestamos = new cls_prestamos;

// Mostrar tablas
if(isset($_GET["tablaEquipos"])) {
    $resultEquipos = $obj_equipos -> consultAll();
    showEquipos($resultEquipos);
}

// Iniciando prestamo a asignar 
$asignPrestamo = "";
if(isset($_POST["asignPrestamo"]) && isset($_POST["id_aula"]) && isset($_POST["id_usuario"]) && isset($_POST["fecha_destino"])) {
    $id_docente = $_POST["id_usuario"];
    $id_aula = $_POST["id_aula"];
    $fecha_destino = $_POST["fecha_destino"];   
    
    $asignPrestamo.= $obj_prestamos -> addToPrestamoWhenAsigning($id_docente, $id_aula, $fecha_destino);
}
echo $asignPrestamo;

// Llenando prestamo a asignar
$addinToPrestamoAsignado = "";
if(isset($_GET["prestamo"]) && isset($_GET["equipo"]) && isset($_GET["inicio"]) && isset($_GET["inicio_input"]) && isset($_GET["fin"]) && isset($_GET["fin_input"])) {
    $id_prestamo = $_GET["prestamo"];
    $id_equipo = $_GET["equipo"];
    $inicio = $_GET["inicio"];
    $inicio_input = $_GET["inicio_input"];
    $fin = $_GET["fin"];
    $fin_input = $_GET["fin_input"];

    $addinToPrestamoAsignado.= $obj_prestamos -> addToDetPrestamoWhenAsigning($id_prestamo, $id_equipo, $inicio, $inicio_input, $fin, $fin_input);
}
echo $addinToPrestamoAsignado;

// Cancelando prestamo a asignar
$cancel_option1 = "";
if(isset($_GET["cancel_option1"])) {
    $cancel_option1.= $obj_prestamos -> cancelPrestamoAsignado();
}
echo $cancel_option1;

// Finalizando prestamo a asignar
$finalizar_prestamo = "";
if(isset($_GET["finalizar_prestamo"])) {
    $finalizar_prestamo.= $obj_prestamos -> finalizarPrestamoAsignado();
}
echo $finalizar_prestamo;

// Filtros a equipos a prestar
if(isset($_GET["filterEquipoName"]) && isset($_GET["filterEquipoMarca"]) && isset($_GET["filterEquipoModelo"])) {
    $name = $_GET["filterEquipoName"];
    $marca = $_GET["filterEquipoMarca"];
    $modelo = $_GET["filterEquipoModelo"];

    if($name != "" && $marca != "" && $modelo != "") {
        // Filtrar equipos por nombre, estado, marca
        $result = $obj_equipos -> filterEquipoBy3Asignar($name, $marca, $modelo);
        showEquipos($result);
    } else if($name != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByNameModeloAsignar($name, $modelo);
        showEquipos($result);
    } else if($name != "" && $marca != "") {
        $result = $obj_equipos->filterEquipoByNameMarcaAsignar($name, $marca);
        showEquipos($result);
    } else if($marca != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByMarcaModeloAsignar($marca, $modelo);
        showEquipos($result);
    } else if($name != "") {
        $result = $obj_equipos->filterEquipoByNameAsignar($name);
        showEquipos($result);
    } else if($modelo != "") {
        $result = $obj_equipos->filterEquipoByModeloAsignar($modelo);
        showEquipos($result);
    } else if($marca != "") {
        $result = $obj_equipos->filterEquipoByMarcaAsignar($marca);
        showEquipos($result);
    } else if($name == "" && $modelo == "") {
        $result = $obj_equipos->consult();
        showEquipos($result);
    }
}

// Mostrar tabla equipos
function showEquipos($equipos) {
    $tabla= "
    <table class='page__table' id='table-equipos-a-agregar'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>
                <th>Material</th>
                <th>Numero de serie</th>
                <th>Descripcion</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Departamento</th>
                <th>Inicia</th>
                <th>Finaliza</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";


if(mysqli_num_rows($equipos) >= 1) {
    $i = 0;
    foreach($equipos as $fila) {
    $i++;
    $tabla.= "
            <tr class='page__table-row'>
                <input id='idEquipo-$i' type='hidden' value='$fila[id_material]'>
                <td>$fila[material]</td>
                <td style='min-width: 70px; max-width: 70px; overflow-x: scroll'>$fila[n_serie]</td>
                <td style='min-width: 300px; max-width: 300px; overflow-x: scroll'>$fila[descripcion]</td>
                <td>$fila[tipo]</td>
                <td>$fila[marca]</td>
                <td>$fila[depto]</td>
                <td style='padding: 0'>
                    <input type='time' class='page__table-row-input' id='time-inicio-$i' style='padding-bottom: 1rem;'>
                </td>
                <td style='padding: 0'>
                    <input type='time' class='page__table-row-input' id='time-fin-$i' style='padding-bottom: 1rem;'>
                </td>
                <td class='no-padding accion'>
                    <button class='btn-accion' id='agregar_a_prestamo-$i' style='background-color: var(--color-save); padding-bottom: 1.2rem;'><p>Agregar</p></button>
                </td>
            </tr>


            <script>
            $('#time-inicio-$i').each(function() {
                inputChanges(this);
            });

            $('#time-fin-$i').each(function() {
                inputChanges(this);
            });
            
            function inputChanges(input) {
                $(input).change(function() {
                    hideAlert();
                });
        
                $(input).on('focusout', function(event){
                // changing the color of the border
                    $(input).css('border', '3px solid #fff');
                });
        
                $(input).on('focus', function(event){
                // changing the color of the border
                    $(input).css('border', '3px solid var(--color-purple)');
                });
            }

            $('#agregar_a_prestamo-$i').mousedown(function(){
                obj = new Object();"; 
                if(isset($_SESSION["nuestroPrestamoAsignado"])) { $tabla.="obj.prestamo = $_SESSION[nuestroPrestamoAsignado];"; } $tabla.="
                obj.equipo = $('#idEquipo-$i').val();
                obj.inicio = $('#time-inicio-$i').val();
                obj.inicio_input = 'time-inicio-$i';
                obj.fin = $('#time-fin-$i').val();
                obj.fin_input = 'time-fin-$i';

                $.ajax({
                    url: 'php/ajax/asignar-prestamo/ajax_asignar-prestamo.php?'+ $.param(obj),
                    success: function(response){
                        $('#resp').html(response);
                    }
                });
            });
            </script>
            ";
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
            <td colspan='9' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se han encontrado resultados.</td>
        </tr>
        ";
}

    $tabla.= "
        </tbody>
    </table>

    <script>
        $('#table-equipos-a-agregar').DataTable({
            'searching': false,
            'pageLength': 5,
            'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos los']],
            'language': {
                'lengthMenu': 'Mostrar _MENU_ equipos',
                'info': 'Mostrando _START_ a _END_ de _TOTAL_ equipos',
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
?>