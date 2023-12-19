<?php
require_once("cn.php");

class cls_aulas extends cn {
    public function consult() {
        $sql = "SELECT * FROM aula ORDER BY tipo ASC"; 
        return $this -> cn() -> query($sql);
    } 

    // Sabemos si hay prestamos pertenecientes al departamento
    public function prestamoAfiliatedOrNot($id) {
        $sql = "SELECT * FROM prestamo WHERE id_aula = $id";
        return $this -> cn() -> query($sql);
    }
    
    public function delete($id) {
        $aula = $this -> consultAula($id);
        $aulaData = $aula -> fetch_assoc();
            
        // verificamos si hay prestamos afiliados al departamento (solo si no hay, es posible borrar)
        if(mysqli_num_rows($this -> prestamoAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, existen prestamos asignados al aula actual.'); }, 1);
            </script>";
        } else {
            $sql = "DELETE FROM aula WHERE id_aula = $id"; 
            $this -> cn() -> query($sql);
            
            $aula_or_computo = $aulaData["tipo"] == 0 ? "El aula ($aulaData[aula]) fue eliminada" : "El computo ($aulaData[aula]) fue eliminado";
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('$aula_or_computo con exito.'); }, 1);
            </script>"; 
        }
    }

    public function existsForInsert($aula, $tipo) {
        $sql = "SELECT * FROM aula WHERE aula = '$aula' AND tipo = '$tipo'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsForUpdate($aula, $tipo) {
        $sql = "SELECT * FROM aula WHERE aula = '$aula' AND tipo = '$tipo'"; 
        return $this -> cn() -> query($sql);
    }

    public function insert($aula, $ubicacion, $descripcion, $tipo) {
        // Llamamos la funcion exists para comprobrar si existe esta aula 
        $result = $this -> existsForInsert($aula, $tipo);

        if(mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO aula (aula, ubicacion, descripcion, tipo) VALUES ('$aula', '$ubicacion', '$descripcion', $tipo)"; 
            $this -> cn() -> query($sql);

            $tipo_aula = $tipo == 1 ? "computo" : "aula";
            // Cambiamos el estado del boton
            return "
            <script>
                btnStateChange();
                $('#aula_type').blur();
                
                alertGreen();
                alerts('El $tipo_aula ($aula) fue creada con exito.');
            </script>
            ";
        } else {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            $aulaExists = $result -> fetch_assoc();
            $aula_or_computo = $aulaExists["tipo"] == 0 ? "el aula" : "el computo"; 
            return "
            <script>
            $('#aula_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Ups, $aula_or_computo ($aulaExists[aula]) ya existe'); }, 1);
            </script>
            ";
        }

    }

    // Consultando aulas en base a id_aula
    public function consultAula($id) {
        $sql = "SELECT * FROM aula WHERE id_aula = $id"; 
        return $this -> cn() -> query($sql);
    } 

    // Filtrando aulas en base al numero del aula o computo
    public function filterAulaName($name) {
        $sql = "SELECT * FROM aula WHERE aula like '%$name%'"; 
        return $this -> cn() -> query($sql);
    }

    // Filtrando aulas en base al tipo (aula o computo)
    public function filterAulaType($type) {
        if($type == 'Ambos') {
            $sql = "SELECT * FROM aula ORDER BY tipo ASC"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT * FROM aula WHERE tipo like '%$type%'"; 
            return $this -> cn() -> query($sql);
        }
    }

    public function filterAulaNameType($name, $type){
        if($type == 'Ambos') {
            $sql = "SELECT * FROM aula WHERE aula like '%$name%' ORDER BY tipo ASC";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT * FROM aula WHERE aula like '%$name%' AND tipo like '%$type%'"; 
            return $this -> cn() -> query($sql);
        }
    }

    // Actualizando todo en el aula
    public function updateAll($id, $aula_name, $aula_ubication, $aula_description, $aula_type) {
        // Llamamos la funcion exists para comprobrar si se cambio algun valor de esta aula o todo sigue siendo lo mismo 
        $result = $this -> existsForUpdate($aula_name, $aula_type);
        
        if(mysqli_num_rows($result) == 1) {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            
            $aula = $result -> fetch_assoc();
            $aula_or_computo = $aula["tipo"] == 0 ? "otra aula" : "otro computo"; 
            
            return "
            <script>
            btnInitialState();

            alertRed();
            alerts('Ups, el nombre ($aula_name) ya fue asignado a $aula_or_computo.');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE aula SET aula = '$aula_name', ubicacion = '$aula_ubication', descripcion = '$aula_description', tipo = $aula_type WHERE id_aula = $id";
            $this -> cn() -> query($sql);

            $_SESSION["aula"]["aula_name"] = $aula_name;
            $_SESSION["aula"]["aula_ubication"] = $aula_ubication;
            $_SESSION["aula"]["aula_description"] = $aula_description;
            $_SESSION["aula"]["aula_type"] = $aula_type;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();

                    // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                    $('#aula_name').data('inicialValue', $('#aula_name').val());
                    $('#aula_type').data('inicialValue', $('#aula_type').val());

                    alertGreen();
                    alerts('Los cambios han sido editados con exito.');
                </script>
            ";
        } 
    }

    // Actualizando solo nombre y tipo en el aula
    public function updateNameType($id, $aula_name, $aula_type) {
        // Llamamos la funcion exists para comprobrar si se cambio algun valor de esta aula o todo sigue siendo lo mismo 
        $result = $this -> existsForUpdate($aula_name, $aula_type);
        
        if(mysqli_num_rows($result) == 1) {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            
            $aula = $result -> fetch_assoc();
            $aula_or_computo = $aula["tipo"] == 0 ? "otra aula" : "otro computo"; 
            
            return "
            <script>
            btnInitialState();

            alertRed();
            alerts('Ups, el nombre ($aula_name) ya fue asignado a $aula_or_computo.');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE aula SET aula = '$aula_name', tipo = $aula_type WHERE id_aula = $id";
            $this -> cn() -> query($sql);

            $_SESSION["aula"]["aula_name"] = $aula_name;
            $_SESSION["aula"]["aula_type"] = $aula_type;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();

                    // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                    $('#aula_name').data('inicialValue', $('#aula_name').val());
                    $('#aula_type').data('inicialValue', $('#aula_type').val());

                    alertGreen();
                    alerts('Los cambios han sido editados con exito.');
                </script>
            ";
        } 
    }

    // Actualizando solo ubicacion y descripcion en el aula
    public function updateUbicationDescription($id, $aula_ubication, $aula_description) {
        $sql = "UPDATE aula SET ubicacion = '$aula_ubication', descripcion = '$aula_description' WHERE id_aula = $id";
        $this -> cn() -> query($sql);

        $_SESSION["aula"]["aula_ubication"] = $aula_ubication;
        $_SESSION["aula"]["aula_description"] = $aula_description;

        // Cambiamos el estado del boton
        return "
            <script>
                btnStateChange();

                // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                $('#aula_ubication').data('inicialValue', $('#aula_ubication').val());
                $('#aula_description').data('inicialValue', $('#aula_description').val());

                alertGreen();
                alerts('Los cambios han sido editados con exito.');
            </script>
        ";
    }

    public function csvAulas($file) {
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($file["type"], $mimes)) {

            move_uploaded_file($file["tmp_name"], $file["folder"]);

            $aulas = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE){
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $aulas[$encoded_row[0]] = $encoded_row;

                        // Another way of inserting the csv rows
                        // $inserting = $this -> cn() -> prepare("INSERT INTO marca (marca) VALUES (?)");
                        // $inserting -> bind_param("s", $row[1]);
                        // $inserting -> execute();
                    }

                    $data_repits = 0; 
                    foreach($aulas as $aula){
                        if(mysqli_num_rows($this -> existsForInsert($aula[1], $aula[4])) == 0) {
                            $sql = "INSERT INTO aula (id_aula, aula, ubicacion, descripcion, tipo) VALUES ('$aula[0]', '$aula[1]', '$aula[2]', '$aula[3]', $aula[4])";
                            $this -> cn() -> query($sql);
                        } else {
                            $data_repits++;        
                        }
                    }

                    if($data_repits == 0) {
                        return "
                        <script>
                            $('#csv_marcas').val(null);
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de aulas o computos fue insertada con exito.'); }, 1);
                        </script>
                        "; 
                    } else {
                        return "
                        <script>
                            $('#csv_marcas').val(null);
                            hideAlert();
                            $('#alert').css('background-color', '#ffe979');
                            $('#alert').css('color', 'var(--color-dark)');
                            setTimeout( function() { alerts('El archivo csv ha sido leido, algunas aulas o computos se repiten por lo que solo se han agregado los que no se repiten (si todos se repiten la insercion no se llevara acabo).'); }, 1);
                        </script>
                        "; 
                    }
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