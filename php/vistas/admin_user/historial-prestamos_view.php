<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>realizar-prestamo" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
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

        <a href="<?php echo RUTA;?>historial-prestamos" class="page__card <?php if($_SESSION["prestamos"] == "historial-prestamos") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
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
    <div class="table-container select-allowed">
        <form class='inside-form'>
            <div class="form__division"></div>
            <h2 class='form__heading'>Historial de tus prestamos</h2>
            <div class="form__division"></div>
            
            <div class="loading" id="loading" style="display: flex; justify-content: center;">
                <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
            </div>
            
            <div class="table-responsive" id="table-historial-prestamos">
                <!-- Aqui se coloca la tabla con ajax -->
            </div>
        </form>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp" ></div>  

<script src="js/admin_and_user/historial-prestamos.js"></script>

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