<?php
require_once("php/controladores/cls_departamentos.php");
require_once("php/controladores/cls_marcas.php");
$obj_departamentos = new cls_departamentos;
$obj_marcas = new cls_marcas;

$deptos = $obj_departamentos -> consult();
$marcas = $obj_marcas -> consult();

if(isset($_SESSION["equipo"])){
    unset($_SESSION["equipo"]);
}
?>

<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-equipo" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#equipo"></use>
            </svg>
            <div class="page__card-block">
                Agregar material
                <span>Crea un nuevo material</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-equipos" class="page__card <?php if($_SESSION["equipos"] == "administrar-equipos") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#equipos"></use>
            </svg>
                <div class="page__card-block">
                Administrar materiales
                <span>Administra materiales nuevos</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Administra los materiales</h2>
        <div class="page__division"></div>

        <div class="page__filters">
        <input id="filterEquipoName" type="text" class="page__filter form__input" placeholder="Filtrar por nombre" style='flex: 1 1 40rem'>
            <?php
            $print = "";
            if(mysqli_num_rows($marcas) >= 1) {
                $print.= "
                <select id='filterEquipoMarca' required class='page__filter form__input' style='flex: 1 1 40rem'>
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
                <div class='page__filter' style='background-color: var(--color-wrong); padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: center'>
                <p>No existe ninguna marca para filtrar por marca.</p>
                </div>
                ";
            }

            if(mysqli_num_rows($deptos) >= 1) {
                $print.= "
                <select id='filterEquipoDepto' required class='page__filter form__input' style='flex: 1 1 40rem'>
                <option selected disabled value='0' class='disabled'>Filtrar por departamento</option>";
                foreach($deptos as $fila){
                    $print.= "<option value='$fila[id_depto]'>$fila[depto]</option>";
                }
                
                $print.="
                <option value='Todos'>Todos</option>
                </select>
                ";
            } else {
                $print.= "
                <div class='page__filter' style='background-color: var(--color-wrong); padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: center'>
                <p>No existe ningun departamento para filtrar por departamento.</p>
                </div>
                ";
            }
            
            echo $print;
            ?>
            <select id='filterEquipoModelo' required class='page__filter form__input' style='flex: 1 1 40rem'>
                <option selected disabled value='0' class='disabled'>Filtrar por tipo</option>";
                <option value='Retornable'>Retornable</option>
                <option value='No retornable'>No retornable</option>
                </select>
        </div>

        <div class="loading" id="loading" style="display: flex; justify-content: center;">
            <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
        </div>

        <div class='table-responsive' id="table-responsive">
            <!-- Aqui se coloca la tabla con ajax -->
        </div>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<script src="js/admin/mantenimiento/equipos/administra-materiales.js"></script>

<script>
    $(document).on({
        ajaxStart: function(){
            $("#loading").removeClass("hidden"); 
        },
        ajaxStop: function(){ 
            $("#loading").addClass("hidden"); 
        }    
    });
</script>