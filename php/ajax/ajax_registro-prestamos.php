<?php
require_once("../controladores/cls_equipos.php");
require_once("../controladores/cls_marcas.php");
require_once("../controladores/cls_departamentos.php");
require_once("../controladores/cls_aulas.php");
require_once("../controladores/cls_prestamos.php");
require_once("../controladores/cls_usuarios.php");
$obj_equipos = new cls_equipos;
$obj_prestamos = new cls_prestamos;

if(isset($_POST["tablaRegistroPrestamos"])) {
    $resultPrestamos = $obj_prestamos -> consultRegistroPrestamos();
    showRegistroPrestamos($resultPrestamos);
}

// Marcar prestamo como devuelto (admin)(registro prestamos)
$marcarDevuelto = "";
if(isset($_GET["marcarDevuelto"]) && isset($_GET["id_prestamo"])) {
    $id_prestamo = $_GET["id_prestamo"];

    $marcarDevuelto.= $obj_prestamos -> marcarPrestamoDevuelto($id_prestamo);
}
echo $marcarDevuelto;

$cancel = "";
if(isset($_GET["cancel"]) && isset($_GET["id_prestamo"])) {
    $id_prestamo = $_GET["id_prestamo"];
    $cancel.= $obj_prestamos -> cancelPrestamo($id_prestamo);
}
echo $cancel;

// Filtros a registro de prestamos
if(isset($_GET["filterFechaDestino"]) && isset($_GET["filterFechaHecho"]) && isset($_GET["filterCarnet"]) && isset($_GET["filterEquipo"])) {
    $fecha_destino = $_GET["filterFechaDestino"];
    $fecha_hecha = $_GET["filterFechaHecho"];
    $carnet = $_GET["filterCarnet"];
    $equipo = $_GET["filterEquipo"];

    if($fecha_destino != "" && $fecha_hecha != "" && $carnet != "" && $equipo != "") {
        // Filtrar por todo
        $result = $obj_prestamos -> filterByAll($fecha_destino, $fecha_hecha, $carnet, $equipo);
        showRegistroPrestamos($result);
    } else if($fecha_destino != "" && $fecha_hecha != "" && $equipo != "") {
        $result = $obj_prestamos->filterFechaDestinoHechaEquipo($fecha_destino, $fecha_hecha, $equipo);
        showRegistroPrestamos($result);
    } else if($fecha_destino != "" && $carnet != "" && $equipo != "") {
        $result = $obj_prestamos->filterFechaDestinoCarnetEquipo($fecha_destino, $carnet, $equipo);
        showRegistroPrestamos($result);
    } else if($fecha_destino != "" && $carnet != "" && $fecha_hecha != "") {
        $result = $obj_prestamos->filterFechaDestinoHechaCarnet($fecha_destino, $fecha_hecha,  $carnet);
        showRegistroPrestamos($result);
    } else if($fecha_hecha != "" && $equipo != "" && $carnet != "") {
        $result = $obj_prestamos->filterFechaHechaEquipoCarnet($fecha_hecha, $equipo, $carnet);
        showRegistroPrestamos($result);
    } else if($fecha_destino != "" && $equipo != "") {
        $result = $obj_prestamos->filterFechaDestinoEquipo($fecha_destino, $equipo);
        showRegistroPrestamos($result);
    } else if($fecha_destino != "" && $fecha_hecha != "") {
        $result = $obj_prestamos->filterFechaDestinoHecha($fecha_destino, $fecha_hecha);
        showRegistroPrestamos($result);
    } else if($fecha_hecha != "" && $equipo != "") {
        $result = $obj_prestamos->filterFechaHechaEquipo($fecha_hecha, $equipo);
        showRegistroPrestamos($result);
    } else if($carnet != "" && $fecha_destino != "") {
        $result = $obj_prestamos->filterFechaDestinoCarnet($fecha_destino, $carnet);
        showRegistroPrestamos($result);
    } else if($carnet != "" && $fecha_hecha != "") {
        $result = $obj_prestamos->filterFechaHechaCarnet($fecha_hecha, $carnet);
        showRegistroPrestamos($result);
    } else if($carnet != "" && $equipo != "") {
        $result = $obj_prestamos->filterCarnetEquipo($carnet, $equipo);
        showRegistroPrestamos($result);
    } else if($fecha_destino != "") {
        $result = $obj_prestamos->filterFechaDestino($fecha_destino);
        showRegistroPrestamos($result);
    } else if($fecha_hecha != "") {
        $result = $obj_prestamos->filterFechaHecha($fecha_hecha);
        showRegistroPrestamos($result);
    } else if($carnet != "") {
        $result = $obj_prestamos->filterCarnet($carnet);
        showRegistroPrestamos($result);
    } else if($equipo != "") {
        $result = $obj_prestamos->filterEquipo($equipo);
        showRegistroPrestamos($result);
    } else if($fecha_destino == "" && $equipo == "") {
        $result = $obj_prestamos->consultRegistroPrestamos();
        showRegistroPrestamos($result);
    }
}

