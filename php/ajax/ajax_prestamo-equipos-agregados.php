<?php
session_start();

require_once("../controladores/cls_equipos.php");
require_once("../controladores/cls_marcas.php");
require_once("../controladores/cls_departamentos.php");
require_once("../controladores/cls_prestamos.php");
$obj_equipos = new cls_equipos;
$obj_prestamos = new cls_prestamos;

// Mostrar tabla equipos agregados al prestamo
if(isset($_GET["tablaEquiposAgregados"])) {
    $prestamo = $obj_prestamos -> consultPrestamo();
    $result = $prestamo -> fetch_assoc();

    $det_prestamo = $obj_prestamos -> consult_detPrestamo($result["id_prestamo"]);
    showEquiposAgregados($det_prestamo);
}

$equipo_removed = "";
// Actualizando equipo agregado al prestamo a 'Removido'
if(isset($_GET["quitar_det_prestamo_Retornable"]) && isset($_GET["equipo"]) && isset($_GET["inicio"]) && isset($_GET["fin"])) {
    $id_det_prestamo  = $_GET["quitar_det_prestamo_Retornable"];
    $equipo = $_GET["equipo"];
    $inicio = $_GET["inicio"];
    $fin = $_GET["fin"];

    $equipo_removed.= $obj_prestamos -> remove_equipoAgregado($id_det_prestamo, $equipo, $inicio, $fin);
}
echo $equipo_removed;

$equipo_removedNoRetornable = "";
// Actualizando equipo agregado al prestamo a 'Removido'
if(isset($_GET["quitar_det_prestamo"]) && isset($_GET["equipo"])) {
    $id_det_prestamo  = $_GET["quitar_det_prestamo"];
    $equipo = $_GET["equipo"];

    $equipo_removedNoRetornable.= $obj_prestamos -> remove_equipoAgregadoNoRetornable($id_det_prestamo, $equipo);
}
echo $equipo_removedNoRetornable;

// Actualizando horario de prestamo de equipo agregado
$actualizandoEquipoAgregado = "";
if(isset($_GET["prestamo"]) && isset($_GET["det_prestamo"]) && isset($_GET["equipo"]) && isset($_GET["inicio"]) && isset($_GET["inicio_input"]) && isset($_GET["fin"]) && isset($_GET["fin_input"])) {
    $id_prestamo = $_GET["prestamo"];
    $id_det_prestamo = $_GET["det_prestamo"];
    $id_equipo = $_GET["equipo"];
    $inicio = $_GET["inicio"];
    $inicio_input = $_GET["inicio_input"];
    $fin = $_GET["fin"];
    $fin_input = $_GET["fin_input"];

    $actualizandoEquipoAgregado.= $obj_prestamos -> actualizarDetPrestamo($id_prestamo, $id_det_prestamo, $id_equipo, $inicio, $inicio_input, $fin, $fin_input);
}
echo $actualizandoEquipoAgregado;


