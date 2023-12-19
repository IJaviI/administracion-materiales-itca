// Menu para editar info del perfil de usuario

// Recopilando inputs para el check validity 
const inputCarnet = document.querySelector('#usuario_carnet');
const inputNames = document.querySelector('#usuario_names');
const inputLastNames = document.querySelector('#usuario_lastnames');
const inputType = document.querySelector('usuario_type');
const inputHousePhone = document.querySelector('#usuario_house-phone');
const inputPhone = document.querySelector('#usuario_phone');
const inputEmail = document.querySelector('#usuario_email');
const inputPasswordLast = document.querySelector('#usuario_passwordLast');
const inputPassword = document.querySelector('#usuario_password');
const inputPasswordRe = document.querySelector('#usuario_passwordRe');

$(document).ready(function () {
    // Editar carnet
    $('#carnetProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#carnet-modal').removeClass('hidden');
        $('#usuario_carnet').val("");
        $('#usuario_carnet').css("borderColor", "var(--color-border)");
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseCarnet').mousedown(function () {
        $('#carnet-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editCarnet').mousedown(function () {
        if ($('#usuario_carnet').val() == "") {
            $('#usuario_carnet').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de rellenar el carnet.'); }, 1);
        } else if (containsSpecialChars($('#usuario_carnet').val())) {
            $('#usuario_carnet').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, el carnet no puede contener caracteres especiales.'); }, 1);
        } else {
            const obj = {};
            obj.editCarnet = $('#usuario_carnet').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar nombres
    $('#namesProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#names-modal').removeClass('hidden');
        $('#usuario_names').val("");
        $('#usuario_names').css("borderColor", "var(--color-border)");
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseNames').mousedown(function () {
        $('#names-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editNames').mousedown(function () {
        if ($('#usuario_names').val() == "") {
            $('#usuario_names').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de rellenar los nombres.'); }, 1);
        } else if (containsSpecialChars($('#usuario_names').val())) {
            $('#usuario_names').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, los nombres no pueden contener ningun caracter especial.'); }, 1);
        } else if (!inputNames.checkValidity()) {
            $('#usuario_names').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de ingresar dos nombres divididos por un espacio.'); }, 1);
        } else {
            const obj = {};
            obj.editNames = $('#usuario_names').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar apellidos
    $('#lastnamesProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#lastnames-modal').removeClass('hidden');
        $('#usuario_lastnames').val('');
        $('#usuario_lastnames').css("borderColor", "var(--color-border)");
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseLastNames').mousedown(function () {
        $('#lastnames-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editLastNames').mousedown(function () {
        if ($('#usuario_lastnames').val() == "") {
            $('#usuario_lastnames').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de rellenar los apellidos.'); }, 1);
        } else if (containsSpecialCharsGuion($('#usuario_lastnames').val())) {
            $('#usuario_lastnames').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, los apellidos no pueden contener ningun caracter especial.'); }, 1);
        } else if (!inputLastNames.checkValidity()) {
            $('#usuario_lastnames').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de ingresar dos apellidos divididos por un espacio.'); }, 1);
        } else {
            const obj = {};
            obj.editLastNames = $('#usuario_lastnames').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar email
    $('#emailProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#email-modal').removeClass('hidden');
        $('#usuario_email').val('');
        $('#usuario_email').css("borderColor", "var(--color-border)");
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseEmail').mousedown(function () {
        $('#email-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editEmail').mousedown(function () {
        if ($("#usuario_email").val() == "") {
            $('#usuario_email').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de ingresar un email.'); }, 1);
        } else if (!inputEmail.checkValidity()) {
            $('#usuario_email').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, ingrese un email valido (asegurese que no contenga espacios), ejemplo: ejemplo@itca.edu.sv'); }, 1);
        } else {
            const obj = {};
            obj.editEmail = $('#usuario_email').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar telefono de casa
    $('#housePhoneProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#housePhone-modal').removeClass('hidden');
        $('#usuario_house-phone').val('');
        $('#usuario_house-phone').css("borderColor", "var(--color-border)");
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseHousePhone').mousedown(function () {
        $('#housePhone-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editHousePhone').mousedown(function () {
        if ($("#usuario_house-phone").val() == "") {
            $('#usuario_house-phone').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de ingresar un telefono de casa.'); }, 1);
        } else if (containsSpecialCharsGuion($('#usuario_house-phone').val())) {
            $('#usuario_house-phone').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, el telefono de casa no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
        } else if (!inputHousePhone.checkValidity()) {
            $('#usuario_house-phone').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, telefono de casa no puede contener letras y debe contener 8 digitos separados por (-) ejemplo: 4454-3423.'); }, 1);
        } else {
            const obj = {};
            obj.editHousePhone = $('#usuario_house-phone').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar celular
    $('#phoneProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#phone-modal').removeClass('hidden');
        $('#usuario_phone').val('');
        $('#usuario_phone').css("borderColor", "var(--color-border)");
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnClosePhone').mousedown(function () {
        $('#phone-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editPhone').mousedown(function () {
        if ($("#usuario_phone").val() == "") {
            $('#usuario_phone').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de ingresar un celular.'); }, 1);
        } else if (containsSpecialCharsGuion($('#usuario_phone').val())) {
            $('#usuario_phone').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, el celular no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
        } else if (!inputPhone.checkValidity()) {
            $('#usuario_phone').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, celular no puede contener letras y debe contener 8 digitos separados por (-) ejemplo: 6745-2565.'); }, 1);
        } else {
            const obj = {};
            obj.editPhone = $('#usuario_phone').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar cargo
    $('#typeProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#type-modal').removeClass('hidden');
        $('#usuario_type').val('0');
        $('#usuario_type').css('borderColor', 'var(--color-border)');
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseType').mousedown(function () {
        $('#type-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editType').mousedown(function () {
        if (!$('#usuario_type').val()) {
            $('#usuario_type').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, asegurese de seleccionar un cargo.'); }, 1);
        } else {
            const obj = {};
            obj.editCargo = $('#usuario_type').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar departamento
    $('#deptoProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#depto-modal').removeClass('hidden');
        $('#usuarioDepto').val('0');
        $('#usuarioDepto').css('borderColor', 'var(--color-border)');
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnCloseDepto').mousedown(function () {
        $('#depto-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editDepto').mousedown(function () {
        if (!$("#usuarioDepto").val()) {
            $('#usuarioDepto').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, seleccione un departamento.'); }, 0);
        } else {
            const obj = {};
            obj.editDepto = $('#usuarioDepto').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    // Editar contraseña
    $('#passwordProfile').mousedown(function () {
        $('#user-modal').addClass('hidden');
        $('#password-modal').removeClass('hidden');
        $('#usuario_passwordLast').val('');
        $('#usuario_passwordLast').css('borderColor', 'var(--color-border)');
        $('#usuario_password').val('');
        $('#usuario_password').css('borderColor', 'var(--color-border)');
        $('#usuario_passwordRe').val('');
        $('#usuario_passwordRe').css('borderColor', 'var(--color-border)');
        $('#details__overlay').removeClass('hidden');
        hideAlert2();
    });

    $('#btnClosePassword').mousedown(function () {
        $('#password-modal').addClass('hidden');
        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });

    $('#editPassword').mousedown(function () {
        if ($("#usuario_passwordLast").val() == "") {
            $('#usuario_passwordLast').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, debe ingresar su contraseña actual.'); }, 1);
        } else if (containsSpecialCharsPassword($('#usuario_passwordLast').val())) {
            $('#usuario_passwordLast').css('borderColor', 'var(--color-wrong)');
            hideAlert2()
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, la contraseña actual no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
        } else if (!inputPasswordLast.checkValidity()) {
            $('#usuario_passwordLast').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, la contraseña actual debe tener almenoz 6 caracteres y no debe contener espacios en blanco.'); }, 1);
        } else if ($("#usuario_password").val() == "") {
            $('#usuario_password').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, debe ingresar la nueva contraseña.'); }, 1);
        } else if (containsSpecialCharsPassword($('#usuario_password').val())) {
            $('#usuario_password').css('borderColor', 'var(--color-wrong)');
            hideAlert2()
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, la nueva contraseña no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
        } else if (!inputPassword.checkValidity()) {
            $('#usuario_password').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, la nueva contraseña debe tener almenoz 6 caracteres y no debe contener espacios en blanco.'); }, 1);
        } else if ($("#usuario_passwordRe").val() == "") {
            $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, confirmar la nueva contraseña.'); }, 1);
        } else if (containsSpecialCharsPassword($('#usuario_passwordRe').val())) {
            $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
            hideAlert2()
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, la confirmacion de la nueva contraseña no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
        } else if (!inputPasswordRe.checkValidity()) {
            $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, la confirmacion de la nueva contraseña debe tener almenoz 6 caracteres y no debe contener espacios en blanco.'); }, 1);
        } else if ($('#usuario_password').val() != $('#usuario_passwordRe').val()) {
            $('#usuario_password').css('borderColor', 'var(--color-wrong)');
            $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
            hideAlert2();
            alertRed2();
            setTimeout(function () { alertsUserMenu('Error, las contraseñas no coinciden.'); }, 1);
        } else {
            const obj = {};
            obj.lastPassword = $('#usuario_passwordLast').val();
            obj.newPassword = $('#usuario_passwordRe').val();
            $.ajax({
                data: obj,
                url: "php/ajax/ajax_usuarios.php",
                type: "GET",
                success: function (response) {
                    $("#res").html(response);
                }
            });
        }
    });

    $('#newPhotoProfile').change(function () {
        let user_data = new FormData();
        let image = $('#newPhotoProfile')[0].files;

        user_data.append('editUserProfileImage', image[0]);
        $.ajax({
            url: "php/ajax/ajax_usuarios.php",
            type: "POST",
            data: user_data,
            processData: false, // Importante usar al pasar un FormData
            contentType: false, // Importante usar al pasar un FormData
            success: function (response) {
                $("#res").html(response);
            }
        });
    });

    // Al clickear afuera de cualquier menu de editar un valor del perfil de usuario
    $('#details__overlay').mousedown(function () {
        hideAlert2();
        $('#carnet-modal').addClass('hidden');
        $('#names-modal').addClass('hidden');
        $('#lastnames-modal').addClass('hidden');
        $('#email-modal').addClass('hidden');
        $('#housePhone-modal').addClass('hidden');
        $('#phone-modal').addClass('hidden');
        $('#type-modal').addClass('hidden');
        $('#depto-modal').addClass('hidden');
        $('#password-modal').addClass('hidden');

        $('#details__overlay').addClass('hidden');
        $('#user-modal').removeClass('hidden');
    });


    // Actualizando input deptartamentos en perfil de usuario en vivo
    const obj = {};
    obj.showDepto = 0;

    function requestDeptos() {
        $.ajax({
            data: obj,
            url: "php/ajax/ajax_app.php",
            type: "POST",
            success: function (response) {
                $("#requestingDeptos").html(response);
            }
        });
    }

    requestDeptos();


    // Calling the function inputChanges on the inputs
    $('#usuario_carnet').each(function () {
        inputChanges(this);
    });

    $('#usuario_names').each(function () {
        inputChanges(this);
    });

    $('#usuario_lastnames').each(function () {
        inputChanges(this);
    });

    $('#usuario_email').each(function () {
        inputChanges(this);
    });

    $('#usuario_house-phone').each(function () {
        inputChanges(this);
    });

    $('#usuario_phone').each(function () {
        inputChanges(this);
    });

    $('#usuario_passwordLast').each(function () {
        inputChanges(this);
    });

    $('#usuario_password').each(function () {
        inputChanges(this);
    });

    $('#usuario_passwordRe').each(function () {
        inputChanges(this);
    });

    // Selected option changes
    $('#usuario_type').change(function () {
        hideAlert2();
        $('#usuario_type').css('borderColor', 'var(--color-border)');
    });

    $('#usuarioDepto').change(function () {
        hideAlert2();
        $('#usuarioDepto').css('borderColor', 'var(--color-border)');
    });

    function inputChanges($this) {
        // Save current value of element
        $($this).data('oldVal', $($this));

        // Look for changes in the value
        $($this).bind("propertychange keyup keydown input paste", function (event) {
            // If value has changed...
            if ($($this).data('oldVal') != $($this).val()) {
                // Updated stored value
                $($this).data('oldVal', $($this).val());

                $($this).css('borderColor', 'var(--color-purple)');
                hideAlert2();
            }
        });

        $($this).on("focusout", function (event) {
            // changing the color of the border
            $($this).css("borderColor", "var(--color-border)");
        });

        $($this).on("focus", function (event) {
            // changing the color of the border
            $($this).css("borderColor", "var(--color-purple)");
        });
    }

    // Cerrando el mini menu (esquina superior derecha) al clickear afuera de el
    $("#user__overlay").mousedown(function () {
        userMenu.classList.add('hidden');
        userOverlay.classList.add('hidden');
    });

    // Abriendo el menu de perfil de usuario
    $("#opneUserMenu").mousedown(function () {
        $("#modal-background").removeClass('hidden');
        $("#user-modal").removeClass('hidden');

        $.ajax({
            url: "php/ajax/ajax_app.php", success: function () {
                userMenu.classList.add('hidden');
                userOverlay.classList.add('hidden');
            }
        });
    });

    // Cerrando el menu del perfil de usuario al clickear afuera de el
    $("#modal-background").mousedown(function () {
        $("#modal-background").addClass('hidden');
        $("#user-modal").addClass('hidden');
        $('#alert2').addClass('hidden');
    });

    // Cerrando el menu de perfil de usuario al clickear la x
    $("#btnCloseProfile").mousedown(function () {
        $("#modal-background").addClass('hidden');
        $("#user-modal").addClass('hidden');
        $('#alert2').addClass('hidden');
    });
});

// Temas de la aplicacion
function darkTheme() {
    const Obj = {};
    Obj.dark = 0;

    $.ajax({
        url: "php/ajax/ajax_app.php?" + $.param(Obj), success: function () {
            // Changing the Theme
            // Getting elements related to the app's theme
            const variablesContainer = document.querySelector('body');

            variablesContainer.classList.add('theme-dark');
            variablesContainer.classList.remove('theme-light');

            // Showing the check icon
            $("#iconCheckLight").css('visibility', 'hidden');
            $("#iconCheckDark").css('visibility', 'visible');

            // Not able to click it again
            btnDark.removeAttribute('onmousedown');
            btnLight.setAttribute('onmousedown', 'lightTheme()');

            // Changing between cursos default and pointer
            btnDark.style.cursor = 'default';
            btnLight.style.cursor = 'pointer';

            // Closing the user menu
            userMenu.classList.add('hidden');
            userOverlay.classList.add('hidden');
        }
    });
};

function lightTheme() {
    const Obj = {};
    Obj.light = 0;

    $.ajax({
        url: "php/ajax/ajax_app.php?" + $.param(Obj), success: function () {
            const variablesContainer = document.querySelector('body');

            variablesContainer.classList.add('theme-light');
            variablesContainer.classList.remove('theme-dark');

            // Mostrando el icono de check
            $("#iconCheckDark").css("visibility", "hidden");
            $("#iconCheckLight").css('visibility', 'visible');

            // No permitiendo clickear denuevo el boton clickeado
            btnLight.removeAttribute('onmousedown');
            btnDark.setAttribute('onmousedown', 'darkTheme()');

            // Cambiando cursor al default al boton clickeado
            btnLight.style.cursor = 'default';
            btnDark.style.cursor = 'pointer';

            // Cerrando el menu del usuario
            userMenu.classList.add('hidden');
            userOverlay.classList.add('hidden');
        }
    });
}