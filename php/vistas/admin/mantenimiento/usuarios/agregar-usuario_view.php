<?php
if(isset($_SESSION["usuario"])){
    unset($_SESSION["usuario"]);
}

require_once("php/controladores/cls_departamentos.php");
$obj_departamentos = new cls_departamentos();
?>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>

<div class="page" id="page" tabindex="2">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-usuario" class="page__card <?php if($_SESSION["usuarios"] == "agregar-usuario") { echo "hide"; }?>">
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

        <a href="<?php echo RUTA;?>administrar-usuarios" class="page__card">
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
    <div class="form-container">
        <form class='inside-form select-allowed' method="post" enctype="multipart/form-data">
            <div class="form__division"></div>
            <h2 class='form__heading'>Crea una cuenta de usuario</h2>
            <div class="form__division"></div>

            <div class='form__row'>
                <input type='number' id='usuario_carnet1' autofocus required class='form__input' placeholder='Carnet'>
            </div>

            <div class='form__row'>
                <input type='text' required id='usuario_names1' class='form__input' placeholder='Nombres'>
            </div>

            <div class='form__row'>
                <input type='text' required id='usuario_lastnames1' class='form__input' placeholder='Apellidos'>
            </div>

            <div class='form__row'>
                <select id='usuario_type1' required class='form__input form__select'>
                    <option selected value='0' class='disabled'>Cargo</option>
                    <option value='Ingeniero'>Ingeniero</option>
                    <option value='Licenciado/a'>Licenciado/a</option>
                    <option value='Tecnico'>Tecnico</option>
                    <option value='Estudiante'>Estudiante</option>
                </select>
            </div>

            <div class='form__row'>
                <select id='usuario_isAdmin1' required class='form__input form__select'>
                    <option selected value='-1' class='disabled'>Tipo de usuario</option>
                    <option value='0'>No admin</option>
                    <option value='1'>Admin</option>
                </select>
            </div>

            <div class='form__row'>
                <input type='tel' id='usuario_house-phone1' required pattern='[0-9]{4}-[0-9]{4}' class='form__input' placeholder='Telefono de casa'>
            </div>

            <div class='form__row'>
                <input type='tel' id='usuario_phone1' required pattern='[0-9]{4}-[0-9]{4}' class='form__input' placeholder='Celular'>
            </div>
            
            <div class='form__row'>
                <input type='email' required id='usuario_email1' class='form__input' placeholder='Email'>
            </div>
            
            <div class='form__row'>
                <input type='text' required id='usuario_password1' autocomplete="off"  pattern='^\S{6,}$' class='form__input' placeholder='Contraseña'>
            </div>
            
            <div class='form__row'>
                <input type='text' required autocomplete="off"  pattern='^\S{6,}$' id='usuario_passwordRe1' class='form__input' placeholder='Confirmar contraseña'>
            </div>

            <div class='form__row'>
                <label class='form__input form__row-label no-padding' for='user_image1'>
                    <span>Imagen</span>
                    <div class='form__row-label-state'>Seleccione la imagen del usuario (opcional)</div>
                    <input type='file' accept='image/*' id='user_image1' class='hidden'>
                </label>
            </div>

            <?php
            $isThereDeptos = $obj_departamentos -> consult();
            $print = "";
            if(mysqli_num_rows($isThereDeptos) >= 1) {
                $print.= "
                <div class='form__row'>
                    <select id='usuario_depto1' required class='form__input form__select'>
                        <option selected value='0' class='disabled'>Departamento</option>";
                        $deptos = $obj_departamentos -> consult();
                        foreach($deptos as $fila){
                            $print.= "<option value='$fila[id_depto]'>$fila[depto]</option>";
                        }

                        $print.="
                    </select>
                </div>
                
                <div class='form__row'>
                    <button id='user-account' class='form__btn no-margin'>Crear cuenta de usuario</button>
                </div>

                <div class='form__row'>
                    <span style='display: block; font-size: calc(var(--font-app) + .1rem); margin: 1rem  0; margin-top: 4rem'>¿Ya tienes una coleccion de registros de usuarios? Inserta tu archivo csv aqui.</span>

                    <label class='form__input form__row-label no-padding' for='csv_usuarios'>
                        <span style='color: var(--color-light); background-color: var(--color-dark);'>Csv</span>
                        <div class='form__row-label-state'>Ingresa tu archivo csv (La inserción sera automatica una vez insertado)</div>
                        <input type='file' accept='text/csv' id='csv_usuarios' class='hidden'>
                    </label>
                </div>
                ";
            } else {
                $print.= "
                <div class='form__row'>
                    <div class='warning-depto'>
                        <p>No existe ningun departamento para asignar a este usuario, la creacion del usuario solo sera posible hasta que exista minimo un departamento.</p>
                    </div>
                </div>
                ";
            }

            echo $print;
            ?>
        </form>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp"></div>

<script src="js/admin/mantenimiento/usuarios/agregar-usuario.js"></script>