<?php
if(isset($_SESSION["aula"])){
    unset($_SESSION["aula"]);
}
?>

<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-aula" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#aula"></use>
            </svg>
            <div class="page__card-block">
                Agregar aula
                <span>Crea una nueva aula</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-aulas" class="page__card <?php if($_SESSION["aulas"] == "administrar-aulas") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#people-fill"></use>
            </svg>
                <div class="page__card-block">
                Administrar aulas
                <span>Administra aulas existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Administra las aulas</h2>
        <div class="page__division"></div>

        <div class="page__filters">
            <input id="filterAulaName" type="text" class="page__filter form__input" placeholder="Filtrar por nombre">
            <select id="filterAulaType" class="page__filter form__input">
                <option disabled selected>Filtrar por tipo</option>
                <option value="0">Aula</option>
                <option value="1">Computo</option>
                <option value="Ambos">Ambos</option>
            </select>
        </div>

        <div class="loading" id="loading" style="display: flex; justify-content: center;">
            <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
        </div>

        <div class="table-responsive" id="table-responsive">

            <!-- Aqui se coloca la tabla con ajax -->
        </div>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<script src="js/admin/mantenimiento/aulas/administrar-aulas.js"></script>

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