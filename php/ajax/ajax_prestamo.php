<?php
session_start();

require_once("../controladores/cls_equipos.php");
require_once("../controladores/cls_marcas.php");
require_once("../controladores/cls_departamentos.php");
require_once("../controladores/cls_aulas.php");
require_once("../controladores/cls_prestamos.php");
require_once("../controladores/cls_usuarios.php");
$obj_equipos = new cls_equipos;
$obj_prestamos = new cls_prestamos;

// Mostrar tablas
if(isset($_GET["tablaEquipos"])) {
    $depto_user_name = $_SESSION["userProfile"]["depto"];
    $obj_departamentos = new cls_departamentos;

    $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
    $result = $depto -> fetch_assoc();

    $id_depto_usuario = $result["id_depto"];
    $resultEquipos = $obj_equipos -> consultEquipoPorDeptoDeUsuario($id_depto_usuario);
    showEquipos($resultEquipos);
}

// Tabla historial de prestamos
if(isset($_POST["tablaPrestamos"])) {
    $resultPrestamos = $obj_prestamos -> consultPrestamos();
    showHistorialPrestamos($resultPrestamos);
}

// Iniciando prestamo
$createPrestamo = "";
if(isset($_POST["createPrestamo"]) && isset($_POST["id_aula"]) && isset($_POST["fecha_destino"])) {
    $id_usuario = $_SESSION["userProfile"]["id"];
    $id_aula = $_POST["id_aula"];
    $fecha_destino = $_POST["fecha_destino"];   
    
    $createPrestamo.= $obj_prestamos -> addToPrestamo($id_usuario, $id_aula, $fecha_destino);
}
echo $createPrestamo;

// Llenando prestamo
$addinToPrestamo = "";
if(isset($_GET["prestamo"]) && isset($_GET["equipo"]) && isset($_GET["cantidadAPrestar"]) && isset($_GET["cantidad_disponible"]) && isset($_GET["tipo"]) && isset($_GET["inicio"]) && isset($_GET["inicio_input"]) && isset($_GET["fin"]) && isset($_GET["fin_input"])) {
    $id_prestamo = $_GET["prestamo"];
    $id_equipo = $_GET["equipo"];
    $cantidad = $_GET["cantidadAPrestar"];
    $cantidad_disponible = $_GET["cantidad_disponible"];
    $tipo = $_GET["tipo"];
    $inicio = $_GET["inicio"];
    $inicio_input = $_GET["inicio_input"];
    $fin = $_GET["fin"];
    $fin_input = $_GET["fin_input"];

    $addinToPrestamo.= $obj_prestamos -> addToDetPrestamo($id_prestamo,$id_equipo, $cantidad, $tipo, $inicio, $inicio_input, $fin, $fin_input, $_SESSION["userProfile"]["id"]);
}
echo $addinToPrestamo;

// Cancelando prestamo
$cancel_option1 = "";
if(isset($_GET["cancel_option1"])) {
    $cancel_option1.= $obj_prestamos -> cancelPrestamoActual();
}
echo $cancel_option1;

$cancel_option2 = "";
if(isset($_GET["cancel_option2"]) && isset($_GET["id_prestamo"])) {
    $id_prestamo = $_GET["id_prestamo"];
    $cancel_option2.= $obj_prestamos -> cancelPrestamo($id_prestamo);
}
echo $cancel_option2;

// Finalizando prestamo
$finalizar_prestamo = "";
if(isset($_GET["finalizar_prestamo"])) {
    $finalizar_prestamo.= $obj_prestamos -> finalizarPrestamoActual();
}
echo $finalizar_prestamo;

