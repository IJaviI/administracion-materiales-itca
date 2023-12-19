<?php
require_once("php/controladores/cn.php");
require_once("php/controladores/cls_aulas.php");
require_once("php/controladores/cls_marcas.php");
$cn = new cn;
$obj_aulas = new cls_aulas;
$obj_marcas = new cls_marcas;

$aulas = $obj_aulas -> consult();
$marcas = $obj_marcas -> consult();

if(isset($_SESSION["prestamo"])){
    unset($_SESSION["prestamo"]);
}

if(isset($_SESSION["nuestroPrestamo"])) {
    $prestamoActual = $_SESSION["nuestroPrestamo"];
    $sql = "SELECT * FROM prestamo WHERE id_prestamo = $prestamoActual";
    $result = $cn -> cn() -> query($sql);
    $estado = $result -> fetch_assoc();
}

if(isset($_SESSION["creating-prestamo"]) && $estado["estado"] == "En proceso") { 
    echo "
    <script>
    setTimeout( function() {
        $('#alert').css('background-color', '#ffe979');
        $('#alert').css('color', 'var(--color-dark)');
        $('#alert').removeClass('hidden');
        $('#alert__message').text('Tienes un prestamo iniciado, porfavor finalizalo o cancelalo para iniciar uno nuevo.');
     }, 1);
    </script>
    ";
}


?>

