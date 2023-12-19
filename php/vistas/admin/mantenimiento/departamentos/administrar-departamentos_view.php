<?php
if(isset($_SESSION["depto"])){
    unset($_SESSION["depto"]);
}
?>

<div class="page">
    <div class="page__navigation">
        <a href="agregar-departamento" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#depto"></use>
            </svg>
            <div class="page__card-block">
                Agregar departamento
                <span>Crea un nuevo departamento</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="administrar-departamentos" class="page__card <?php if($_SESSION["departamentos"] == "administrar-departamentos") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#people-fill"></use>
            </svg>
                <div class="page__card-block">
                Administrar departamentos
            </div>
                <span>Administra departamentos existentes</span>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container">
        <div class="page__division"></div>
        <h2 class='page__heading'>Administra los departamentos</h2>
        <div class="page__division"></div>

        <div class="page__filters">
            <input id="filterDeptoName" type="text" class="page__filter form__input" placeholder="Filtrar por nombre">
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

<script src="js/admin/mantenimiento/departamentos/administrar-departamentos.js"></script>

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