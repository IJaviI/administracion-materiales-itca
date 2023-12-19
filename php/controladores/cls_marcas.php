<?php
require_once("cn.php");

class cls_marcas extends cn {
    public function consult() {
        $sql = "SELECT * FROM marca"; 
        return $this -> cn() -> query($sql);
    } 

        // Sabemos si hay equipos pertenecientes a la marca
        public function equipoAfiliatedOrNot($id) {
            $sql = "SELECT * FROM equipo WHERE id_marca = $id";
            return $this -> cn() -> query($sql);
        }

    public function delete($id) {
        $marca = $this -> consultMarca($id);
        $marcaData = $marca -> fetch_assoc();

        // verificamos si hay docentes o equipos pertenecientes a la marca (solo si no hay, es posible borrar)
        if(mysqli_num_rows($this -> equipoAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, existen equipos que pertenecen a la marca ($marcaData[marca]).'); }, 1);
            </script>";
        } else {
            $sql = "DELETE FROM marca WHERE id_marca = $id"; 
            $this -> cn() -> query($sql);
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('La marca ($marcaData[marca]) fue eliminada con exito.'); }, 1);
            </script>"; 
        }
    }

    public function exists($marca_name) {
        $sql = "SELECT marca FROM marca WHERE marca = '$marca_name'"; 
        return $this -> cn() -> query($sql);
    }

    public function insert($marca_name) {
        // Llamamos la funcion exists para comprobrar si existe esta marca 
        $result = $this -> exists($marca_name);
        if(mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO marca (marca) VALUES ('$marca_name')"; 
            $this -> cn() -> query($sql);

            // Cambiamos el estado del boton
            return "
            <script>
                btnStateChange();

                alertGreen();
                setTimeout( function() { alerts('La marca ($marca_name) fue creada con exito.'); }, 10);
            </script>
            ";
        } else {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            $marcaExists = $result -> fetch_assoc();
            $marca = $marcaExists["marca"];
            return "
            <script>
            $('#marca_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Ups, la marca ($marca) ya existe.'); }, 1);
            </script>
            ";
        }

    }

    // Consultando marcas en base a id_marca
    public function consultMarca($id) {
        $sql = "SELECT * FROM marca WHERE id_marca = $id"; 
        return $this -> cn() -> query($sql);
    } 

    // Filtrando marcas en base a nombre de la marca
    public function filterMarcaName($filterName) {
        $sql = "SELECT * FROM marca WHERE marca like '%$filterName%'"; 
        return $this -> cn() -> query($sql);
    }

    // Actualizando marca
    public function update($id, $marca_name) {
        // Llamamos la funcion exists para comprobrar si se cambio el nombre de esta marca o sigue siendo el mismo 
        $result = $this -> exists($marca_name);
        $fila = $result -> fetch_assoc();

        if(mysqli_num_rows($result) == 1) {
            // Devolvemos el boton a su estado inicial y hacemos el input rojo
            return "
            <script>
            $('#depto_name').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            alertRed();
            alerts('Ups, este nombre ya esta asignado a otra marca.');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE marca SET marca =  '$marca_name' WHERE id_marca = $id";
            $this -> cn() -> query($sql);
            $_SESSION["marca"]["marca_name"] = $marca_name;
            
            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();
                    $('#marca_name').data('inicialValue', $('#marca_name').val());

                    alertGreen();
                    alerts('El nombre de la marca fue editado con exito a ($marca_name).');
                </script>
            ";
        } 
    }

    public function csvMarcas($file) {
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($file["type"], $mimes)) {

            move_uploaded_file($file["tmp_name"], $file["folder"]);

            $marcas = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE){
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $marcas[$encoded_row[0]] = $encoded_row;

                        // Another way of inserting the csv rows
                        // $inserting = $this -> cn() -> prepare("INSERT INTO marca (marca) VALUES (?)");
                        // $inserting -> bind_param("s", $row[1]);
                        // $inserting -> execute();
                    }

                    $data_repits = 0; 
                    foreach($marcas as $marca){
                        if(mysqli_num_rows($this -> exists($marca[1])) == 0) {
                            $sql = "INSERT INTO marca (id_marca, marca) VALUES ('$marca[0]', '$marca[1]')";
                            $result = $this -> cn() -> query($sql);
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
                            setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de marcas fue insertada con exito.'); }, 1);
                        </script>
                        "; 
                    } else {
                        return "
                        <script>
                            $('#csv_marcas').val(null);
                            hideAlert();
                            $('#alert').css('background-color', '#ffe979');
                            $('#alert').css('color', 'var(--color-dark)');
                            setTimeout( function() { alerts('El archivo csv ha sido leido, algunas marcas se repiten por lo que solo se han agregado las que no se repiten (si todas se repiten la insercion no se llevara acabo).'); }, 1);
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