// Filtros a equipos a prestar
if(isset($_GET["filterEquipoName"]) && isset($_GET["filterEquipoMarca"]) && isset($_GET["filterEquipoModelo"])) {
    $name = $_GET["filterEquipoName"];
    $marca = $_GET["filterEquipoMarca"];
    $modelo = $_GET["filterEquipoModelo"];

    if($name != "" && $marca != "" && $modelo != "") {
        // Filtrar equipos por nombre, estado, marca
        $result = $obj_equipos -> filterEquipoBy3($name, $marca, $modelo);
        showEquipos($result);
    } else if($name != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByNameModelo($name, $modelo);
        showEquipos($result);
    } else if($name != "" && $marca != "") {
        $result = $obj_equipos->filterEquipoByNameMarca($name, $marca);
        showEquipos($result);
    } else if($marca != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByMarcaModelo($marca, $modelo);
        showEquipos($result);
    } else if($name != "") {
        $result = $obj_equipos->filterEquipoByName($name);
        showEquipos($result);
    } else if($modelo != "") {
        $result = $obj_equipos->filterEquipoByModelo($modelo);
        showEquipos($result);
    } else if($marca != "") {
        $result = $obj_equipos->filterEquipoByMarca($marca);
        showEquipos($result);
    } else if($name == "" && $modelo == "") {
        // Se muestran todas los equipos del departamento del usuario
        $depto_user_name = $_SESSION["userProfile"]["depto"];
        $obj_departamentos = new cls_departamentos;

        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];
        $result = $obj_equipos->consultEquipoPorDeptoDeUsuario($id_depto_usuario);
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
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Precio</th>
                <th>Marca</th>
                <th>Departamento</th>
                <th>Cantidad a prestar</th>
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
                <input id='tipo-$i' type='hidden' value='$fila[tipo]'>
                <input id='cantidad_disponible-$i' type='hidden' value='$fila[cantidad]'>
                <td>$fila[material]</td>
                <td>$fila[n_serie]</td>
                <td>$fila[descripcion]</td>
                <td>$fila[tipo]</td>
                <td>$fila[cantidad]</td>
                <td>$fila[unidad]</td>
                <td>$$fila[precio]</td>
                <td>$fila[marca]</td>
                <td>$fila[depto]</td>
                <td style='padding: 0'>";

                    if($fila["cantidad"] == 0){
                        $tabla.="<input type='number' disabled min='0' value='0' class='page__table-row-input' style='padding-bottom: 1rem;'>";
                    } else {
                        $tabla.="<input id='cantidadAPrestar-$i' type='number' min='0' value='0' class='page__table-row-input' style='padding-bottom: 1rem;'>";
                    }

                $tabla.="
                </td>
                
                <td style='padding: 0'>
                    <input type='time'"; if($fila["tipo"] == "No retornable") { $tabla.=" disabled value='00:00' "; } $tabla.="class='page__table-row-input' id='time-inicio-$i' style='padding-bottom: 1rem;'>
                </td>
                <td style='padding: 0'>
                    <input type='time'"; if($fila["tipo"] == "No retornable") { $tabla.=" disabled value='00:00' "; } $tabla.="class='page__table-row-input' id='time-fin-$i' style='padding-bottom: 1rem;'>
                </td>
                
                <td class='no-padding accion'>";

                    if($fila["cantidad"] == 0){
                        $tabla.= "
                        <button type='button' class='btn-accion' style='background-color: #828282; color: var(--color-light); cursor: default'><p>Agregar</p></button>
                        ";
                    } else {
                        $tabla.="<button class='btn-accion' id='agregar_a_prestamo-$i' style='background-color: var(--color-save); padding-bottom: 1.2rem;'><p>Agregar</p></button>";
                    }

                $tabla.="</td>
            </tr>


            <script>
            $('#cantidadAPrestar-$i').each(function() {
                inputChanges(this);
            });

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
                if(isset($_SESSION["nuestroPrestamo"])) { $tabla.="obj.prestamo = $_SESSION[nuestroPrestamo];"; } $tabla.="
                obj.equipo = $('#idEquipo-$i').val();
                obj.cantidadAPrestar = $('#cantidadAPrestar-$i').val();
                obj.cantidad_disponible = $('#cantidad_disponible-$i').val();
                obj.tipo = $('#tipo-$i').val();
                obj.inicio = $('#time-inicio-$i').val();
                obj.inicio_input = 'time-inicio-$i';
                obj.fin = $('#time-fin-$i').val();
                obj.fin_input = 'time-fin-$i';

                $.ajax({
                    url: 'php/ajax/ajax_prestamo.php?'+ $.param(obj),
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
            <td class='hidden'>9</td>
            <td class='hidden'>10</td>
            <td class='hidden'>11</td>
            <td class='hidden'>12</td>
            <td colspan='13' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se han encontrado resultados.</td>
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
                'lengthMenu': 'Mostrar _MENU_ materiales',
                'info': 'Mostrando _START_ a _END_ de _TOTAL_ materiales',
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

function showHistorialPrestamos($prestamos) {
    $tabla= "
    <table class='page__table' id='table-historial-de-prestamos'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>    
                <th style='min-width: 150px'>ID</th>
                <th style='min-width: 150px'>Aula o computo usado</th>
                <th style='min-width: 300px'>Prestamo realizado en</th>
                <th style='min-width: 300px'>Prestamo agendado para</th>
                <th style='min-width: 150px'>Estado</th>
                <th style='text-align: center'>Accion</th>
                <th style='text-align: center'>Detalles</th>
                <th class='hidden'>8</th>
                <th class='hidden'>9</th>
            </tr>
        </thead>
        
        <tbody>";


if(mysqli_num_rows($prestamos) >= 1) {
    $i = 0;
    foreach($prestamos as $fila) {
        $i++;
        
        $fecha_hecha = strtotime($fila["fecha_hecha"]);
        $new_fecha_hecha = date('d-m-Y', $fecha_hecha);

        $fecha_destino = strtotime($fila["fecha_destino"]);
        $new_fecha_destino = date('d-m-Y', $fecha_destino);

        $tabla.= "
            <tr class='page__table-row'>
                <td>$fila[id_prestamo]</td>
                <td>$fila[aula]</td>
                <td>$new_fecha_hecha</td>
                <td>$new_fecha_destino</td>
                <td>$fila[estado]</td>
                <td class='no-padding accion'>";
    
                if($fila["estado"] == "Cancelado" || $fila["estado"] == "Devuelto"){
                    $tabla.= "
                    <button type='button' class='btn-accion' style='background-color: #828282; color: var(--color-light); cursor: default'><p>Cancelar</p></button>
                    ";
                } else {
                    $tabla.= "
                    <button type='button' class='btn-accion' id='cancel-prestamo-option2-$i' style='background-color: var(--color-wrong); color: var(--color-light)'><p>Cancelar</p></button>
                    ";
                }
                
        $tabla.="
                </td>
                <td class='no-padding accion'>
                    <button type='button' class='btn-accion' id='ver_detalles-prestamo-$i' style='background-color: var(--color-edit);' ><p>Ver mas</p></button>
                    <button type='button' class='btn-accion' id='cerrar_detalles-prestamo-$i' style='background-color: var(--color-edit);'><p>Ver menos</p></button>
                </td>
                <th class='hidden'>8</th>
                <th class='hidden'>9</th>
            </tr>";

        $tabla.="
        <tr id='row-number-$i' class='page__table-row' style='border: 2px var(--border)'>
            <td colspan='9' class='no-padding' style='border: 0'>
                <table class='page__table' style='display: table;'>
                    <thead class='page__table-head' style='border: 0'>
                        <tr class='page__table-row' style='border-bottom: 2px var(--border);'>
                            <th style='border: 0; text-align: center' colspan='9'>Materiales agregados a este prestamo</th>
                        </tr>
                    </thead>
                    <thead class='page__table-head' style='border: 0'>";

        $obj_prestamos = new cls_prestamos;
        $det_prestamo = $obj_prestamos -> consult_detPrestamo($fila["id_prestamo"]);
        
        if(mysqli_num_rows($det_prestamo) >= 1){
            $tabla.="
                <tr class='page__table-row' style='background-color: var(--color-bg-2)'>
                    <th>Material</th>
                    <th>Numero de serie</th>
                    <th>Descripcion</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Precio</th>
                    <th>Marca</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                    <th>Hora inicio</th>
                    <th>Hora fin</th>
                </tr>
            </thead>

            <tbody>";
            
            foreach($det_prestamo as $detalle_del_prestamo) {
                $tabla.="
                <tr class='page__table-row'>
                    <td style='border-left: 0;'>$detalle_del_prestamo[material]</td>
                    <td>$detalle_del_prestamo[n_serie]</td>
                    <td>$detalle_del_prestamo[descripcion]</td>
                    <td>$detalle_del_prestamo[tipo]</td>
                    <td>$detalle_del_prestamo[cantidad]</td>
                    <td>$detalle_del_prestamo[unidad]</td>
                    <td>$detalle_del_prestamo[precio]</td>
                    <td>$detalle_del_prestamo[marca]</td>
                    <td>$detalle_del_prestamo[depto]</td>
                    <td>$detalle_del_prestamo[estado]</td>
                    <td>$detalle_del_prestamo[inicio]</td>
                    <td style='border-right: 0;'>$detalle_del_prestamo[fin]</td>
                </tr>";
            }
        } else {
            $tabla.= "
            </thead>

            <tbody>
                <tr class='page__table-row'>
                    <td colspan='9' style='border-left: 0; border-right: 0; text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No hay equipos añadidos a este prestamo.</td>
                </tr>
            ";
        }

        $tabla.="
                        <tr class='page__table-row' style='background-color: var(--color-user-bg)'>
                            <td colspan='9' style='border: 0'></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <th class='hidden'>2</th>
            <th class='hidden'>3</th>
            <th class='hidden'>4</th>
            <th class='hidden'>5</th>
            <th class='hidden'>6</th>
            <th class='hidden'>7</th>
            <th class='hidden'>8</th>
            <th class='hidden'>9</th>
        </tr>";

        $tabla.="
        <script>
            $('#row-number-$i').hide();
            $('#cerrar_detalles-prestamo-$i').hide();

            $('#ver_detalles-prestamo-$i').mousedown(function(){
                $('#row-number-$i').show();
                $('#ver_detalles-prestamo-$i').hide();
                $('#cerrar_detalles-prestamo-$i').show();
            });

            $('#cerrar_detalles-prestamo-$i').mousedown(function(){
                $('#row-number-$i').hide();
                $('#cerrar_detalles-prestamo-$i').hide();
                $('#ver_detalles-prestamo-$i').show();
            });

            $('#cancel-prestamo-option2-$i').mousedown(function () {
                hideAlert();
                setTimeout(function () {
                    $('#resp').html(
                        `<div id='alertCancelPrestamo' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closeAlertCancelPrestamo()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de cancelar este prestamo?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='cancelPrestamo($fila[id_prestamo])' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, cancelar</button>
                            <button type='button' onmousedown='closeAlertCancelPrestamo()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                    )
                }, 1);
            });
            
            function closeAlertCancelPrestamo() {
                $('#alertCancelPrestamo').addClass('hidden');
            }
        </script>
        ";
    }
} else {
    $tabla.= "
            <tr class='page__table-row'>
                <th class='hidden'>1</th>
                <th class='hidden'>2</th>
                <th class='hidden'>3</th>
                <th class='hidden'>4</th>
                <th class='hidden'>5</th>
                <th class='hidden'>6</th>
                <th class='hidden'>7</th>
                <th class='hidden'>8</th>
                <td colspan='7' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>Aun no se ha realizado un prestamo, tus prestamos se mostraran aquí al realizar al menos uno.</td>
            </tr>";
}

    $tabla.= "
        </tbody>
    </table>
    
    <script>
        $('#table-historial-de-prestamos').DataTable({
            'searching': false,
            'ordering': false,
            'pageLength': 10,
            'lengthMenu': [[10, 20, 50, 100, -1], [5, 10, 25, 50, 'Todos los']],
            drawCallback: function () {
                var api = this.api();
                var info = api.page.info();
                $('#table-historial-de-prestamos_info').html('Mostrando ' + (info.start/2 + 1) + ' a ' + (info.end / 2 == 0.5 ? 1 : info.end / 2) + ' de ' + (info.recordsTotal/2 == 0.5 ? 1 : info.recordsTotal/2) + ' prestamos')
            },
            'language': {
                'lengthMenu': 'Mostrar _MENU_ prestamos',
                'info': 'Showing _START_ to _END_ of _TOTAL_ entries',
                'paginate': {
                    'first':      'Primero',
                    'last':       'Ultimo',
                    'next':       'Siguiente',
                    'previous':   'Anterior'
                } 
            }
        });
    </script>
    ";

    echo $tabla;
}


?>