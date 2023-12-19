<?php
if(isset($_SESSION["marca"])){
    unset($_SESSION["marca"]);
}
?>

<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-marca" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#marca"></use>
            </svg>
            <div class="page__card-block">
                Agregar marca
                <span>Crea una nueva marca</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-marcas" class="page__card <?php if($_SESSION["marcas"] == "administrar-marcas") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#people-fill"></use>
            </svg>
                <div class="page__card-block">
                Administrar marcas
                <span>Administra marcas existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Administra las marcas</h2>
        <div class="page__division"></div>

        <div class="page__filters">
            <input id="filterMarcaName" type="text" class="page__filter form__input" placeholder="Filtrar por nombre">
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

<script src="js/admin/mantenimiento/marcas/administrar-marcas.js"></script>

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