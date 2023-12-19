<?php
require_once("cn.php");

class cls_prestamos extends cn {
    public function consultPrestamos() {
        $id = $_SESSION["userProfile"]["id"];
        $sql = "SELECT id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', estado, fecha_hecha, fecha_destino FROM prestamo WHERE id_usuario = $id ORDER BY estado DESC, fecha_destino DESC"; 
        return $this -> cn() -> query($sql);
    }

    public function consultPrestamo() {
        $id_prestamo_actual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $id_prestamo_actual"; 
        return $this -> cn() -> query($sql);
    }

    public function consultRegistroPrestamos() {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC"; 
        return $this -> cn() -> query($sql);
    }
    
    // Consultar detalle prestamo
    public function consult_detPrestamo($id_prestamo) {
        $sql = "SELECT det_prestamo.id_prestamo, det_prestamo.id_det_prestamo, det_prestamo.id_material, det_prestamo.estado, det_prestamo.cantidad, CONCAT(IF(HOUR(det_prestamo.inicio) < 10, CONCAT('0', HOUR(det_prestamo.inicio)), HOUR(det_prestamo.inicio)), ':', IF(MINUTE(det_prestamo.inicio) < 10, CONCAT('0', MINUTE(det_prestamo.inicio)), MINUTE(det_prestamo.inicio))) AS inicio, CONCAT(IF(HOUR(det_prestamo.fin) < 10, CONCAT('0', HOUR(det_prestamo.fin)), HOUR(det_prestamo.fin)), ':', IF(MINUTE(det_prestamo.fin) < 10, CONCAT('0', MINUTE(det_prestamo.fin)), MINUTE(det_prestamo.fin))) AS fin, det_prestamo.fecha, material.material, material.n_serie, material.descripcion, material.tipo, material.precio, (SELECT marca FROM marca WHERE id_marca = material.id_marca) as marca, (SELECT depto FROM depto WHERE id_depto = material.id_depto) as depto, (SELECT unidad FROM unidad WHERE id_unidad = material.id_unidad) as unidad FROM det_prestamo INNER JOIN material ON det_prestamo.id_material = material.id_material WHERE id_prestamo = $id_prestamo ORDER BY estado ASC, inicio ASC"; 
        return $this -> cn() -> query($sql);    
    }