function showRegistroPrestamos($prestamos) {
    $tabla= "
    <table class='page__table' id='table-registro-de-prestamos'>
        <thead class='page__table-head'>
            <tr class='page__table-row' style='border: 2px solid var(--color-border)'>    
                <th colspan='3' style='text-align: center;'>Realizado por</th>
                <th colspan='8' style='text-align: center;'>Datos del prestamo</th>
            </tr>
            <tr class='page__table-row'>
                <th class='hidden'>1</th>
                <th class='hidden'>2</th>
                <th class='hidden'>3</th>
                <th class='hidden'>4</th>
                <th class='hidden'>5</th>
                <th class='hidden'>6</th>
                <th class='hidden'>7</th>
                <th class='hidden'>8</th>
                <th class='hidden'>9</th>
                <th class='hidden'>10</th>
                <th class='hidden'>11</th>
            </tr>
        </thead>
        <thead class='page__table-head' style='background-color: var(--color-bg-2)'>
            <tr class='page__table-row'>    
                <th style='width: 150px'>Carnet</th>
                <th style='width: 150px'>Nombres</th>
                <th style='min-width: 150px'>Apellidos</th>
                <th style='min-width: 150px'>ID</th>
                <th style='width: 150px'>Aula o computo usado</th>
                <th style='width: 300px'>Prestamo realizado en</th>
                <th style='width: 300px'>Prestamo agendado para</th>
                <th style='width: 150px'>Estado</th>
                <th style='text-align: center'>Acciones</th>
                <th style='text-align: center'>Detalles</th>
                <th class='hidden'>11</th>
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
                <td>$fila[carnet]</td>
                <td>$fila[nombres]</td>
                <td>$fila[apellidos]</td>
                <td>$fila[id_prestamo]</td>
                <td>$fila[aula]</td>
                <td>$new_fecha_hecha</td>
                <td>$new_fecha_destino</td>
                <td>$fila[estado]</td>
                <td class='no-padding accion'>
                    <div>";

                if($fila["estado"] == "Completado"){
                    $tabla.= "
                    <button type='button' class='btn-accion' style='background-color: #828282; color: var(--color-light); cursor: default'><p>Completado</p></button>
                    ";
                } else if($fila["estado"] == "Cancelado"){
                    $tabla.= "
                    <button type='button' class='btn-accion' style='background-color: #828282; color: var(--color-light); cursor: default'><p>Cancelado</p></button>
                    ";
                } else {
                    $tabla.= "
                    <button type='button' id='marcar-devuelto-$i' class='btn-accion' style='background-color: var(--color-save);'><p>Marcar completado</p></button>
                    <button type='button' id='cancel-user-prestamo-$i' class='btn-accion' style='color: var(--color-light); background-color: var(--color-wrong);'><p>Cancelar</p></button>
                    ";
                }
                
        $tabla.="
                    </div>
                </td>
                <td class='no-padding accion'>
                    <button type='button' class='btn-accion' id='ver_detalles-prestamo-$i' style='background-color: var(--color-edit);' ><p>Ver mas</p></button>
                    <button type='button' class='btn-accion' id='cerrar_detalles-prestamo-$i' style='background-color: var(--color-edit);'><p>Ver menos</p></button>
                </td>
                <th class='hidden'>11</th>
            </tr>";

        $tabla.="
        <tr id='row-number-$i' class='page__table-row' style='border: 2px var(--border)'>
            <td colspan='10' class='no-padding' style='border: 0'>
                <table class='page__table' style='display: table;'>
                    <thead class='page__table-head' style='border: 0'>
                        <tr class='page__table-row' style='border-bottom: 2px var(--border);'>
                            <th colspan='10' style='background-color: var(--color-user-bg); text-align: center; border-bottom: 2px solid var(--color-border)'>Productos agregados a este prestamo</th>
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
                <td>$$detalle_del_prestamo[precio]</td>
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
            <tr class='page__table-row' >
                <td colspan=11' style='border-left: 0; border-right: 0; text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No hay equipos añadidos a este prestamo.</td>
            </tr>
            ";
        }

        $tabla.="
                        <tr class='page__table-row' style='background-color: var(--color-user-bg)'>
                            <td colspan='11'></td>
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
            <th class='hidden'>10</th>
            <th class='hidden'>11</th>
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

            $('#marcar-devuelto-$i').mousedown(function () {
                hideAlert();
                setTimeout(function () {
                    $('#resp').html(
                        `<div id='alertMarcarDevuelto' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closeAlertMarcarDevuelto()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de marcar este prestamo como completado?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='marcarDev($fila[id_prestamo])' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, marcar devuelto</button>
                            <button type='button' onmousedown='closeAlertMarcarDevuelto()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                    )
                }, 1);
            });
            
            function closeAlertMarcarDevuelto() {
                $('#alertMarcarDevuelto').addClass('hidden');
            }

            $('#cancel-user-prestamo-$i').mousedown(function () {
                hideAlert();
                setTimeout(function () {
                    $('#resp').html(
                        `<div id='alertCancelUserPrestamo' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                        <button type='button' onmousedown='closeAlertCancelUserPrestamo()' class='alert__btn-close'>&times;</button>
                        <p class='alert__message'>¿Estas seguro de cancelar este prestamo?</p>
                        <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                            <button type='button' onmousedown='cancelRegistro($fila[id_prestamo])' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, cancelar</button>
                            <button type='button' onmousedown='closeAlertCancelUserPrestamo()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
                        </div>
                    </div>`
                    )
                }, 1);
            });
            
            function closeAlertCancelUserPrestamo() {
                $('#alertCancelUserPrestamo').addClass('hidden');
            }
        </script>
        ";
    }
} else {
    $tabla.= "
    <tr class='page__table-body'>
        <th class='hidden'>1</th>
        <th class='hidden'>2</th>
        <th class='hidden'>3</th>
        <th class='hidden'>4</th>
        <th class='hidden'>5</th>
        <th class='hidden'>6</th>
        <th class='hidden'>7</th>
        <th class='hidden'>8</th>
        <th class='hidden'>9</th>
        <th class='hidden'>10</th>
        <td colspan='10' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ningun prestamo.</td>
    </tr>";
}

    $tabla.= "
        </tbody>
    </table>

    <script>
        $('#table-registro-de-prestamos').DataTable({
            'searching': false,
            'ordering': false,
            'pageLength': 10,
            'lengthMenu': [[10, 20, 50, 100, -1], [5, 10, 25, 50, 'Todos los']],
            drawCallback: function () {
                var api = this.api();
                var info = api.page.info();
                $('#table-registro-de-prestamos_info').html('Mostrando ' + (info.start/2 + 1) + ' a ' + (info.end / 2 == 0.5 ? 1 : info.end / 2) + ' de ' + (info.recordsTotal/2 == 0.5 ? 1 : info.recordsTotal/2) + ' prestamos')
            },
            'language': {
                'lengthMenu': 'Mostrar _MENU_ prestamos',
                'paginate': {
                    'first':      'Primero',
                    'last':       'Ultimo',
                    'next':       'Siguiente',
                    'previous':   'Anterior'
                },
            }
        });
    </script>
    ";

    echo $tabla;
}

?>