<?php
if(isset($_SESSION["unidad"])){
    unset($_SESSION["unidad"]);
}
?>

<div class="page" id="page" tabindex="3">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-unidad" class="page__card <?php if($_SESSION["unidades"] == "agregar-unidad") { echo "hide"; }?>">
        <img class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3rem !important' src="img/SVG/svgs/unit2.svg"></img>
            <div class="page__card-block">
                Agregar unidad
                <span>Crea un nuevo unidad</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-unidades" class="page__card">
            <img class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3rem !important' src="img/SVG/svgs/unit2.svg"></img>
            <div class="page__card-block">
                Administrar unidades
                <span>Administra unidads existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="form-container">
        <form class='inside-form'>
            <div class="form__division"></div>
            <h2 class='form__heading'>Crea una unidad</h2>
            <div class="form__division"></div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' autofocus id='unidad_name' class='form__input' placeholder='Nombre de la unidad'>
            </div>

            <div class='form__row'>
                <button type="button" id='create-unidad' class='form__btn no-margin'>
                    <span class="create-text">Crear unidad</span>
                    <svg class="btn-check hidden">
                        <use xlink:href="img/SVG/sprite.svg#check"></use>
                    </svg>
                </button>
            </div>

            <div class='form__row'>
                <span style="display: block; font-size: calc(var(--font-app) + .1rem); margin: 1rem  0; margin-top: 4rem">¿Ya tienes una coleccion de registros de unidades? Inserta tu archivo csv aqui.</span>

                <label class='form__input form__row-label no-padding' for='csv_unidades'>
                    <span style="color: var(--color-light); background-color: var(--color-dark);">Csv</span>
                    <div class='form__row-label-state'>Ingresa tu archivo csv (La inserción sera automatica una vez insertado)</div>
                    <input type='file' accept='text/csv' id='csv_unidades' class='hidden'>
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

<script src="js/admin/mantenimiento/unidades/agregar-unidad.js"></script>