function showEquiposAgregados($agregados) {
    $tabla= "
    <table class='page__table' id='table-equipos-agregados-a-prestamo'>
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
                <th>Estado</th>
                <th>Inicia</th>
                <th>Finaliza</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>
        <tbody class='page__table-body'>
    ";

    if(mysqli_num_rows($agregados) >= 1){
        $i = 0;
        foreach($agregados as $agregado) {
            $i++;
            $tabla.="
            <tr class='page__table-row'>
                <td>$agregado[material]</td>
                <td>$agregado[n_serie]</td>
                <td>$agregado[descripcion]</td>
                <td>$agregado[tipo]</td>
                <td>$agregado[cantidad]</td>
                <td>$agregado[unidad]</td>
                <td>$$agregado[precio]</td>
                <td>$agregado[marca]</td>
                <td>$agregado[depto]</td>
                <td>$agregado[estado]</td>
                <td style='padding: 0'>
                    <input "; if($agregado["tipo"] == "No retornable") { $tabla.=" disabled "; } if($agregado["estado"] == 'Removido') {$tabla.="disabled";} $tabla.=" type='time' class='page__table-row-input' id='time-inicio-agregado-$i' value='$agregado[inicio]' style='padding-top: 0.9rem; padding-bottom: 0.9rem'>
                </td>
                <td style='padding: 0'>
                    <input "; if($agregado["tipo"] == "No retornable") { $tabla.=" disabled "; } if($agregado["estado"] == 'Removido') {$tabla.="disabled";} $tabla.=" type='time' class='page__table-row-input' id='time-fin-agregado-$i' value='$agregado[fin]' style='padding-top: 0.9rem; padding-bottom: 0.9rem'>
                </td>
                <td class='no-padding accion'>
                    <div>
                        <button type='button' class='btn-accion "; if($agregado["estado"] == 'Removido') {$tabla.="hidden";} $tabla.="' id='quitar-agregado-$i' style='background-color: var(--color-wrong); color: var(--color-light); cursor: pointer; padding-top: 1.4rem; paddin-bottom: 1.4rem'><p>Quitar</p></button>

                        <button type='button' class='btn-accion "; if($agregado["estado"] == 'Removido') {$tabla.="hidden";} $tabla.="' id='actualizar-agregado-$i' style='background-color: var(--color-save); padding-top: 1.4rem; paddin-bottom: 1.4rem;"; if($agregado["tipo"] == "No retornable") { $tabla.=" display: none "; } $tabla.="'><p>Actualizar horario</p></button>

                        <button type='button' class='btn-accion "; if($agregado["estado"] != 'Removido') {$tabla.="hidden";} $tabla.="'' style='background-color: #828282; color: var(--color-light); cursor: default; padding-top: 1.4rem; paddin-bottom: 1.4rem'><p>Removido</p></button>
                    </div>
                </td>
            </tr>
            
            <script>
                ";

                if($agregado["tipo"] == "Retornable") {
                   $tabla.="
                   $('#quitar-agregado-$i').mousedown(function(){
                        hideAlert();
                        setTimeout( function() { $('#resp').html(
                            `<div id='alertDeleteAgregado$i' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                                <button type='button' onmousedown='closeAlertDeleteAgregado$i()' class='alert__btn-close'>&times;</button>
                                <p class='alert__message'>¿Estas seguro de quitar de tu prestamo el material ($agregado[material]) agendado en el horario ($agregado[inicio] - $agregado[fin])?</p>
                                <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                                    <button type='button' onmousedown='quitarAgregadoConfirmado$i()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, quitar</button>
                                    <button type='button' onmousedown='closeAlertDeleteAgregado$i()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                                </div>
                            </div>`
                        ) }, 1);    
                    });
    
                    function closeAlertDeleteAgregado$i() {
                        $('#alertDeleteAgregado$i').addClass('hidden');
                    }
    
                    function quitarAgregadoConfirmado$i() {
                        obj = new Object();
                        obj.quitar_det_prestamo_Retornable = $agregado[id_det_prestamo];
                        obj.equipo = '$agregado[material]';
                        obj.inicio = '$agregado[inicio]';
                        obj.fin = '$agregado[fin]';
    
                        $.ajax({url: 'php/ajax/ajax_prestamo-equipos-agregados.php?'+ $.param(obj), success: function(response){
                            $('#resp').html(response);
                        }});
                    }";
                } else {
                    $tabla.="
                    $('#quitar-agregado-$i').mousedown(function(){
                        hideAlert();
                        setTimeout( function() { $('#resp').html(
                            `<div id='alertDeleteAgregado$i' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                                <button type='button' onmousedown='closeAlertDeleteAgregado$i()' class='alert__btn-close'>&times;</button>
                                <p class='alert__message'>¿Estas seguro de quitar de tu prestamo el material ($agregado[material]) ?</p>
                                <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                                    <button type='button' onmousedown='quitarAgregadoConfirmado$i()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, quitar</button>
                                    <button type='button' onmousedown='closeAlertDeleteAgregado$i()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                                </div>
                            </div>`
                        ) }, 1);    
                    });

                    function closeAlertDeleteAgregado$i() {
                        $('#alertDeleteAgregado$i').addClass('hidden');
                    }
    
                    function quitarAgregadoConfirmado$i() {
                        obj = new Object();
                        obj.quitar_det_prestamo = $agregado[id_det_prestamo];
                        obj.equipo = '$agregado[material]';
    
                        $.ajax({url: 'php/ajax/ajax_prestamo-equipos-agregados.php?'+ $.param(obj), success: function(response){
                            $('#resp').html(response);
                        }});
                    }";
                }

                $tabla.="$('#time-inicio-agregado-$i').data('inicialValue', $('#time-inicio-agregado-$i').val());
                $('#time-fin-agregado-$i').data('inicialValue', $('#time-fin-agregado-$i').val());

                $('#actualizar-agregado-$i').mousedown(function(){
                    obj = new Object();
                    obj.prestamo = $_SESSION[nuestroPrestamo];
                    obj.det_prestamo = $agregado[id_det_prestamo];
                    obj.equipo = $agregado[id_material];
                    obj.inicio = $('#time-inicio-agregado-$i').val();
                    obj.inicio_input = 'time-inicio-agregado-$i';
                    obj.fin = $('#time-fin-agregado-$i').val();
                    obj.fin_input = 'time-fin-agregado-$i';

                    if($('#time-inicio-agregado-$i').data('inicialValue') != $('#time-inicio-agregado-$i').val() || $('#time-fin-agregado-$i').data('inicialValue') != $('#time-fin-agregado-$i').val()){
                        $.ajax({url: 'php/ajax/ajax_prestamo-equipos-agregados.php?'+ $.param(obj), success: function(response){
                            $('#resp').html(response);
                        }});
                    } else {
                        hideAlert();
                        alertRed();
                        setTimeout(function () { alerts('¿Huh?, no se ha detectado un cambio en el horario de inicio o fin del prestamo del equipo a actualizar.'); }, 1);

                        $('#time-inicio-agregado-$i').css('borderColor', 'var(--color-wrong)');
                        $('#time-fin-agregado-$i').css('borderColor', 'var(--color-wrong)');
                    }
    
                });

                $('#time-inicio-agregado-$i').change(function() {
                    $('#alert').addClass('hidden');
                    $('#alertPrestamo').addClass('hidden');
                });

                $('#time-inicio-agregado-$i').focusout(function() {
                    $(this).css('border', '3px solid var(--color-light)');
                    $('#time-fin-agregado-$i').css('border', '3px solid var(--color-light)');
                });

                $('#time-inicio-agregado-$i').focus(function() {
                    $(this).css('border', '3px solid var(--color-purple)');
                });

                $('#time-fin-agregado-$i').change(function() {
                    $('#alert').addClass('hidden');
                    $('#alertPrestamo').addClass('hidden');
                });

                $('#time-fin-agregado-$i').focusout(function() {
                    $(this).css('border', '3px solid var(--color-light)');
                    $('#time-inicio-agregado-$i').css('border', '3px solid var(--color-light)');
                });

                $('#time-fin-agregado-$i').focus(function() {
                    $(this).css('border', '3px solid var(--color-purple)');
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
                <td colspan='13' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No hay materiales agregados al prestamo aun.</td>
            </tr>
        ";
    }
        
    $tabla.= "        
        </tbody>
    </table>
    
    <script>
        $('#table-equipos-agregados-a-prestamo').DataTable({
            'searching': false,
            'info': false,
            'pageLength': 5,
            'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos los']],
            'language': {
                'lengthMenu': 'Mostrar _MENU_ materiales agregados',
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
?>