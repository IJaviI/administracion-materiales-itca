<?php
require_once("cn.php");

class cls_departamentos extends cn {
    public function consult() {
        $sql = "SELECT * FROM depto"; 
        return $this -> cn() -> query($sql);
    } 

    public function consultDeptoByName($depto_name) {
        $sql = "SELECT * FROM depto WHERE depto = '$depto_name'"; 
        return $this -> cn() -> query($sql);
    } 

    // Sabemos si hay docentes pertenecientes al departamento
    public function docenteAfiliatedOrNot($id) {
        $sql = "SELECT * FROM docente WHERE id_depto = $id";
        return $this -> cn() -> query($sql);
    }

    // Sabemos si hay equipos pertenecientes al departamento
    public function equipoAfiliatedOrNot($id) {
        $sql = "SELECT * FROM equipo WHERE id_depto = $id";
        return $this -> cn() -> query($sql);
    }

    public function delete($id) {
        $depto = $this -> consultDepto($id);
        $deptoData = $depto -> fetch_assoc();

        // verificamos si hay docentes o equipos afiliados al departamento (solo si no hay, es posible borrar)
        if(mysqli_num_rows($this -> docenteAfiliatedOrNot($id)) >= 1 && mysqli_num_rows($this -> equipoAfiliatedOrNot($id))) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, existen docentes y equipos que pertenecen al departamento ($deptoData[depto]).'); }, 1);
            </script>";
        } else if (mysqli_num_rows($this -> docenteAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, existen docentes que pertenecen al departamento ($deptoData[depto]).'); }, 1);
            </script>";
        } else if(mysqli_num_rows($this -> equipoAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, existen equipos que pertenecen al departamento ($deptoData[depto]).'); }, 1);
            </script>";
        } else {
            $sql = "DELETE FROM depto WHERE id_depto = $id"; 
            $this -> cn() -> query($sql);
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El departamento ($deptoData[depto]) fue eliminado con exito.'); }, 1);
            </script>"; 
        }
    }

    public function exists($depto_name) {
        $sql = "SELECT depto FROM depto WHERE depto = '$depto_name'"; 
        return $this -> cn() -> query($sql);
    }

    public function insert($depto_name) {
        // Llamamos la funcion exists para comprobrar si existe este departamento 
        $result = $this -> exists($depto_name);
        if(mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO depto (depto) VALUES ('$depto_name')"; 
            $this -> cn() -> query($sql);

            // Cambiamos el estado del boton
            return "
            <script>
                btnStateChange();

                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El departamento ($depto_name) fue creado con exito.');
                }, 1);
            </script>
            ";
        } else {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            $departamentoExists = $result -> fetch_assoc();
            $departamento = $departamentoExists["depto"];
            return "
            <script>
            $('#depto_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Ups, el departamento ($departamento) ya existe.'); }, 1);
            </script>
            ";
        }

    }

    // Consultando deptos en base a id_depto
    public function consultDepto($id) {
        $sql = "SELECT * FROM depto WHERE id_depto = $id"; 
        return $this -> cn() -> query($sql);
    } 

    // Filtrando deptos en base a nombre del depto
    public function filterDeptoName($filterName) {
        $sql = "SELECT * FROM depto WHERE depto like '%$filterName%'"; 
        return $this -> cn() -> query($sql);
    }

    // Actuzlizando depto
    public function update($id, $depto_name) {
        // Llamamos la funcion exists para comprobrar si se cambio el nombre de este departamento o sigue siendo el mismo 
        $result = $this -> exists($depto_name);
        $fila = $result -> fetch_assoc();
        
        if(mysqli_num_rows($result) == 1) {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            return "
            <script>
            $('#depto_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            alertRed();
            alerts('Ups, este nombre ya esta asignado a otro departamento.');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE depto SET depto =  '$depto_name' WHERE id_depto = $id";
            $this -> cn() -> query($sql);
            $_SESSION["depto"]["depto_name"] = $depto_name;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();
                    $('#depto_name').data('inicialValue', $('#depto_name').val())

                    hideAlert();
                    alertGreen();
                    setTimeout( function() { alerts('El nombre del departamento fue editado con exito a ($depto_name).');
                    }, 1);
                </script>
            ";
        } 
    }

    public function csvDeptos($file) {
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($file["type"], $mimes)) {

            move_uploaded_file($file["tmp_name"], $file["folder"]); 

            $deptos = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE) {
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $deptos[$encoded_row[0]] = $encoded_row;
                    }

                    $data_repits = 0;
                    foreach($deptos as $depto) {
                        if(mysqli_num_rows($this -> exists($depto[1])) == 0) {
                            $sql = "INSERT INTO depto (id_depto, depto) VALUES('$depto[0]', '$depto[1]')";
                            $result = $this -> cn() -> query($sql);
                        } else {
                            $data_repits++;
                        }
                    }
                }

                if($data_repits == 0) {
                    return "
                    <script>
                        $('#csv_deptos').val(null);
                        hideAlert();
                        alertGreen();
                        setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de departamentos fue insertada con exito.'); }, 1);
                    </script>
                    "; 
                } else {
                    return "
                    <script>
                        $('#csv_marcas').val(null);
                        hideAlert();
                        $('#alert').css('background-color', '#ffe979');
                        $('#alert').css('color', 'var(--color-dark)');
                        setTimeout( function() { alerts('El archivo csv ha sido leido, algunos departamentos se repiten por lo que solo se han agregado los que no se repiten (si todos se repiten la insercion no se llevara acabo).'); }, 1);
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