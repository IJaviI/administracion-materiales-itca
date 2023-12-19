<?php
require_once("php/controladores/cls_usuarios.php");
$obj_usuarios = new cls_usuarios();


// Sabiendo si hay un usuario o no (para mostrar formulario de creacion de admin si no existe ningun usuario)
$result = $obj_usuarios -> consult();
?>

<div id="access" class='access-overlay'>
    <?php
    if(mysqli_num_rows($result) >= 1){
        echo "
        <form class='form log-in'>
            <div class='form__row'>
                <img src='img/logo blaco ITCA - FEPADE.png' alt='Logo de itca' class='form__logo'>    
            </div>

            <div class='form__inside'>
                <h2 class='form__heading'>Inicia sesión para acceder</h2>
                <div class='form__row'>
                    <input class='form__input' style='border: 3px solid #3e3e3e' required autofocus type='email' id='email_login' placeholder='Email'>
                </div>
                
                <div class='form__row'>
                    <input class='form__input' style='border: 3px solid #3e3e3e' autocomplete='off' required type='password' id='password_login' placeholder='Contraseña'>
                </div>
            
                <div class='form__row'>
                    <button type='button' class='form__btn' id='login'>Iniciar sesión</button>
                </div>

                <div class='form__row hidden'>
                    <div class='form__banner'>
                        <button type='button' id='close-banner' class='close-banner'>&times;</button>
                        <p class='form__warning'>Usuario o contraseña incorrectos.</p>
                    </div>
                </div>
            </div>
        </form>

        <script>
        // When clicking the key 'Enter' when writing on the input
        addEventListener('keydown', function(e){            
            if(e.key == 'Enter') {
                validateLogin();
            }
        });
        </script>
        ";        
    } else {
        echo "
        <form class='form' style='flex: 0 1 58rem;'>
            <div class='form__row'>
                <img src='img/logo blaco ITCA - FEPADE.png' alt='Logo de itca' class='form__logo'>    
            </div>
            
            <div class='form__inside'>
                <h2 class='form__heading'>Crea la cuenta de administrador para acceder a 'prestamos ITCA...'</h2>
                <div class='form__row'>
                    <input type='number' id='usuario_carnet' required style='-webkit-appearance: none; border: 3px solid #3e3e3e' autofocus class='form__input' placeholder='Carnet'>
                </div>

                <div class='form__row'>
                    <input type='text' required id='usuario_names' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Nombres'>
                </div>

                <div class='form__row'>
                    <input type='text' required id='usuario_lastnames' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Apellidos'>
                </div>

                <div class='form__row'>
                    <select id='usuario_type' required class='form__input form__select' style='border: 3px solid #3e3e3e'>
                        <option disabled selected value='-1' class='disabled'>Cargo</option>
                        <option value='Ingeniero'>Ingeniero</option>
                        <option value='Licenciado/a'>Licenciado/a</option>
                        <option value='Tecnico'>Tecnico</option>
                    </select>
                </div>

                <div class='form__row'>
                    <input type='tel' id='usuario_house-phone' required pattern='[0-9]{4}-[0-9]{4}' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Telefono de casa'>
                </div>

                <div class='form__row'>
                    <input type='tel' id='usuario_phone' required pattern='[0-9]{4}-[0-9]{4}' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Celular'>
                </div>
                
                <div class='form__row'>
                    <input type='email' required id='usuario_email' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Email'>
                </div>
                
                <div class='form__row'>
                    <input type='password' autocomplete='off' required id='usuario_password' pattern='^\S{6,}$' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Contraseña'>
                </div>
                
                <div class='form__row'>
                    <input type='password' autocomplete='off' required pattern='^\S{6,}$' id='usuario_passwordRe' class='form__input' style='border: 3px solid #3e3e3e' placeholder='Confirmar contraseña'>
                </div>

                <div class='form__row'>
                    <label class='form__input form__row-label no-padding' for='admin_image' style='border: 3px solid #3e3e3e'>
                        <span>Imagen</span>
                        <div class='form__row-label-state'>Seleccione la imagen del usuario (opcional)</div>
                        <input type='file' accept='image/*' id='admin_image' class='hidden'>
                    </label>
                </div>

                <div class='form__row'>
                    <button type='button' id='admin-account' class='form__btn no-margin'>Crear cuenta de administrador</button>
                </div>
            </div>
        </form>

        <script>
        // Inputs de la creacion del admin 
        const inputCarnet = document.querySelector('#usuario_carnet');
        const inputNames = document.querySelector('#usuario_names');
        const inputLastNames = document.querySelector('#usuario_lastnames');
        const inputType = document.querySelector('usuario_type');
        const inputHousePhone = document.querySelector('#usuario_house-phone');
        const inputPhone = document.querySelector('#usuario_phone');
        const inputEmail = document.querySelector('#usuario_email');
        const inputPassword = document.querySelector('#usuario_password');
        const inputPasswordRe = document.querySelector('#usuario_passwordRe');
        
        // AVoiding user writing numbers on Names's input
        inputNames.addEventListener('keydown', function(event){
            if((/\d/g).test(event.key)) event.preventDefault();
        });
        
        // AVoiding user writing numbers on LastNames's input
        inputLastNames.addEventListener('keydown', function(event){
            if((/\d/g).test(event.key)) event.preventDefault();
        });
        
        // When clicking the key 'Enter' when writing on the input
        addEventListener('keydown', function(e){            
            if(e.key == 'Enter') {
                validateAdmin();
            }
        });
        </script>
        ";
    }
    ?>
</div>


<div id="alert" class="alert hidden" style="position: fixed; top: 10px;">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp"></div>

<script src="js/accessForm.js"></script>


<!-- <form method='post' class='form restore hidden'>
            <div class='form__row'>
                <img src='img/logo-itca.png' alt='Logo de itca' class='form__logo'>    
            </div>
            
            <div class='form__inside'>
                <h2 class='form__heading'>Restablece tu contraseña para acceder a 'prestamos ITCA...'</h2>
                <div class='form__row'>
                    <input class='form__input' required type='email' name='email_restore' id='restoreEmail' placeholder='Email'>
                </div>
            
                <div class='form__row'>
                    <input class='form__input' required type='text' name='carnet_restore' id='restoreCarnet' placeholder='Carnet'>
                </div>

                <div class='form__row'>
                    <input class='form__input' required type='tel' name='telefono_restore' id='inputPassword' pattern='[0-9]{4}-[0-9]{4}' onchange='this.setCustomValidity(this.validity.patternMismatch ? 'Debe contener 8 digitos separados por (-) ejemplo: 6745-2565' : '');' placeholder='Celular'>
                </div>
            
                <div class='form__row'>
                    <button type='button' class='form__btn' id='askRestore' style='border: 2px solid var(--color-help);'>Solicitar restablecer</button>
                    <p>Regresar a <button type='button' class='form__link'>Iniciar sesion</button></p>
                </div>
            </div>
        </form> -->