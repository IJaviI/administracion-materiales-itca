<?php 
// Carga de clases
require_once("cn.php");
require_once("cls_departamentos.php");

class cls_equipos extends cn {
    public function consult($creator_id) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE created_by = $creator_id OR created_by IN (SELECT id_usuario FROM usuario WHERE created_by = $creator_id)";
        return $this -> cn() -> query($sql);
    }

    public function consultAll() {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad";
        return $this -> cn() -> query($sql);
    }

    public function consultEquipo($id) {
        $sql = "SELECT * FROM material WHERE id_material = $id"; 
        return $this -> cn() -> query($sql);
    } 

    // Sabemos si un equipo ya fue registrado a algun prestamo
    public function equipoAfiliatedOrNot($id) {
        $sql = "SELECT * FROM det_prestamo WHERE id_material = $id";
        return $this -> cn() -> query($sql);
    }
    
    public function consultEquipoPorDeptoDeUsuario($id_depto) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $id_depto"; 
        return $this -> cn() -> query($sql);
    }
    
    public function exists($equipo_name, $num_serie) {
        $sql = "SELECT material FROM material WHERE material = '$equipo_name' AND n_serie = '$num_serie'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsForUpdate($equipo, $num_serie) {
        $sql = "SELECT * FROM material WHERE material = '$equipo' AND n_serie = '$num_serie'"; 
        return $this -> cn() -> query($sql);
    }

    public function delete($id) {
        $result = $this -> consultEquipo($id, $_SESSION["userProfile"]["id"]);
        $equipo = $result -> fetch_assoc();
        // verificamos si el equipo ya fue registrado en algun prestamo (solo si no lo esta, es posible borrar)
        if(mysqli_num_rows($this -> equipoAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, este material ya fue registrado en un prestamo.'); }, 10);
            </script>";
        } else {
            $sql = "DELETE FROM material WHERE id_material = $id"; 
            $this -> cn() -> query($sql);
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El material ($equipo[material]) fue eliminado con exito.'); }, 1);
            </script>"; 
        }
    }
    
    public function addEquipo($name, $num_serie, $description, $tipo, $cantidad, $precio, $marca, $depto, $unidad, $created_by) {
        $equipo_exists = $this -> exists($name, $num_serie);

        if(mysqli_num_rows($equipo_exists) == 0) {
            $sql = "INSERT INTO material(material, n_serie, fecha_ingreso, estado, descripcion, tipo, cantidad, precio, id_marca, id_depto, id_unidad, created_by) VALUES('$name', '$num_serie', CURRENT_DATE(), 'Disponible', '$description', '$tipo', $cantidad, $precio, $marca, $depto, $unidad, $created_by)"; 
            $this -> cn() -> query($sql);

            $sql = "INSERT INTO material_archivado(id_material, fecha_ingreso, cantidad_ingresada) VALUES((SELECT id_material FROM material ORDER BY id_material DESC LIMIT 1), CURRENT_DATE(), $cantidad)"; 
            $this -> cn() -> query($sql);

            return "
            <script>
                btnStateChange();

                alertGreen();
                setTimeout( function() { alerts('El material ($name) fue creado con exito.'); }, 10);
            </script>
            ";
        } else {
            return "
            <script>
            $('#equipo_name').css('borderColor', 'var(--color-wrong)');
            $('#equipo_serie').css('borderColor', 'var(--color-wrong)');
            $('#equipo_modelo').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Ups, ya existe un material ($name) con el mismo numero de serie.'); }, 1);
            </script>";
        }
    }

    // Actualizando todo en el equipo
    public function updateAll($id, $name, $num_serie, $description, $tipo, $cantidad, $precio, $marca, $depto, $unidad) {
        $result = $this -> existsForUpdate($name, $num_serie);
        
        if(mysqli_num_rows($result) == 1) {            
            return "
            <script>
            btnInitialState();

            alertRed();
            alerts('Ups, ya existe un material con el mismo nombre ($name) y numero de serie ($num_serie).');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "SELECT IF(cantidad = $cantidad, 0, (SELECT IF($cantidad < cantidad, -(cantidad - $cantidad), $cantidad - cantidad))) AS resultado FROM material WHERE id_material = $id";
            $result = $this -> cn() -> query($sql);
            $calculatedQuantity = $result -> fetch_assoc();
            
            $sql = "SELECT IF(fecha_ingreso = CURRENT_DATE(), true, false) AS resultado FROM material_archivado WHERE id_material = $id ORDER BY id_archivado DESC LIMIT 1";
            $resultado = $this -> cn() -> query($sql);
            $isToday = $resultado -> fetch_assoc();

            if($isToday["resultado"] > 0) {
                if($calculatedQuantity["resultado"] > 0) {
                    $sql = "UPDATE material_archivado SET
                                cantidad_ingresada = cantidad_ingresada + $calculatedQuantity[resultado]
                    WHERE id_material = $id AND fecha_ingreso = CURRENT_DATE()"; 
                    $this -> cn() -> query($sql);
                }
            } else {
                if($calculatedQuantity["resultado"] > 0) {
                    $sql = "INSERT INTO material_archivado(
                                id_material, 
                                fecha_ingreso, 
                                cantidad_ingresada) 
                            VALUES($id,
                                    CURRENT_DATE(),
                                    $calculatedQuantity[resultado])";
                    $this -> cn() -> query($sql);
                }
            }
            
            $sql="UPDATE material SET 
                    material = '$name',
                    n_serie = '$num_serie', 
                    descripcion = '$description', 
                    tipo = '$tipo', 
                    cantidad = $cantidad, 
                    precio = $precio, 
                    id_marca = $marca, 
                    id_depto = $depto, 
                    id_unidad = $unidad
                WHERE id_material = $id";
            $this -> cn() -> query($sql);

            // $sql = "UPDATE material SET material = '$name', n_serie = '$num_serie', descripcion = '$description', tipo = '$tipo', cantidad = cantidad + $cantidad, precio = $precio, id_marca = $marca, id_depto = $depto, id_unidad = $unidad WHERE id_material = $id";
            // $this -> cn() -> query($sql);

            $_SESSION["equipo"]["equipo_name"] = $name;
            $_SESSION["equipo"]["equipo_serie"] = $num_serie;
            $_SESSION["equipo"]["equipo_description"] = $description;
            $_SESSION["equipo"]["equipo_tipo"] = $tipo;
            $_SESSION["equipo"]["equipo_stock"] = $cantidad;
            $_SESSION["equipo"]["equipo_precio"] = $precio;
            $_SESSION["equipo"]["equipo_marca"] = $marca;
            $_SESSION["equipo"]["equipo_depto"] = $depto;
            $_SESSION["equipo"]["equipo_unidad"] = $unidad;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();

                    // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                    $('#equipo_name').data('inicialValue', $('#equipo_name').val());
                    $('#equipo_serie').data('inicialValue', $('#equipo_serie').val());
                    $('#equipo_description').data('inicialValue', $('#equipo_description').val());
                    $('#equipo_tipo').data('inicialValue', $('#equipo_tipo').val());
                    $('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
                    $('#equipo_precio').data('inicialValue', $('#equipo_price').val());
                    $('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
                    $('#equipo_depto').data('inicialValue', $('#equipo_depto').val());
                    $('#equipo_unidad').data('inicialValue', $('#equipo_unidad').val());

                    alertGreen();
                    alerts('Los cambios han sido guardados con exito.');
                </script>
            ";
        } 
    }

    // public function updateBasedOnNameAndSerie($id, $name, $num_serie, $description, $tipo, $cantidad, $precio, $marca, $depto, $unidad) {
    //     $result = $this -> existsForUpdate($name, $num_serie);
        
    //     if(mysqli_num_rows($result) == 1) {
    //         return "
    //         <script>
    //         btnInitialState();

    //         hideAlert();
    //         alertRed();
    //         alerts('Ups, ya existe un material con el mismo nombre ($name) y numero de serie ($num_serie).');
    //         </script>
    //         ";
    //     } else if(mysqli_num_rows($result) == 0) {
    //         $sql = "UPDATE equipo SET equipo = '$name', n_serie = '$num_serie', descripcion = '$description', tipo = '$tipo', cantidad = $cantidad, precio = $precio, id_marca = $marca, id_depto = $depto, id_unidad = $unidad WHERE id_equipo = $id";
    //         $this -> cn() -> query($sql);

    //         $_SESSION["equipo"]["equipo_name"] = $name;
    //         $_SESSION["equipo"]["equipo_serie"] = $num_serie;
    //         $_SESSION["equipo"]["equipo_description"] = $description;
    //         $_SESSION["equipo"]["equipo_tipo"] = $tipo;
    //         $_SESSION["equipo"]["equipo_stock"] = $cantidad;
    //         $_SESSION["equipo"]["equipo_precio"] = $precio;
    //         $_SESSION["equipo"]["equipo_marca"] = $marca;
    //         $_SESSION["equipo"]["equipo_depto"] = $depto;
    //         $_SESSION["equipo"]["equipo_unidad"] = $unidad;

    //         // Cambiamos el estado del boton
    //         return "
    //             <script>
    //                 btnStateChange();

    //                 // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
    //                 $('#equipo_name').data('inicialValue', $('#equipo_name').val());
    //                 $('#equipo_serie').data('inicialValue', $('#equipo_serie').val());
    //                 $('#equipo_description').data('inicialValue', $('#equipo_description').val());
    //                 $('#equipo_tipo').data('inicialValue', $('#equipo_tipo').val());
    //                 $('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
    //                 $('#equipo_precio').data('inicialValue', $('#equipo_price').val());
    //                 $('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
    //                 $('#equipo_depto').data('inicialValue', $('#equipo_depto').val());
    //                 $('#equipo_unidad').data('inicialValue', $('#equipo_unidad').val());

    //                 hideAlert();
    //                 alertGreen();
    //                 setTimeout(function () { alerts('Los cambios han sido guardados con exito.'); }, 10);
    //             </script>
    //         ";
    //     } 
    // }

    public function updateRestOfFields($id, $description, $tipo, $cantidad, $precio, $marca, $depto, $unidad) {
        $sql = "SELECT IF(cantidad = $cantidad, 0, (SELECT IF($cantidad < cantidad, -(cantidad - $cantidad), $cantidad - cantidad))) AS resultado FROM material WHERE id_material = $id";
        $result = $this -> cn() -> query($sql);
        $calculatedQuantity = $result -> fetch_assoc();
        
        $sql = "SELECT IF(fecha_ingreso = CURRENT_DATE(), true, false) AS resultado FROM material_archivado WHERE id_material = $id ORDER BY id_archivado DESC LIMIT 1";
        $resultado = $this -> cn() -> query($sql);
        $isToday = $resultado -> fetch_assoc();

        if($isToday["resultado"] > 0) {
            if($calculatedQuantity["resultado"] > 0) {
                $sql = "UPDATE material_archivado SET
                            cantidad_ingresada = cantidad_ingresada + $calculatedQuantity[resultado]
                WHERE id_material = $id AND fecha_ingreso = CURRENT_DATE()"; 
                $this -> cn() -> query($sql);
            }
        } else {
            if($calculatedQuantity["resultado"] > 0) {
                $sql = "INSERT INTO material_archivado(
                            id_material, 
                            fecha_ingreso, 
                            cantidad_ingresada) 
                        VALUES($id,
                                CURRENT_DATE(),
                                $calculatedQuantity[resultado])";
                $this -> cn() -> query($sql);
            }
        }

        $sql = "UPDATE material SET 
                    descripcion = '$description', 
                    tipo = '$tipo', 
                    cantidad = $cantidad, 
                    precio = $precio, 
                    id_marca = $marca, 
                    id_depto = $depto, 
                    id_unidad = $unidad 
                WHERE id_material = $id";
        $this -> cn() -> query($sql);

        $_SESSION["equipo"]["equipo_description"] = $description;
        $_SESSION["equipo"]["equipo_tipo"] = $tipo;
        $_SESSION["equipo"]["equipo_stock"] = $cantidad;
        $_SESSION["equipo"]["equipo_precio"] = $precio;
        $_SESSION["equipo"]["equipo_marca"] = $marca;
        $_SESSION["equipo"]["equipo_depto"] = $depto;
        $_SESSION["equipo"]["equipo_unidad"] = $unidad;

        // Cambiamos el estado del boton
        return "
            <script>
                btnStateChange();

                // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                $('#equipo_name').data('inicialValue', $('#equipo_name').val());
                $('#equipo_serie').data('inicialValue', $('#equipo_serie').val());
                $('#equipo_description').data('inicialValue', $('#equipo_description').val());
                $('#equipo_tipo').data('inicialValue', $('#equipo_tipo').val());
                $('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
                $('#equipo_precio').data('inicialValue', $('#equipo_price').val());
                $('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
                $('#equipo_depto').data('inicialValue', $('#equipo_depto').val());
                $('#equipo_unidad').data('inicialValue', $('#equipo_unidad').val());
                hideAlert();
                alertGreen();
                setTimeout(function () { alerts('Los cambios han sido guardados con exito.'); }, 10);
            </script>
        ";
    }

    public function filterEquipoByName($equipo_name) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material LIKE '%$equipo_name%' AND depto.id_depto = $id_depto_usuario"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByMarca($equipo_marca) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($equipo_marca == 'Todas'){
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $id_depto_usuario";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $equipo_marca AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByTipo($equipo_tipo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo' AND depto.id_depto = $id_depto_usuario"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoByNameMarca($material_name, $material_marca) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($material_marca == 'Todas') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND marca.id_marca = $material_marca AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaTipo($material_marca, $material_tipo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($material_marca == 'Todas') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo' AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca AND material.tipo = '$material_tipo' AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByNameTipo($material_name, $material_tipo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo' AND depto.id_depto = $id_depto_usuario"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoBy3($material_name, $material_marca, $material_tipo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo' AND depto.id_depto = $id_depto_usuario";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca AND material.material like '%$material_name%' AND material.tipo = '$material_tipo' AND depto.id_depto = $id_depto_usuario";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameAdmin($material_name) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%'"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByMarcaAdmin($material_marca) {
        if($material_marca == 'Todas'){
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByDeptoAdmin($material_depto) {
        if($material_depto == 'Todos'){
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $material_depto"; 
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByTipoAdmin($material_tipo) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo'"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoByNameMarcaAdmin($material_name, $material_marca) {
        if($material_marca == 'Todas') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaTipoAdmin($material_marca, $material_tipo) {
        if($material_marca == 'Todas') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '%$material_tipo%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca AND material.tipo = '$material_tipo'"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByNameTipoAdmin($material_name, $material_tipo) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo'"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByDeptoNameAdmin($material_depto, $material_name) {
        if($material_depto == 'Todos') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material = '%$material_name%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND depto.id_depto = $material_depto"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByDeptoMarcaAdmin($material_depto, $material_marca) {
        if($material_depto == "Todos" && $material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad";
            return $this -> cn() -> query($sql);
        } else if($material_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        } else if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $material_depto"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $material_depto AND marca.id_marca = $material_marca";
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByDeptoTipoAdmin($material_depto, $material_tipo) {
        if($material_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo'"; 
            return $this -> cn() -> query($sql);
        }  else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $material_depto AND material.tipo = '$material_tipo'";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameMarcaTipo($material_name, $material_marca, $material_tipo) {
        if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo'";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca AND material.material like '%$material_name%' AND material.tipo = '$material_tipo' ";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameDeptoTipo($material_name, $material_depto, $material_tipo) {
        if($material_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo'";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE depto.id_depto = $material_depto AND material.material like '%$material_name%' AND material.tipo = '$material_tipo' ";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameDeptoMarca($material_name, $material_depto, $material_marca) {
        if($material_depto == "Todos" && $material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%'"; 
            return $this -> cn() -> query($sql);
        } else if($material_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        } else if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND depto.id_depto = $material_depto"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND depto.id_depto = $material_depto AND marca.id_marca = $material_marca";
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaTipoDepto($material_marca, $material_tipo, $material_depto) {
        if($material_depto == "Todos" && $material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo'"; 
            return $this -> cn() -> query($sql);
        } else if($material_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo' AND marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        } else if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo' AND depto.id_depto = $material_depto"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo' AND depto.id_depto = $material_depto AND marca.id_marca = $material_marca";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByAll($material_name, $material_marca, $equipo_depto, $material_tipo) {
        if($material_marca == "Todas" && $equipo_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo'";
            return $this -> cn() -> query($sql);
        } else if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo' AND depto.id_depto = $equipo_depto";
            return $this -> cn() -> query($sql);
        } else if($equipo_depto == "Todos") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo' AND marca.id_marca = $material_marca";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo' AND marca.id_marca = $material_marca AND depto.id_depto = $equipo_depto";
            return $this -> cn() -> query($sql);
        }
    }

    // Filtros a equipos agregados para ASIGNAR PRESTAMO
    public function filterEquipoByNameAsignar($material_name) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material LIKE '%$material_name%'"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByMarcaAsignar($material_marca) {
        if($material_marca == 'Todas'){
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByTipoAsignar($material_tipo) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo'"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoByNameMarcaAsignar($material_name, $material_marca) {
        if($material_marca == 'Todas') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND marca.id_marca = $material_marca"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaTipoAsignar($material_marca, $material_tipo) {
        if($material_marca == 'Todas') {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.tipo = '$material_tipo'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca AND material.tipo = '$material_tipo'"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByNameTipoAsignar($material_name, $material_tipo) {
        $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
        material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
        INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo'"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoBy3Asignar($material_name, $material_marca, $material_tipo) {
        if($material_marca == "Todas") {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE material.material like '%$material_name%' AND material.tipo = '$material_tipo'";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT material.id_material, material.material, material.n_serie, material.fecha_ingreso, material.estado, material.descripcion, 
            material.tipo, material.cantidad, material.precio, marca.marca, depto.depto, unidad.unidad FROM material INNER JOIN marca ON material.id_marca = marca.id_marca 
            INNER JOIN depto ON material.id_depto = depto.id_depto INNER JOIN unidad ON material.id_unidad = unidad.id_unidad WHERE marca.id_marca = $material_marca AND material.material like '%$material_name%' AND material.tipo = '$material_tipo'";
            return $this -> cn() -> query($sql);
        }
    }


    public function csvEquipos($file) {
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($file["type"], $mimes)) {

            move_uploaded_file($file["tmp_name"], $file["folder"]);

            $equipos = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE){
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $equipos[$encoded_row[0]] = $encoded_row;

                        // Another way of inserting the csv rows
                        // $inserting = $this -> cn() -> prepare("INSERT INTO marca (marca) VALUES (?)");
                        // $inserting -> bind_param("s", $row[1]);
                        // $inserting -> execute();
                    }

                    $data_repits = 0; 
                    foreach($equipos as $equipo){
                        if(mysqli_num_rows($this -> exists($equipo[0], $equipo[1])) == 0) {
                            $fecha = date_create($equipo[2]);
                            $fecha_ingreso = date_format($fecha, 'Y-m-d');

                            $sql = "INSERT INTO material (material, n_serie, fecha_ingreso, estado, descripcion, tipo, cantidad, precio, created_by, id_marca, id_depto, id_unidad) VALUES ('$equipo[0]', '$equipo[1]', '$fecha_ingreso', 'Disponible', '$equipo[3]', '$equipo[4]', $equipo[5], $equipo[6], $equipo[7], $equipo[8], $equipo[9], $equipo[10])";
                            $this -> cn() -> query($sql);
                        } else {
                            $data_repits++;        
                        }
                    }

                    if($data_repits == 0) {
                        return "
                        <script>
                            $('#csv_equipos').val(null);
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de materiales fue insertada con exito.'); }, 1);
                        </script>
                        "; 
                    } else {
                        return "
                        <script>
                            $('#csv_equipos').val(null);
                            hideAlert();
                            $('#alert').css('background-color', '#ffe979');
                            $('#alert').css('color', 'var(--color-dark)');
                            setTimeout( function() { alerts('El archivo csv ha sido leido, algunos materiales se repiten por lo que solo se han agregado los que no se repiten (si todos se repiten la insercion no se llevara acabo).'); }, 1);
                        </script>
                        "; 
                    }
                }    
            } else {
                return "
                <script>
                    $('#csv_equipos').val(null);
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('El archivo csv esta vacio, porfavor verifique los datos del archivo.'); }, 1);
                </script>
                ";    
            }
        } else {
         return "
         <script>
            $('#csv_equiipos').val(null);
            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Error, el archivo ingresado debe ser de tipo csv.'); }, 1);
         </script>
         ";    
        }
    }
}

?>