<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>generar-pdf" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important; padding: .4rem'>
                <use xlink:href="img/SVG/sprite.svg#pdf"></use>
            </svg>
            <div class="page__card-block">
                Generar pdf de prestamos
                <span>Genera un pdf apartir del registro de prestamos</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>generar-excel" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important;'>
                <use xlink:href="img/SVG/sprite.svg#excel"></use>
            </svg>
                <div class="page__card-block">
                Generar excel de prestamos
                <span>Genera un excel apartir del registro de prestamos</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Registro de prestamos</h2>
        <div class="page__division"></div>

        <div class="page__filters">
            <div class="page__filter" style='flex: 1 1 40rem'>
                <label for="filterFechaDestino" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Fecha para la que se prestaron los materiales</label>
                <input id="filterFechaDestino" type="date" class="form__input">
            </div>
            <div class="page__filter" style='flex: 1 1 40rem'>
                <label for="filterFechaHecho" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Fecha en la que se realizo el prestamo</label>
                <input id="filterFechaHecho" type="date" class="form__input">
            </div>
            <input id="filterCarnet" type="text" class="page__filter form__input" style='flex: 1 1 40rem' placeholder="Filtrar por carnet de usuario que presto">
            <input id="filterEquipo" type="text" class="page__filter form__input" style='flex: 1 1 40rem' placeholder="Filtrar por nombre del material prestado">
        </div>

        <div class="loading" id="loading" style="display: flex; justify-content: center;">
            <img src="img/SVG/loading-spinner.svg" style="width: 5rem;" alt="loading spinner">
        </div>
        
        <div class="table-responsive" id="table-registro-prestamos">
                <!-- Aqui se coloca la tabla con ajax -->
        </div>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="modalFoto__overlay" class="details__overlay hidden" style='background: rgba(0, 0, 0, 0.5); position: fixed'></div>

<form class="user__modal user__modal-detail hidden" id="modal-foto-usuario" style='height: 80svh; position: fixed'>
    <button type="button" id="btnCloseFotoUsuario" class="alert__btn-close closing">&times;</button>
    <div class="user__detail">
        <h2 class="user__info-title" id="userSelectedName"></h2>
        <img id='userSelectedImage' src="" alt="Foto de uno de los usuarios" style="width: 80%; aspect-ratio: 1">
    </div>
</form>

<div id="resp"></div>

<script src="js/admin/registro/registro-prestamos.js"></script>

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
