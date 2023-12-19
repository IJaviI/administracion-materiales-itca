<?php
require_once("cn.php");

class cls_unidades extends cn {
    public function consult() {
        $sql = "SELECT * FROM unidad"; 
        return $this -> cn() -> query($sql);
    } 

    public function consultUnidadByName($unidad_name) {
        $sql = "SELECT * FROM unidad WHERE unidad = '$unidad_name'"; 
        return $this -> cn() -> query($sql);
    } 

    // Sabemos si hay materiales pertenecientes a la unidad
    public function materialAfiliatedOrNot($id) {
        $sql = "SELECT * FROM material WHERE id_unidad = $id";
        return $this -> cn() -> query($sql);
    }

    public function delete($id) {
        $unidad = $this -> consultUnidad($id);
        $unidadData = $unidad -> fetch_assoc();

        // verificamos si hay materiales afiliados a la unidad (solo si no hay, es posible borrar)
        if (mysqli_num_rows($this -> materialAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, existen materiales que pertenecen a la unidad ($unidadData[unidad]).'); }, 1);
            </script>";
        } else {
            $sql = "DELETE FROM unidad WHERE id_unidad = $id"; 
            $this -> cn() -> query($sql);
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('Unidad ($unidadData[unidad]) fue eliminada con exito.'); }, 1);
            </script>"; 
        }
    }

    public function exists($unidad_name) {
        $sql = "SELECT unidad FROM unidad WHERE unidad = '$unidad_name'"; 
        return $this -> cn() -> query($sql);
    }

    public function insert($unidad_name) {
        // Llamamos la funcion exists para comprobrar si existe esta unidad 
        $result = $this -> exists($unidad_name);
        if(mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO unidad (unidad) VALUES ('$unidad_name')"; 
            $this -> cn() -> query($sql);

            // Cambiamos el estado del boton
            return "
            <script>
                btnStateChange();

                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('Unidad ($unidad_name) fue creada con exito.');
                }, 1);
            </script>
            ";
        } else {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            $unidadExists = $result -> fetch_assoc();
            $unidad = $unidadExists["unidad"];
            return "
            <script>
            $('#unidad_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Ups, unidad ($unidad) ya existe.'); }, 1);
            </script>
            ";
        }

    }

    // Consultando unidades en base a id_unidad
    public function consultUnidad($id) {
        $sql = "SELECT * FROM unidad WHERE id_unidad = $id"; 
        return $this -> cn() -> query($sql);
    } 

    // Filtrando unidades en base a nombre de la unidad
    public function filterUnidadName($filterName) {
        $sql = "SELECT * FROM unidad WHERE unidad like '%$filterName%'"; 
        return $this -> cn() -> query($sql);
    }

    // Actuazlizando unidad
    public function update($id, $unidad_name) {
        // Llamamos la funcion exists para comprobrar si se cambio el nombre de esta unidad o sigue siendo el mismo 
        $result = $this -> exists($unidad_name);
        $fila = $result -> fetch_assoc();
        
        if(mysqli_num_rows($result) == 1) {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            return "
            <script>
            $('#unidad_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            alertRed();
            alerts('Ups, este nombre ya esta asignado a otra unidad.');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE unidad SET unidad =  '$unidad_name' WHERE id_unidad = $id";
            $this -> cn() -> query($sql);
            $_SESSION["unidad"]["unidad_name"] = $unidad_name;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();
                    $('#unidad_name').data('inicialValue', $('#unidad_name').val())

                    hideAlert();
                    alertGreen();
                    setTimeout( function() { alerts('El nombre de la unidad fue editado con exito a ($unidad_name).');
                    }, 1);
                </script>
            ";
        } 
    }

    public function csvUnidades($file) {
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($file["type"], $mimes)) {

            move_uploaded_file($file["tmp_name"], $file["folder"]); 

            $unidad = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE) {
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $unidad[$encoded_row[0]] = $encoded_row;
                    }

                    $data_repits = 0;
                    foreach($unidad as $unidad) {
                        if(mysqli_num_rows($this -> exists($unidad[1])) == 0) {
                            $sql = "INSERT INTO unidad (id_unidad, unidad) VALUES('$unidad[0]', '$unidad[1]')";
                            $result = $this -> cn() -> query($sql);
                        } else {
                            $data_repits++;
                        }
                    }
                }

                if($data_repits == 0) {
                    return "
                    <script>
                        $('#csv_unidad').val(null);
                        hideAlert();
                        alertGreen();
                        setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de unidades fue insertada con exito.'); }, 1);
                    </script>
                    "; 
                } else {
                    return "
                    <script>
                        $('#csv_marcas').val(null);
                        hideAlert();
                        $('#alert').css('background-color', '#ffe979');
                        $('#alert').css('color', 'var(--color-dark)');
                        setTimeout( function() { alerts('El archivo csv ha sido leido, algunas unidades se repiten por lo que solo se han agregado los que no se repiten (si todos se repiten la insercion no se llevara acabo).'); }, 1);
                    </script>
                    "; 
                }
            } else {
                return "
                <script>
                    $('#csv_marcas').val(null);
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('El archivo csv esta vacio, porfavor verifique los datos del archivo.'); }, 1);
                </script>
                ";
            }
        } else {
        return "
        <script>
            $('#csv_deptos').val(null);
            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Error, el archivo ingresado debe ser de tipo csv.'); }, 1);
        </script>
        ";    
        }
    }
}
?>