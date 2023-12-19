<?php
if(isset($_SESSION["unidad"])){
    unset($_SESSION["unidad"]);
}
?>

<div class="page">
    <div class="page__navigation">
        <a href="agregar-unidad" class="page__card">
        <img class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3rem !important' src="img/SVG/svgs/unit2.svg"></img>
            <div class="page__card-block">
                Agregar unidad
                <span>Crea una nueva unidad</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="administrar-unidades" class="page__card <?php if($_SESSION["unidades"] == "administrar-unidades") { echo "hide"; }?>">
        <img class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3rem !important' src="img/SVG/svgs/unit2.svg"></img>
                <div class="page__card-block">
                Administrar unidades
            </div>
                <span>Administra unidades existentes</span>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container">
        <div class="page__division"></div>
        <h2 class='page__heading'>Administra los unidades</h2>
        <div class="page__division"></div>

        <div class="page__filters">
            <input id="filterUnidadName" type="text" class="page__filter form__input" placeholder="Filtrar por nombre">
        </div>

        <div class="loading" id="loading" style="display: flex; justify-content: center;">
            <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
        </div>

        <div class='table-responsive' id="table-responsive">            
            <!-- Aqui se colocan las filas con ajax -->
        </div>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<script src="js/admin/mantenimiento/unidades/administrar-unidad.js"></script>

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