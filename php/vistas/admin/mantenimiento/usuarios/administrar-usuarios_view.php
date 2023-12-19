<?php
if(isset($_SESSION["usuario"])){
    unset($_SESSION["usuario"]);
}
?>

<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-usuario" class="page__card">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#person-circle"></use>
            </svg>
            <div class="page__card-block">
                Agregar cuenta de usuario
                <span>Crea una nueva cuenta de usuario</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-usuarios" class="page__card <?php if($_SESSION["usuarios"] == "administrar-usuarios") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#people-fill"></use>
            </svg>
                <div class="page__card-block">
                Administrar cuentas de usuario
                <span>Administra cuentas de usuarios existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Administra las cuentas de usuario</h2>
        <div class="page__division"></div>

        <div class="page__filters">
            <input id="filterUsuarioCarnet" type="text" class="page__filter form__input" style='flex: 1 1 40rem' placeholder="Filtrar por carnet">
            <input id="filterUsuarioEmail" type="text" class="page__filter form__input" style='flex: 1 1 40rem' placeholder="Filtrar por email">
            <input id="filterUsuarioNombres" type="text" class="page__filter form__input" style='flex: 1 1 40rem' placeholder="Filtrar por nombres">
            <input id="filterUsuarioApellidos" type="text" class="page__filter form__input" style='flex: 1 1 40rem' placeholder="Filtrar por apellidos">
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

<div id="modalFoto__overlay" class="details__overlay hidden" style='background: rgba(0, 0, 0, 0.5); position: fixed'></div>

<form class="user__modal user__modal-detail hidden" id="modal-foto-usuario" style='height: 80svh; position: fixed'>
    <button type="button" id="btnCloseFotoUsuario" class="alert__btn-close closing">&times;</button>
    <div class="user__detail">
        <h2 class="user__info-title" id="userSelectedName"></h2>
        <img id='userSelectedImage' src="" alt="Foto de uno de los usuarios" style="width: 80%; aspect-ratio: 1">
    </div>
</form>

<div id="resp"></div>

<script src="js/admin/mantenimiento/usuarios/administrar-usuarios.js"></script>

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