    // Actualizar equipo aggregado al prestamo a 'Removido'
    public function remove_equipoAgregado($id_det_prestamo, $equipo, $inicio, $fin) {
        $prestamoActual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $sql = "UPDATE det_prestamo SET estado = 'Removido' WHERE id_det_prestamo = $id_det_prestamo"; 
            $this -> cn() -> query($sql);

            return "
            <script>    
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El material ($equipo) agendado en el horario ($inicio - $fin) ha sido removido con exito del prestamo.'); }, 10);
                mostrarTablaAgregados();
            </script>
            ";
        }
    }

    public function remove_equipoAgregadoNoRetornable($id_det_prestamo, $material) {
        $prestamoActual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $sql = "UPDATE det_prestamo SET estado = 'Removido' WHERE id_det_prestamo = $id_det_prestamo"; 
            $this -> cn() -> query($sql);

            $sql = "SELECT * FROM det_prestamo WHERE id_det_prestamo = $id_det_prestamo";
            $result = $this -> cn() -> query($sql);
            $det_prestamo_data = $result -> fetch_assoc();

            $sql = "SELECT * FROM material WHERE id_material = $det_prestamo_data[id_material]";
            $result = $this -> cn() -> query($sql);
            $material_data = $result -> fetch_assoc();

            $sql = "UPDATE material SET cantidad = ($material_data[cantidad] + $det_prestamo_data[cantidad]) WHERE id_material = $det_prestamo_data[id_material]";
            $this -> cn() -> query($sql);

            return "
            <script>    
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El material ($material) ha sido removido con exito del prestamo.'); }, 10);

                mostrarTablaAgain();
                mostrarTablaAgregados();
            </script>
            ";
        }
    }

    public function cancelPrestamoActual(){
        $prestamoActual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $nuestroPrestamo = $_SESSION["nuestroPrestamo"];
            $sql = "UPDATE prestamo SET estado = 'Cancelado' WHERE id_prestamo = $nuestroPrestamo"; 
            $this -> cn() -> query($sql);

            $sql = "SELECT * FROM det_prestamo WHERE id_prestamo = $nuestroPrestamo";
            $dets_prestamo = $this -> cn() -> query($sql);

            foreach($dets_prestamo as $det_prestamo) {
                $sql = "SELECT tipo, cantidad FROM material WHERE id_material = $det_prestamo[id_material]";
                $result = $this -> cn() -> query($sql);
                $material = $result -> fetch_assoc();
                
                if($material["tipo"] == "No retornable" && $det_prestamo["estado"] == "Agregado") {
                    $sql = "UPDATE material SET cantidad = ($material[cantidad] + $det_prestamo[cantidad])  WHERE id_material = $det_prestamo[id_material]";
                    $this -> cn() -> query($sql);
                }
            }

            $sql = "UPDATE det_prestamo SET estado = 'Cancelado' WHERE id_prestamo = $nuestroPrestamo AND estado = 'Agregado'";
            $this -> cn() -> query($sql);

            unset($_SESSION["creating-prestamo"]);

            return "
            <script>
                $('#iniciar_prestamo').removeClass('hidden');
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');

                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('Prestamo cancelado con exito.'); }, 1);

                $('#fecha_destino').val(null);
                $('#prestamoAula').val(0);
            </script>
            ";
        }
    }

    public function cancelPrestamo($id){
        $sql = "UPDATE prestamo SET estado = 'Cancelado' WHERE id_prestamo = $id"; 
        $this -> cn() -> query($sql);

        $sql = "UPDATE det_prestamo SET estado = 'Cancelado' WHERE id_prestamo = $id AND estado = 'Agregado'";
        $this -> cn() -> query($sql);

        $sql = "SELECT * FROM det_prestamo WHERE id_prestamo = $id";
        $dets_prestamo = $this -> cn() -> query($sql);

        foreach($dets_prestamo as $det_prestamo) {
            $sql = "SELECT tipo, cantidad FROM material WHERE id_material = $det_prestamo[id_material]";
            $result = $this -> cn() -> query($sql);
            $material = $result -> fetch_assoc();
            
            if($material["tipo"] == "No retornable") {
                $sql = "UPDATE material SET cantidad = ($material[cantidad] + $det_prestamo[cantidad])  WHERE id_material = $det_prestamo[id_material]";
                $this -> cn() -> query($sql);
            }
        }
        
        unset($_SESSION["creating-prestamo"]);
        
        return "
        <script>
            hideAlert();
            alertGreen();
            setTimeout( function() { alerts('Prestamo cancelado con exito.'); }, 1);
        </script>
        ";
    }

    public function marcarPrestamoDevuelto($id){
        $sql = "UPDATE prestamo SET estado = 'Completado' WHERE id_prestamo = $id"; 
        $this -> cn() -> query($sql);

        $sql = "UPDATE det_prestamo SET estado = 'Devuelto' WHERE id_prestamo = $id AND estado = 'Agregado' AND inicio != '00:00:00'";
        $this -> cn() -> query($sql);
        
        $sql = "UPDATE det_prestamo SET estado = 'Gastado' WHERE id_prestamo = $id AND estado = 'Agregado' AND inicio = '00:00:00'";
        $this -> cn() -> query($sql);

        return "
        <script>
            hideAlert();
            alertGreen();
            setTimeout( function() { alerts('Prestamo marcado como \"Completado\" con exito.'); }, 1);
        </script>
        ";
    }

    public function finalizarPrestamoActual(){
        unset($_SESSION["creating-prestamo"]);

        $prestamoActual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            return "
            <script>
                $('#iniciar_prestamo').removeClass('hidden');
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');

                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('Prestamo finalizado con exito.'); }, 1);

                $('#fecha_destino').val(null);
                $('#prestamoAula').val(0);
            </script>
            ";
        }
    }

    public function addToPrestamo($id_usuario, $id_aula, $fecha_destino) {        
        $sql = "INSERT INTO prestamo (id_usuario, id_aula, estado, fecha_hecha, fecha_destino) VALUES ($id_usuario, $id_aula, 'En proceso', CURRENT_DATE(), '$fecha_destino')";
        $this -> cn() -> query($sql);

        $sql = "SELECT id_prestamo FROM prestamo ORDER BY id_prestamo DESC LIMIT 1";
        $result = $this -> cn() -> query($sql);
        $nuestroPrestamo = $result -> fetch_assoc();
        $_SESSION["nuestroPrestamo"] = $nuestroPrestamo["id_prestamo"];

        $_SESSION["creating-prestamo"] = "";

        return "
        <script>
            hideAlert();
            alertGreen();
            setTimeout( function() { alerts('Has iniciado tu prestamo con exito, ahora debes agregar los equipos que quieres prestar.'); }, 1);

            $('#iniciar_prestamo').removeClass('show');
            $('#iniciar_prestamo').addClass('hidden');
            $('#agregar__equipos').removeClass('hidden');
            $('#equipos_agregados').removeClass('hidden');

            mostrarTabla();
            mostrarTablaAgregados();
        </script>
        ";
    }

    public function addToDetPrestamo($id_prestamo, $id_equipo, $cantidad, $tipo, $inicio, $inicio_input, $fin, $fin_input, $current_user) {
        $prestamoActual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($cantidad <= 0) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('Cantidad a prestar debe ser minimo 1.'); }, 10);
            </script>
            ";
        } else {
            if($tipo == "No retornable") {
                $sql = "SELECT fecha_destino FROM prestamo WHERE id_prestamo = $id_prestamo";
                $result = $this -> cn() -> query($sql);
                $fecha_destino = $result -> fetch_assoc();

                $sql = "SELECT SUM(cantidad) AS 'cantidad' FROM det_prestamo WHERE id_material = $id_equipo AND fecha = '$fecha_destino[fecha_destino]' AND estado = 'Agregado'";
                $det_prestamo = $this -> cn() -> query($sql);
                $det_prestamo_quantity = $det_prestamo -> fetch_assoc();

                $sql = "SELECT * FROM material WHERE id_material = $id_equipo"; 
                $result = $this -> cn() -> query($sql);
                $equipo = $result -> fetch_assoc();

                $sql = "SELECT cantidad AS 'cantidad' FROM material WHERE id_material = $id_equipo";
                $disponible = $this -> cn() -> query($sql);
                $cantidad_disponible = $disponible -> fetch_assoc();

                if($cantidad <= $equipo["cantidad"]) {
                    // Already exists or not
                    $sql = "SELECT * FROM det_prestamo WHERE id_prestamo = (SELECT id_prestamo FROM prestamo WHERE id_usuario = $current_user ORDER BY id_prestamo DESC LIMIT 1) AND id_material = $id_equipo AND estado = 'Agregado'"; 
                    $result = $this -> cn() -> query($sql);
                    $id_det_prestamo = $result -> fetch_assoc();

                    if(mysqli_num_rows($result) > 0) {
                        $sql = "SELECT cantidad FROM det_prestamo WHERE id_det_prestamo = $id_det_prestamo[id_det_prestamo]"; 
                        $result = $this -> cn() -> query($sql);
                        $cantidad_det = $result -> fetch_assoc();

                        $sql = "UPDATE det_prestamo SET cantidad = ($cantidad_det[cantidad] + $cantidad) WHERE id_det_prestamo = $id_det_prestamo[id_det_prestamo]"; 
                        $this -> cn() -> query($sql);

                        $sql = "UPDATE material SET cantidad = ($cantidad_disponible[cantidad] - $cantidad) WHERE id_material = $id_equipo"; 
                        $this -> cn() -> query($sql);

                        return "
                        <script>
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('Has agregado mas cantidad al material ($equipo[material]) con exito a tu prestamo.'); }, 1);

                            mostrarTablaAgain();
                            mostrarTablaAgregados();
                            $('#$inicio_input').val('');
                            $('#$fin_input').val('');
                        </script>
                        ";
                    } else {
                        $sql = "INSERT INTO det_prestamo (id_prestamo, id_material, cantidad, estado, inicio, fin, fecha) VALUES ($id_prestamo, $id_equipo, $cantidad, 'Agregado', '$inicio', '$fin', '$fecha_destino[fecha_destino]')"; 
                        $this -> cn() -> query($sql);

                        $sql = "UPDATE material SET cantidad = ($cantidad_disponible[cantidad] - $cantidad) WHERE id_material = $id_equipo"; 
                        $this -> cn() -> query($sql);

                        return "
                        <script>
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('Has agregado el material ($equipo[material]) con exito a tu prestamo.'); }, 1);

                            mostrarTablaAgain();
                            mostrarTablaAgregados();
                            $('#$inicio_input').val('');
                            $('#$fin_input').val('');
                        </script>
                        ";
                    }
                } else {
                    $return = "
                        <script>
                            hideAlert();
                            setTimeout( function() { $('#resp').html(
                                `<div id='alertPrestamo' class='alert' style='display: block; padding-bottom: 1.5rem; color: #000; background-color: var(--color-edit);'>
                                    <button type='button' onmousedown='closeAlertPrestamo()' class='alert__btn-close'>&times;</button>
                                    <p class='alert__message'>Existencias ($equipo[cantidad] "; $equipo["cantidad"] > 1 ? $return.="existencias" : $return.="existencia"; $return.=") del material ($equipo[material]) solicitado no son suficientes.</p>
                                </div>`
                            ) }, 1);
                            
                            function closeAlertPrestamo() {
                                $('#alertPrestamo').addClass('hidden');
                            }
                        </script>
                        ";

                        return $return;
                }
            } else {
                $sql = "SELECT fecha_destino FROM prestamo WHERE id_prestamo = $id_prestamo";
                $result = $this -> cn() -> query($sql);
                $fecha_destino = $result -> fetch_assoc();

                if($inicio == '' && $fin == '') {
                    return "
                    <script>
                        hideAlert();
                        alertRed();
                        setTimeout( function() { alerts('Error, para agregar un material debe asignar la hora a la que inicia y finaliza el prestamo del material.'); }, 10);
                        $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                        $('#$fin_input').css('border-color', 'var(--color-wrong)');
                    </script>
                    ";
                }
                else if($inicio == '') {
                    return "
                    <script>
                        hideAlert();
                        alertRed();
                        setTimeout( function() { alerts('Error, para agregar un material debe asignar la hora a la que inicia el prestamo del material.'); }, 10);
                        $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                    </script>
                    ";
                } else if ($fin == '') {
                    return "
                    <script>
                        hideAlert();
                        alertRed();
                        setTimeout( function() { alerts('Error, para agregar un material debe asignar la hora a la que finaliza el prestamo del material.'); }, 10);
                        $('#$fin_input').css('border-color', 'var(--color-wrong)');
                    </script>
                    ";
                } else if ($inicio >= $fin) {
                    return "
                    <script>
                        hideAlert();
                        alertRed();
                        setTimeout( function() { alerts('Error, la hora en la que finaliza el prestamo del material no puede ser menor o igual a la hora en la que este inicia.'); }, 10);
                        $('#$fin_input').css('border-color', 'var(--color-wrong)');
                    </script>
                    ";
                } else {
                    // Checkeamos si todas las existencias del material ya se prestaron para esa hora y fecha
                    $sql = "SELECT id_prestamo, id_material, estado, cantidad, CONCAT(IF(HOUR(inicio) < 10, CONCAT('0', HOUR(inicio)), HOUR(inicio)), ':', IF(MINUTE(inicio) < 10, CONCAT('0', MINUTE(inicio)), MINUTE(inicio))) AS inicio, CONCAT(IF(HOUR(fin) < 10, CONCAT('0', HOUR(fin)), HOUR(fin)), ':', IF(MINUTE(fin) < 10, CONCAT('0', MINUTE(fin)), MINUTE(fin))) AS fin, fecha FROM det_prestamo
                    WHERE (('$inicio' BETWEEN inicio AND fin)
                    OR ('$fin' BETWEEN inicio AND fin)
                    OR (inicio BETWEEN '$inicio' AND '$fin')
                    OR (fin BETWEEN '$inicio' AND '$fin'))
                    AND id_material = $id_equipo AND fecha = '$fecha_destino[fecha_destino]' AND estado = 'Agregado'";
                    $det_prestamo_data = $this -> cn() -> query($sql);

                    $sql = "SELECT SUM(cantidad) AS 'cantidad' FROM det_prestamo
                    WHERE (('$inicio' BETWEEN inicio AND fin)
                    OR ('$fin' BETWEEN inicio AND fin)
                    OR (inicio BETWEEN '$inicio' AND '$fin')
                    OR (fin BETWEEN '$inicio' AND '$fin'))
                    AND id_material = $id_equipo AND fecha = '$fecha_destino[fecha_destino]' AND estado = 'Agregado'";
                    $det_prestamo_sum = $this -> cn() -> query($sql);
                    $det_prestamo_cantidad = $det_prestamo_sum -> fetch_assoc();

                    $sql = "SELECT * FROM material WHERE id_material = $id_equipo"; 
                    $result = $this -> cn() -> query($sql);
                    $equipo = $result -> fetch_assoc();

                    $cantidad_restante_disponible = $equipo["cantidad"] - $det_prestamo_cantidad["cantidad"];

                    if($cantidad > $equipo["cantidad"]) {
                        $return = "
                        <script>
                            hideAlert();
                            setTimeout( function() { $('#resp').html(
                                `<div id='alertPrestamo' class='alert' style='display: block; padding-bottom: 1.5rem; color: #000; background-color: var(--color-edit);'>
                                    <button type='button' onmousedown='closeAlertPrestamo()' class='alert__btn-close'>&times;</button>
                                    <p class='alert__message'>No hay suficientes existencias ($equipo[cantidad] "; $equipo["cantidad"] > 1 ? $return.="existencias" : $return.="existencia"; $return.=") del material ($equipo[material]) solicitado.</p>
                                </div>`
                            ) }, 1);
                            
                            function closeAlertPrestamo() {
                                $('#alertPrestamo').addClass('hidden');
                            }
                        </script>
                        ";

                        return $return;
                    } else if($cantidad > $cantidad_restante_disponible) {
                        $fecha = strtotime($fecha_destino["fecha_destino"]);
                        $new_fecha_destino = date('d-m-Y', $fecha);

                        $return = "
                        <script>
                            hideAlert();
                            setTimeout( function() { $('#resp').html(
                                `<div id='alertPrestamo' class='alert' style='display: block; padding-bottom: 1.5rem; color: #000; background-color: var(--color-edit);'>
                                    <button type='button' onmousedown='closeAlertPrestamo()' class='alert__btn-close'>&times;</button>
                                    <p class='alert__message'>$equipo[cantidad] "; $equipo["cantidad"] > 1 ? $return.="existencias" : $return.="existencia"; $return.=" del material ($equipo[material]) han sido prestadas para la fecha indicada ($new_fecha_destino) y dentro del rango de horario solicitado.</p>
                                    <p class='alert__message' style='margin-top: 1rem; margin-bottom: .5rem'>$cantidad_restante_disponible"; $cantidad_restante_disponible["cantidad"] > 1 ? $return.=" existencias se encuentran disponibles " : $return.=" existencia se encuentra disponible "; $return.="en el horario especificado.</p>
                                    <p class='alert__message' style='margin-top: 1rem; margin-bottom: .5rem'>Detalles a continuacion:</p>
                                    <table class='page__table'>
                                        <thead class='page__table-head' style='background-color: #85e0a3'>
                                            <tr class='page__table-row'>
                                                <th style='border: 2px solid #444444'>Prestado por</th>
                                                <th style='border: 2px solid #444444'>Cantidad prestada</th>
                                                <th style='border: 2px solid #444444'>Fecha</th>
                                                <th style='border: 2px solid #444444'>Inicia</th>
                                                <th style='border: 2px solid #444444'>Finaliza</th>
                                            </tr>
                                        </thead>

                                        <tbody class='page__table-body'>";

                                        $cantidad_total_prestada = 0;

                                        foreach($det_prestamo_data as $det_prestamos){
                                            $cantidad_total_prestada += $det_prestamos["cantidad"];

                                            $fecha = strtotime($det_prestamos["fecha"]);
                                            $new_fecha = date('d-m-Y', $fecha);

                                            $sql= "SELECT * FROM prestamo WHERE id_prestamo = $det_prestamos[id_prestamo]";
                                            $result = $this -> cn() -> query($sql);
                                            $prestamo = $result -> fetch_assoc();

                                            $sql= "SELECT * FROM usuario WHERE id_usuario = $prestamo[id_usuario]";
                                            $result = $this -> cn() -> query($sql);
                                            $prestado_por = $result -> fetch_assoc();

                                            $return.="
                                            <tr class='page__table-row'>
                                                <td style='border: 2px solid #444444'>$prestado_por[nom_docente] $prestado_por[ape_usuario]";
                                                if($prestado_por["nom_usuario"] == $_SESSION["userProfile"]["names"] && $prestado_por["ape_usuario"] == $_SESSION["userProfile"]["lastnames"]) {
                                                    $return.="(Tu)";
                                                }
                                                $return.="</td>
                                                <td style='border: 2px solid #444444'>$det_prestamos[cantidad]</td>
                                                <td style='border: 2px solid #444444'>$new_fecha</td>
                                                <td style='border: 2px solid #444444'>$det_prestamos[inicio]</td>
                                                <td style='border: 2px solid #444444'>$det_prestamos[fin]</td>
                                            </tr>
                                            ";
                                        }
                                        $return.="    
                                            <tr class='page__table-row'>
                                                <th style='border: 2px solid #444444'>Total</th>
                                                <th style='border: 2px solid #444444'>$cantidad_total_prestada</th>
                                                <td style='border: 2px solid #444444'></td>
                                                <td style='border: 2px solid #444444'></td>
                                                <td style='border: 2px solid #444444'></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>`
                            ) }, 1);
                            
                            function closeAlertPrestamo() {
                                $('#alertPrestamo').addClass('hidden');
                            }
                        </script>
                        ";

                        return $return;
                    } else {
                        $sql = "INSERT INTO det_prestamo (id_prestamo, id_material, estado, cantidad, inicio, fin, fecha) VALUES ($id_prestamo, $id_equipo, 'Agregado', $cantidad, '$inicio', '$fin', '$fecha_destino[fecha_destino]')"; 
                        $this -> cn() -> query($sql);

                        return "
                        <script>
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('Has agregado el material ($equipo[material]) con exito a tu prestamo.'); }, 1);
                            
                            mostrarTablaAgain();
                            mostrarTablaAgregados();
                            $('#$inicio_input').val('');
                            $('#$fin_input').val('');
                        </script>
                        ";
                    }
                }
            }
        }
    }

    public function actualizarDetPrestamo($id_prestamo, $id_det_prestamo, $id_equipo, $inicio, $inicio_input, $fin, $fin_input) {
        $prestamoActual = $_SESSION["nuestroPrestamo"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $sql = "SELECT fecha_destino FROM prestamo WHERE id_prestamo = $id_prestamo";
            $result = $this -> cn() -> query($sql);
            $fecha_destino = $result -> fetch_assoc();

            if($inicio == '' && $fin == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para actualizar debe asignar la hora a la que inicia y finaliza el prestamo del material.'); }, 10);
                    $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            }
            else if($inicio == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para actualizar debe asignar la hora a la que inicia el prestamo del material.'); }, 10);
                    $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else if ($fin == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para actualizar debe asignar la hora a la que finaliza el prestamo del material.'); }, 10);
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else if ($inicio >= $fin) {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, la hora en la que finaliza el prestamo del material no puede ser menor o igual a la hora en la que este inicia.'); }, 10);
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else {
                // Checkeamos si el material ya se presto para esa hora y fecha
                $sql = "SELECT id_prestamo, id_material, estado, CONCAT(IF(HOUR(inicio) < 10, CONCAT('0', HOUR(inicio)), HOUR(inicio)), ':', IF(MINUTE(inicio) < 10, CONCAT('0', MINUTE(inicio)), MINUTE(inicio))) AS inicio, CONCAT(IF(HOUR(fin) < 10, CONCAT('0', HOUR(fin)), HOUR(fin)), ':', IF(MINUTE(fin) < 10, CONCAT('0', MINUTE(fin)), MINUTE(fin))) AS fin, fecha FROM det_prestamo
                WHERE (('$inicio' BETWEEN inicio AND fin)
                OR ('$fin' BETWEEN inicio AND fin)
                OR (inicio BETWEEN '$inicio' AND '$fin')
                OR (fin BETWEEN '$inicio' AND '$fin'))
                AND id_material = $id_equipo AND fecha = '$fecha_destino[fecha_destino]' AND estado = 'Agregado'";
                $det_prestamo_data = $this -> cn() -> query($sql);

                $sql = "SELECT * FROM material WHERE id_material = $id_equipo"; 
                $result = $this -> cn() -> query($sql);
                $equipo = $result -> fetch_assoc();

                if(mysqli_num_rows($det_prestamo_data) >= $equipo["cantidad"]) {
                    $fecha = strtotime($fecha_destino["fecha_destino"]);
                    $new_fecha_destino = date('d-m-Y', $fecha);

                    $return = "
                    <script>
                        hideAlert();
                        setTimeout( function() { $('#resp').html(
                            `<div id='alertPrestamo' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                                <button type='button' onmousedown='closeAlertPrestamo()' class='alert__btn-close'>&times;</button>
                                <p class='alert__message'>Todas las existencias ($equipo[cantidad] "; $equipo["cantidad"] > 1 ? $return.="existencias" : $return.="existencia"; $return.=") del material ($equipo[material]) han sido prestadas para la fecha indicada ($new_fecha_destino) y en el horario solicitado.</p>
                                <p class='alert__message' style='margin-top: 1rem; margin-bottom: .5rem'>Detalles a continuacion:</p>
                                <table class='page__table'>
                                    <thead class='page__table-head' style='background-color: #85e0a3'>
                                        <tr class='page__table-row'>
                                            <th style='border: 2px solid #444444'>Prestado por</th>
                                            <th style='border: 2px solid #444444'>Fecha</th>
                                            <th style='border: 2px solid #444444'>Inicia</th>
                                            <th style='border: 2px solid #444444'>Finaliza</th>
                                        </tr>
                                    </thead>

                                    <tbody class='page__table-body'>";
                                    foreach($det_prestamo_data as $det_prestamos){
                                        $fecha = strtotime($det_prestamos["fecha"]);
                                        $new_fecha = date('d-m-Y', $fecha);

                                        $sql= "SELECT * FROM prestamo WHERE id_prestamo = $det_prestamos[id_prestamo]";
                                        $result = $this -> cn() -> query($sql);
                                        $prestamo = $result -> fetch_assoc();

                                        $sql= "SELECT * FROM usuario WHERE id_usuario = $prestamo[id_usuario]";
                                        $result = $this -> cn() -> query($sql);
                                        $prestado_por = $result -> fetch_assoc();

                                        $return.="
                                        <tr class='page__table-row'>
                                            <td style='border: 2px solid #444444'>$prestado_por[nom_usuario] $prestado_por[ape_usuario]";
                                            if($prestado_por["nom_usuario"] == $_SESSION["userProfile"]["names"] && $prestado_por["ape_usuario"] == $_SESSION["userProfile"]["lastnames"]) {
                                                $return.="(Tu)";
                                            }
                                            $return.="</td>
                                            <td style='border: 2px solid #444444'>$new_fecha</td>
                                            <td style='border: 2px solid #444444'>$det_prestamos[inicio]</td>
                                            <td style='border: 2px solid #444444'>$det_prestamos[fin]</td>
                                        </tr>
                                        ";
                                    }
                                    $return.="    
                                    </tbody>
                                </table>
                            </div>`
                        ) }, 1);
                        
                        function closeAlertPrestamo() {
                            $('#alertPrestamo').addClass('hidden');
                        }
                    </script>
                    ";

                    return $return;
                } else {
                    $sql = "UPDATE det_prestamo SET inicio = '$inicio', fin = '$fin' WHERE id_det_prestamo = $id_det_prestamo"; 
                    $this -> cn() -> query($sql);

                    return "
                    <script>
                        hideAlert();
                        alertGreen();
                        setTimeout( function() { alerts('Has actualizado el horario de prestamo del ($equipo[material]) con exito.'); }, 1);

                        mostrarTablaAgregados();
                    </script>
                    ";
                }
            }
        }
    }

    // Filtros a registro de prestamos
    public function filterFechaDestino($fecha_destino) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaHecha($fecha_hecha) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_hecha LIKE '%$fecha_hecha%' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterCarnet($carnet) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterEquipo($equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaDestinoEquipo($fecha_destino, $equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaDestinoHecha($fecha_destino, $fecha_hecha) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.fecha_hecha LIKE '%$fecha_hecha%' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaHechaEquipo($fecha_hecha, $equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_hecha LIKE '%$fecha_hecha%' AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaDestinoCarnet($fecha_destino, $carnet) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaHechaCarnet($fecha_hecha, $carnet) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_hecha LIKE '%$fecha_hecha%' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterCarnetEquipo($carnet, $equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaDestinoHechaEquipo($fecha_destino, $fecha_hecha, $equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.fecha_hecha LIKE '%$fecha_hecha%' AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaDestinoCarnetEquipo($fecha_destino, $carnet, $equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaDestinoHechaCarnet($fecha_destino, $fecha_hecha, $carnet) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.fecha_hecha LIKE '%$fecha_hecha%' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterFechaHechaEquipoCarnet($fecha_hecha, $equipo, $carnet) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_hecha LIKE '%$fecha_hecha%' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_material` IN ((SELECT id_material FROM material WHERE material LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    public function filterByAll($fecha_destino, $fecha_hecha, $carnet, $equipo) {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino LIKE '%$fecha_destino%' AND prestamo.fecha_hecha LIKE '%$fecha_hecha%' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE carnet LIKE '%$carnet%') AND prestamo.id_prestamo IN ((SELECT id_prestamo FROM det_prestamo WHERE id_equipo IN ((SELECT id_equipo FROM equipo WHERE equipo LIKE '%$equipo%')))) ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
        return $this -> cn() -> query($sql);
    }

    // RELACIONADO A ASIGNAR PRESTAMO
    public function consultPrestamoAsignado() {
        $id_prestamo_actual = $_SESSION["nuestroPrestamoAsignado"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $id_prestamo_actual"; 
        return $this -> cn() -> query($sql);
    }

    // Actualizar equipo aggregado al prestamo a 'Removido'
    public function remove_equipoAgregadoWhenAsigning($id_det_prestamo, $equipo, $inicio, $fin) {
        $nuestroPrestamoAsignado = $_SESSION["nuestroPrestamoAsignado"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $nuestroPrestamoAsignado";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $sql = "UPDATE det_prestamo SET estado = 'Removido' WHERE id_det_prestamo = $id_det_prestamo"; 
            $this -> cn() -> query($sql);

            return "
            <script>    
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El material ($equipo) agendado en el horario ($inicio - $fin) ha sido removido con exito del prestamo.'); }, 10);
                mostrarTablaAgregados();
            </script>
            ";
        }
    }

    public function cancelPrestamoAsignado(){
        $prestamoActual = $_SESSION["nuestroPrestamoAsignado"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $nuestroPrestamoAsignado = $_SESSION["nuestroPrestamoAsignado"];
            $sql = "UPDATE prestamo SET estado = 'Cancelado' WHERE id_prestamo = $nuestroPrestamoAsignado"; 
            $this -> cn() -> query($sql);

            $sql = "UPDATE det_prestamo SET estado = 'Cancelado' WHERE id_prestamo = $nuestroPrestamoAsignado AND estado = 'Agregado'";
            $this -> cn() -> query($sql);

            unset($_SESSION["asigning-prestamo"]);

            return "
            <script>
                $('#iniciar_prestamo').removeClass('hidden');
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');

                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('Asignación de prestamo cancelada con exito.'); }, 1);

                $('#fecha_destino').val(null);
            </script>
            ";
        }
    }

    public function finalizarPrestamoAsignado(){
        unset($_SESSION["asigning-prestamo"]);

        $nuestroPrestamoAsignado = $_SESSION["nuestroPrestamoAsignado"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $nuestroPrestamoAsignado";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            return "
            <script>
                $('#iniciar_prestamo').removeClass('hidden');
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');

                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('Asignación de prestamo finalizada con exito.'); }, 1);

                $('#fecha_destino').val(null);
            </script>
            ";
        }
    }
    
    public function addToPrestamoWhenAsigning($id_docente, $id_aula, $fecha_destino) {        
        $sql = "INSERT INTO prestamo (id_usuario, id_aula, estado, fecha_hecha, fecha_destino) VALUES ($id_docente, $id_aula, 'En proceso', CURRENT_DATE(), '$fecha_destino')"; 
        $this -> cn() -> query($sql);

        $sql = "SELECT id_prestamo FROM prestamo ORDER BY id_prestamo DESC LIMIT 1";
        $result = $this -> cn() -> query($sql);
        $nuestroPrestamoAsignado = $result -> fetch_assoc();
        $_SESSION["nuestroPrestamoAsignado"] = $nuestroPrestamoAsignado["id_prestamo"];

        $_SESSION["asigning-prestamo"] = "";

        return "
        <script>
            hideAlert();
            alertGreen();
            setTimeout( function() { alerts('Has asignado el prestamo con exito, ahora debes agregar los materiales a prestar.'); }, 1);

            $('#iniciar_prestamo').removeClass('show');
            $('#iniciar_prestamo').addClass('hidden');
            $('#agregar__equipos').removeClass('hidden');
            $('#equipos_agregados').removeClass('hidden');
            
            mostrarTabla();
            mostrarTablaAgregados();
        </script>
        ";
    }

    public function addToDetPrestamoWhenAsigning($id_prestamo, $id_equipo, $inicio, $inicio_input, $fin, $fin_input) {
        $nuestroPrestamoAsignado = $_SESSION["nuestroPrestamoAsignado"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $nuestroPrestamoAsignado";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $sql = "SELECT fecha_destino FROM prestamo WHERE id_prestamo = $id_prestamo";
            $result = $this -> cn() -> query($sql);
            $fecha_destino = $result -> fetch_assoc();

            if($inicio == '' && $fin == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para agregar un material debe asignar la hora a la que inicia y finaliza el prestamo del material.'); }, 10);
                    $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            }
            else if($inicio == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para agregar un material debe asignar la hora a la que inicia el prestamo del material.'); }, 10);
                    $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else if ($fin == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para agregar un material debe asignar la hora a la que finaliza el prestamo del material.'); }, 10);
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else if ($inicio >= $fin) {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, la hora en la que finaliza el prestamo del material no puede ser menor o igual a la hora en la que este inicia.'); }, 10);
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else {
                // Checkeamos si todas las existencias del material ya se prestaron para esa hora y fecha
                $sql = "SELECT id_prestamo, id_material, estado, CONCAT(IF(HOUR(inicio) < 10, CONCAT('0', HOUR(inicio)), HOUR(inicio)), ':', IF(MINUTE(inicio) < 10, CONCAT('0', MINUTE(inicio)), MINUTE(inicio))) AS inicio, CONCAT(IF(HOUR(fin) < 10, CONCAT('0', HOUR(fin)), HOUR(fin)), ':', IF(MINUTE(fin) < 10, CONCAT('0', MINUTE(fin)), MINUTE(fin))) AS fin, fecha FROM det_prestamo
                WHERE (('$inicio' BETWEEN inicio AND fin)
                OR ('$fin' BETWEEN inicio AND fin)
                OR (inicio BETWEEN '$inicio' AND '$fin')
                OR (fin BETWEEN '$inicio' AND '$fin'))
                AND id_material = $id_equipo AND fecha = '$fecha_destino[fecha_destino]' AND estado = 'Agregado'";
                $det_prestamo_data = $this -> cn() -> query($sql);

                $sql = "SELECT * FROM material WHERE id_material = $id_equipo"; 
                $result = $this -> cn() -> query($sql);
                $equipo = $result -> fetch_assoc();

                if(mysqli_num_rows($det_prestamo_data) >= $equipo["stock"]) {
                    $fecha = strtotime($fecha_destino["fecha_destino"]);
                    $new_fecha_destino = date('d-m-Y', $fecha);

                    $return = "
                    <script>
                        hideAlert();
                        setTimeout( function() { $('#resp').html(
                            `<div id='alertPrestamo' class='alert' style='display: block; padding-bottom: 1.5rem; color: #000; background-color: var(--color-edit);'>
                                <button type='button' onmousedown='closeAlertPrestamo()' class='alert__btn-close'>&times;</button>
                                <p class='alert__message'>Todas las existencias ($equipo[cantidad] "; $equipo["cantidad"] > 1 ? $return.="existencias" : $return.="existencia"; $return.=") del material ($equipo[material]) han sido prestadas para la fecha indicada ($new_fecha_destino) y en el horario solicitado.</p>
                                <p class='alert__message' style='margin-top: 1rem; margin-bottom: .5rem'>Detalles a continuacion:</p>
                                <table class='page__table'>
                                    <thead class='page__table-head' style='background-color: #85e0a3'>
                                        <tr class='page__table-row'>
                                            <th style='border: 2px solid #444444'>Prestado por</th>
                                            <th style='border: 2px solid #444444'>Fecha</th>
                                            <th style='border: 2px solid #444444'>Inicia</th>
                                            <th style='border: 2px solid #444444'>Finaliza</th>
                                        </tr>
                                    </thead>

                                    <tbody class='page__table-body'>";
                                    foreach($det_prestamo_data as $det_prestamos){
                                        $fecha = strtotime($det_prestamos["fecha"]);
                                        $new_fecha = date('d-m-Y', $fecha);

                                        $sql= "SELECT * FROM prestamo WHERE id_prestamo = $det_prestamos[id_prestamo]";
                                        $result = $this -> cn() -> query($sql);
                                        $prestamo = $result -> fetch_assoc();

                                        $sql= "SELECT * FROM usuario WHERE id_usuario = $prestamo[id_usuario]";
                                        $result = $this -> cn() -> query($sql);
                                        $prestado_por = $result -> fetch_assoc();

                                        $return.="
                                        <tr class='page__table-row'>
                                            <td style='border: 2px solid #444444'>$prestado_por[nom_usuario] $prestado_por[ape_usuario]";
                                            if($prestado_por["nom_usuario"] == $_SESSION["userProfile"]["names"] && $prestado_por["ape_usuario"] == $_SESSION["userProfile"]["lastnames"]) {
                                                $return.="(Tu)";
                                            }
                                            $return.="</td>
                                            <td style='border: 2px solid #444444'>$new_fecha</td>
                                            <td style='border: 2px solid #444444'>$det_prestamos[inicio]</td>
                                            <td style='border: 2px solid #444444'>$det_prestamos[fin]</td>
                                        </tr>
                                        ";
                                    }
                                    $return.="    
                                    </tbody>
                                </table>
                            </div>`
                        ) }, 1);
                        
                        function closeAlertPrestamo() {
                            $('#alertPrestamo').addClass('hidden');
                        }
                    </script>
                    ";

                    return $return;
                } else {
                    $sql = "INSERT INTO det_prestamo (id_prestamo, id_material, estado, inicio, fin, fecha) VALUES ($id_prestamo, $id_equipo, 'Agregado', '$inicio', '$fin', '$fecha_destino[fecha_destino]')"; 
                    $this -> cn() -> query($sql);

                    return "
                    <script>
                        hideAlert();
                        alertGreen();
                        setTimeout( function() { alerts('Has agregado el material ($equipo[material]) con exito al prestamo.'); }, 1);

                        mostrarTablaAgregados();
                        $('#$inicio_input').val('');
                        $('#$fin_input').val('');
                    </script>
                    ";
                }
            }
        }
    }

    public function actualizarDetPrestamoWhenAsigning($id_prestamo, $id_det_prestamo, $id_equipo, $inicio, $inicio_input, $fin, $fin_input) {
        $nuestroPrestamoAsignado = $_SESSION["nuestroPrestamoAsignado"];
        $sql = "SELECT * FROM prestamo WHERE id_prestamo = $nuestroPrestamoAsignado";
        $result = $this -> cn() -> query($sql);
        $estado = $result -> fetch_assoc();

        if($estado["estado"] == "Cancelado"){
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('El prestamo ha sido cancelado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else if($estado["estado"] == "Completado"){
            return "
            <script>
                hideAlert();
                setTimeout( function() { alerts('El prestamo ha sido marcado como completado.'); }, 10);
                $('#agregar__equipos').addClass('hidden');
                $('#equipos_agregados').addClass('hidden');
                $('#iniciar_prestamo').removeClass('hidden');
            </script>
            ";
        } else {
            $sql = "SELECT fecha_destino FROM prestamo WHERE id_prestamo = $id_prestamo";
            $result = $this -> cn() -> query($sql);
            $fecha_destino = $result -> fetch_assoc();

            if($inicio == '' && $fin == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para actualizar debe asignar la hora a la que inicia y finaliza el prestamo del material.'); }, 10);
                    $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            }
            else if($inicio == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para actualizar debe asignar la hora a la que inicia el prestamo del material.'); }, 10);
                    $('#$inicio_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else if ($fin == '') {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, para actualizar debe asignar la hora a la que finaliza el prestamo del material.'); }, 10);
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else if ($inicio >= $fin) {
                return "
                <script>
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('Error, la hora en la que finaliza el prestamo del material no puede ser menor o igual a la hora en la que este inicia.'); }, 10);
                    $('#$fin_input').css('border-color', 'var(--color-wrong)');
                </script>
                ";
            } else {
                // Checkeamos si el material ya se presto para esa hora y fecha
                $sql = "SELECT id_prestamo, id_material, estado, CONCAT(IF(HOUR(inicio) < 10, CONCAT('0', HOUR(inicio)), HOUR(inicio)), ':', IF(MINUTE(inicio) < 10, CONCAT('0', MINUTE(inicio)), MINUTE(inicio))) AS inicio, CONCAT(IF(HOUR(fin) < 10, CONCAT('0', HOUR(fin)), HOUR(fin)), ':', IF(MINUTE(fin) < 10, CONCAT('0', MINUTE(fin)), MINUTE(fin))) AS fin, fecha FROM det_prestamo
                WHERE (('$inicio' BETWEEN inicio AND fin)
                OR ('$fin' BETWEEN inicio AND fin)
                OR (inicio BETWEEN '$inicio' AND '$fin')
                OR (fin BETWEEN '$inicio' AND '$fin'))
                AND id_material = $id_equipo AND fecha = '$fecha_destino[fecha_destino]' AND estado = 'Agregado'";
                $det_prestamo_data = $this -> cn() -> query($sql);

                $sql = "SELECT * FROM material WHERE id_material = $id_equipo"; 
                $result = $this -> cn() -> query($sql);
                $equipo = $result -> fetch_assoc();

                if(mysqli_num_rows($det_prestamo_data) >= $equipo["stock"]) {
                    $fecha = strtotime($fecha_destino["fecha_destino"]);
                    $new_fecha_destino = date('d-m-Y', $fecha);

                    $return = "
                    <script>
                        hideAlert();
                        setTimeout( function() { $('#resp').html(
                            `<div id='alertPrestamo' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
                                <button type='button' onmousedown='closeAlertPrestamo()' class='alert__btn-close'>&times;</button>
                                <p class='alert__message'>Todas las existencias ($equipo[cantidad] "; $equipo["cantidad"] > 1 ? $return.="existencias" : $return.="existencia"; $return.=") del material ($equipo[material]) han sido prestadas para la fecha indicada ($new_fecha_destino) y en el horario solicitado.</p>
                                <p class='alert__message' style='margin-top: 1rem; margin-bottom: .5rem'>Detalles a continuacion:</p>
                                <table class='page__table'>
                                    <thead class='page__table-head' style='background-color: #85e0a3'>
                                        <tr class='page__table-row'>
                                            <th style='border: 2px solid #444444'>Prestado por</th>
                                            <th style='border: 2px solid #444444'>Fecha</th>
                                            <th style='border: 2px solid #444444'>Inicia</th>
                                            <th style='border: 2px solid #444444'>Finaliza</th>
                                        </tr>
                                    </thead>

                                    <tbody class='page__table-body'>";
                                    foreach($det_prestamo_data as $det_prestamos){
                                        $fecha = strtotime($det_prestamos["fecha"]);
                                        $new_fecha = date('d-m-Y', $fecha);

                                        $sql= "SELECT * FROM prestamo WHERE id_prestamo = $det_prestamos[id_prestamo]";
                                        $result = $this -> cn() -> query($sql);
                                        $prestamo = $result -> fetch_assoc();

                                        $sql= "SELECT * FROM usuario WHERE id_usuario = $prestamo[id_usuario]";
                                        $result = $this -> cn() -> query($sql);
                                        $prestado_por = $result -> fetch_assoc();

                                        $return.="
                                        <tr class='page__table-row'>
                                            <td style='border: 2px solid #444444'>$prestado_por[nom_usuario] $prestado_por[ape_usuario]";
                                            if($prestado_por["nom_usuario"] == $_SESSION["userProfile"]["names"] && $prestado_por["ape_usuario"] == $_SESSION["userProfile"]["lastnames"]) {
                                                $return.="(Tu)";
                                            }
                                            $return.="</td>
                                            <td style='border: 2px solid #444444'>$new_fecha</td>
                                            <td style='border: 2px solid #444444'>$det_prestamos[inicio]</td>
                                            <td style='border: 2px solid #444444'>$det_prestamos[fin]</td>
                                        </tr>
                                        ";
                                    }
                                    $return.="    
                                    </tbody>
                                </table>
                            </div>`
                        ) }, 1);
                        
                        function closeAlertPrestamo() {
                            $('#alertPrestamo').addClass('hidden');
                        }
                    </script>
                    ";

                    return $return;
                } else {
                    $sql = "UPDATE det_prestamo SET inicio = '$inicio', fin = '$fin' WHERE id_det_prestamo = $id_det_prestamo"; 
                    $this -> cn() -> query($sql);

                    return "
                    <script>
                        hideAlert();
                        alertGreen();
                        setTimeout( function() { alerts('Has actualizado el horario de prestamo del ($equipo[material]) con exito.'); }, 1);

                        mostrarTablaAgregados();
                    </script>
                    ";
                }
            }
        }
    }
}
?>  