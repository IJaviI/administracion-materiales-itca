<?php
if(isset($_SESSION["aula"])){
    unset($_SESSION["aula"]);
}
?>

<div class="page" id="page" tabindex="3">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-aula" class="page__card <?php if($_SESSION["aulas"] == "agregar-aula") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#aula"></use>
            </svg>
            <div class="page__card-block">
                Agregar marca
                <span>Crea una nueva marca</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-aulas" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#aulas"></use>
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
    <div class="form-container select-allowed">
        <form class='inside-form'>
            <div class="form__division"></div>
            <h2 class='form__heading'>Crea un aula o computo</h2>
            <div class="form__division"></div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' min="0" id='aula_name' autofocus class='form__input' placeholder='Nombre del aula o computo'>
            </div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' id='aula_ubication' class='form__input' placeholder='Ubicacion'>
            </div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' id='aula_description' class='form__input' placeholder='Descripcion del aula'>
            </div>
            <div class='form__row'>
                <select id="aula_type" class="page__filter form__input">
                    <option disabled selected value="-1">Tipo</option>
                    <option value="0">Aula</option>
                    <option value="1">Computo</option>
                </select>
            </div>
            <div class='form__row'>
                <button type="button" id='create-aula' class='form__btn no-margin'>
                    <span class="create-text">Crear aula</span>
                    <svg class="btn-check hidden">
                        <use xlink:href="img/SVG/sprite.svg#check"></use>
                    </svg>
                </button>
            </div>

            <div class='form__row'>
                <span style="display: block; font-size: calc(var(--font-app) + .1rem); margin: 1rem  0; margin-top: 4rem">¿Ya tienes una coleccion de registros de aulas? Inserta tu archivo csv aqui.</span>

                <label class='form__input form__row-label no-padding' for='csv_aulas'>
                    <span style="color: var(--color-light); background-color: var(--color-dark);">Csv</span>
                    <div class='form__row-label-state'>Ingresa tu archivo csv (La inserción sera automatica una vez insertado)</div>
                    <input type='file' accept='text/csv' id='csv_aulas' class='hidden'>
                </label>
            </div>
        </form>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp" ></div>

<script src="js/admin/mantenimiento/aulas/agregar-aulas.js"></script>