<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-aula" class="page__card <?php if($_SESSION["prestamos"] == "realizar-prestamo") { echo " hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#historial"></use>
            </svg>
            <div class="page__card-block">
                Realizar prestamo
                <span>Agenda un nuevo prestamo</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>historial-prestamos" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#historial-2"></use>
            </svg>
            <div class="page__card-block">
                Historial de prestamos
                <span>Revisa prestamos realiados pendientes o finalizados</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>

    <div id="iniciar_prestamo" class="form-container select-allowed <?php if($estado["estado"] == "Devuelto" || $estado["estado"] == "Cancelado"){ echo "show "; } if(isset($_SESSION["creating-prestamo"])){ echo "hidden"; }?>" style="padding: 0; padding-top: 4rem; margin-bottom: 2rem">
        <form class='inside-form'>
            <h2 class='form__heading'>Realiza tu prestamo</h2>
            <div class='form__row' style="padding-bottom: 4rem;">
            <label for="fecha_destino" class='page__heading' style="display: block; font-size: calc(var(--font-app) + .1rem); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Fecha en la que se prestaran los materiales</label>
            <input id="fecha_destino" type="date" class="page__filter form__input" style="margin-bottom: 1rem;">
            <?php
                $print = "";
                if(isset($_SESSION["userProfile"]["depto"]) && $_SESSION["userProfile"]["depto"] != "") {
                    if(mysqli_num_rows($aulas) >= 1) {
                        $print.= "
                        <label for='prestamoAula' class='page__heading' style='display: block; font-size: calc(var(--font-app) + .1rem); text-align: left; margin-left: .3rem; margin-bottom: .5rem'>Aula o computo en el que se tendran los materiales</label>
                        <select id='prestamoAula' required>";
                            foreach($aulas as $fila){
                                if($fila["tipo"] == 0) {
                                    $tipo = 'Aula';
                                } else if($fila["tipo"] == 1) {
                                    $tipo = 'Computo';
                                }

                                $print.= "<option value='$fila[id_aula]'>$tipo $fila[aula]</option>";
                            }

                            $print.="
                        </select>
                        <button type='button' id='create-prestamo' class='form__btn no-margin' style='margin-top: 1.5rem !important'>Abrir nuevo prestamo</button>
                        ";
                    } else {
                        $print.= "
                        <div class='warning-depto' style='margin-bottom: 1rem'>
                            <p>No existe ninguna aula para asignar al prestamo.</p>
                        </div>
                        ";
                    }
                } else {
                    $print.= "
                    <div class='warning-depto' style='margin-bottom: 1rem'>
                        <p>Debes pertenecer a un departamento para realizar un prestamo, edita tu informacion personal y agrega el departamento al que perteneces en \"Configuración\".</p>
                    </div>
                    ";
                }

                echo $print;
                ?>
            </div>
        </form>
    </div>

    <div id="agregar__equipos" class="table-container select-allowed <?php if(!isset($_SESSION["creating-prestamo"]) || ($estado["estado"] == "Devuelto" || $estado["estado"] == "Cancelado")){ echo "hidden"; }?>">
        <div class="page__division" sty></div>
        <h2 class='page__heading' style="font-size: calc(var(--font-app) + 1rem);">Agrega materiales al prestamo</h2>
        <div class="page__division" style="margin-bottom: 4rem;"></div> 


        <div class="page__filters">
            <input id="filterEquipoName" type="text" class="page__filter form__input" placeholder="Filtrar por nombre">
            <?php
            $print = "";
            if(mysqli_num_rows($marcas) >= 1) {
                $print.= "
                <select id='filterEquipoMarca' required class='page__filter form__input'>
                <option selected disabled value='0' class='disabled'>Filtrar por marca</option>";
                foreach($marcas as $fila){
                    $print.= "<option value='$fila[id_marca]'>$fila[marca]</option>";
                }
                
                $print.="
                <option value='Todas'>Todas</option>
                </select>
                ";
            } else {
                $print.= "
                <div class='page__filter' style='color: var(--color-light); background-color: var(--color-wrong); padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: center'>
                <p>No existe ninguna marca para filtrar por marca.</p>
                </div>
                ";
            }
            
            echo $print;
            ?>
            <input id="filterEquipoModelo" type="text" class="page__filter form__input" placeholder="Filtrar por modelo">
        </div>

        <div class="loading" id="loading1" style="display: flex; justify-content: center;">
            <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
        </div>
        
        <div class="table-responsive" id="table-equipos">
            <!-- Aqui se coloca la tabla con ajax -->
        </div>
    </div>

    <div id="equipos_agregados" class="table-container select-allowed <?php if(!isset($_SESSION["creating-prestamo"]) || ($estado["estado"] == "Devuelto" || $estado["estado"] == "Cancelado")){ echo "hidden"; }?>" style="margin-top: 2rem">
        <div class="page__division" sty></div>
        <h2 class='page__heading' style="font-size: calc(var(--font-app) + 1rem);">Materiales agregados al prestamo</h2>
        <div class="page__division" style="margin-bottom: 4rem;"></div> 

        <div class="loading" id="loading2" style="display: flex; justify-content: center;">
            <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
        </div>
        
        <div class="table-responsive" id="table-equipos-agregados">
            <!-- Aqui se coloca la tabla con ajax -->
        </div>
        <div class='table-container__btns'>
            <button id='cancel-prestamo-option1' class='table-container__btn table-container__btn-cancel'>Cancelar prestamo</button>
            <button id='finalizar-prestamo' class='table-container__btn table-container__btn-end'>Finalizar prestamo</button>
        </div>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp" ></div>

<script src="js/admin_and_user/realizar-prestamos.js"></script>

<script>
    $('#prestamoAula').selectize({
        sortField: 'text'
    });
    
    $(document).on({
        ajaxStart: function(){
            $("#loading1").removeClass("hidden");
            $("#loading2").removeClass("hidden");
        },
        ajaxStop: function(){ 
            $("#loading1").addClass("hidden"); 
            $("#loading2").addClass("hidden"); 
        }    
    });
    
    function mostrarTablaAgain() {
        const Obj = {};
        Obj.filterEquipoName = $('#filterEquipoName').val();
        Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
        Obj.filterEquipoModelo = $('#filterEquipoModelo').val();

        $.ajax({
            url: "php/ajax/ajax_prestamo.php?" + $.param(Obj), success: function (respuesta) {
                $("#table-equipos").html(respuesta);
            }
        });
    }
</script>
