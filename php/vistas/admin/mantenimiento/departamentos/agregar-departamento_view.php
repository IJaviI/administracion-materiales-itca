<?php
if(isset($_SESSION["depto"])){
    unset($_SESSION["depto"]);
}
?>

<div class="page" id="page" tabindex="3">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-departamento" class="page__card <?php if($_SESSION["departamentos"] == "agregar-departamento") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/newsprite.svg#person-circle"></use>
            </svg>
            <div class="page__card-block">
                Agregar departamento
                <span>Crea un nuevo departamento</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-departamentos" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#deptos"></use>
            </svg>
            <div class="page__card-block">
                Administrar departamentos
                <span>Administra departamentos existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="form-container">
        <form class='inside-form'>
            <div class="form__division"></div>
            <h2 class='form__heading'>Crea un departamento</h2>
            <div class="form__division"></div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' autofocus id='depto_name' class='form__input' placeholder='Nombre del departamento'>
            </div>

            <div class='form__row'>
                <button type="button" id='create-depto' class='form__btn no-margin'>
                    <span class="create-text">Crear departamento</span>
                    <svg class="btn-check hidden">
                        <use xlink:href="img/SVG/sprite.svg#check"></use>
                    </svg>
                </button>
            </div>

            <div class='form__row'>
                <span style="display: block; font-size: calc(var(--font-app) + .1rem); margin: 1rem  0; margin-top: 4rem">¿Ya tienes una coleccion de registros de departamentos? Inserta tu archivo csv aqui.</span>

                <label class='form__input form__row-label no-padding' for='csv_deptos'>
                    <span style="color: var(--color-light); background-color: var(--color-dark);">Csv</span>
                    <div class='form__row-label-state'>Ingresa tu archivo csv (La inserción sera automatica una vez insertado)</div>
                    <input type='file' accept='text/csv' id='csv_deptos' class='hidden'>
                </label>
            </div>
        </form>
    </div>
</div>



<!-- Alertas -->
<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<!-- Resivimos javascript como respuesta del ajax -->
<div id="resp" ></div>

<script src="js/admin/mantenimiento/departamentos/agregar-departamentos.js"></script>