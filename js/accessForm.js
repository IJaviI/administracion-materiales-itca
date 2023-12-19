"use strict";

// Inputs del inicio de sesion
const emailLogin = document.querySelector("#email_login");

// When clicking the button "Crear cuenta"
$("#admin-account").mousedown(function () {
    validateAdmin();
});

$('#login').mousedown(function () {
    validateLogin();
});

// Cambiado nombre del input por el del archivo subido
$("#admin_image").change(function () {
    filename = this.files[0].name;
    const labelFile = document.querySelector('.form__row-label-state');
    labelFile.textContent = filename;
});


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

$('#usuario_type').each(function () {
    inputChanges(this);
});

$('#usuario_house-phone').each(function () {
    inputChanges(this);
});

$('#usuario_phone').each(function () {
    inputChanges(this);
});

$('#usuario_email').each(function () {
    inputChanges(this);
});

$('#usuario_password').each(function () {
    inputChanges(this);
});

$('#usuario_passwordRe').each(function () {
    inputChanges(this);
});


// Inicio de sesion
$('#email_login').each(function () {
    inputChanges(this);
});

$('#password_login').each(function () {
    inputChanges(this);
});

// Doing something with events on the input like changing the color of the border
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
            hideAlert();
        }
    });

    $($this).on("focusout", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "#3e3e3e");
    });

    $($this).on("focus", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-purple)");
    });
}

// Validating that the inputs
function validateAdmin() {
    if ($('#usuario_carnet').val() == "") {
        $('#usuario_carnet').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar el carnet.'); }, 1);
    } else if (containsSpecialChars($('#usuario_carnet').val())) {
        $('#usuario_carnet').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, el carnet no puede contener caracteres especiales.'); }, 1);
    } else if ($('#usuario_names').val() == "") {
        $('#usuario_names').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar los nombres.'); }, 1);
    } else if (containsSpecialChars($('#usuario_names').val())) {
        $('#usuario_names').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, los nombres no pueden contener ningun caracter especial.'); }, 1);
    } else if ($('#usuario_lastnames').val() == "") {
        $('#usuario_lastnames').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar los apellidos.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_lastnames').val())) {
        $('#usuario_lastnames').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, los apellidos no pueden contener ningun caracter especial.'); }, 1);
    } else if ($("#usuario_type").val() != "Ingeniero" && $("#usuario_type").val() != "Licenciado" && $("#usuario_type").val() != "Tecnico") {
        $('#usuario_type').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de seleccionar un cargo.'); }, 1);
    } else if ($("#usuario_house-phone").val() == "") {
        $('#usuario_house-phone').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar un telefono de casa.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_house-phone').val())) {
        $('#usuario_house-phone').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, el telefono de casa no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
    } else if (!inputHousePhone.checkValidity()) {
        $('#usuario_house-phone').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, telefono de casa no puede contener letras y debe contener 8 digitos separados por (-) ejemplo: 4454-3423.'); }, 1);
    } else if ($("#usuario_phone").val() == "") {
        $('#usuario_phone').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar un celular.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_phone').val())) {
        $('#usuario_phone').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, el celular no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
    } else if (!inputPhone.checkValidity()) {
        $('#usuario_phone').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, celular no puede contener letras y debe contener 8 digitos separados por (-) ejemplo: 6745-2565.'); }, 1);
    } else if ($("#usuario_email").val() == "") {
        $('#usuario_email').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar un email.'); }, 1);
    } else if (!inputEmail.checkValidity()) {
        $('#usuario_email').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, ingrese un email valido (asegurese que no contenga espacios), ejemplo: ejemplo@itca.edu.sv'); }, 1);
    } else if ($("#usuario_password").val() == "") {
        $('#usuario_password').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar una contraseña.'); }, 1);
    } else if (containsSpecialCharsPassword($('#usuario_password').val())) {
        $('#usuario_password').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, la contraseña no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
    } else if (!inputPassword.checkValidity()) {
        $('#usuario_password').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, la contraseña debe tener almenoz 6 caracteres y no debe contener espacios en blanco.'); }, 1);
    } else if ($("#usuario_passwordRe").val() == "") {
        $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de confirmar la contraseña.'); }, 1);
    } else if (containsSpecialCharsPassword($('#usuario_passwordRe').val())) {
        $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
        alertRed();
        setTimeout(function () { alerts('Error, la confirmacion de la contraseña no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
    } else if ($('#usuario_password').val() != $('#usuario_passwordRe').val()) {
        $('#usuario_password').css('borderColor', 'var(--color-wrong)');
        $('#usuario_passwordRe').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, las contraseñas no coinciden.'); }, 1);
    } else {
        const obj = {};
        obj.carnetAdmin = $('#usuario_carnet').val();
        obj.namesAdmin = $('#usuario_names').val();
        obj.lastNamesAdmin = $('#usuario_lastnames').val();
        obj.typeAdmin = $('#usuario_type').val();
        obj.housePhoneAdmin = $('#usuario_house-phone').val();
        obj.phoneAdmin = $('#usuario_phone').val();
        obj.emailAdmin = $('#usuario_email').val();
        obj.passwordAdmin = $('#usuario_passwordRe').val();

        let form_data = new FormData();
        let image = $('#admin_image')[0].files;

        $.ajax({
            url: "php/ajax/ajax_usuarios.php",
            type: "POST",
            data: obj,
            success: function (response) {
                $("#resp").html(response);
            }
        });

        // Solo pasa si se inserto una imagen en el input
        if (image.length > 0) {
            form_data.append('admin_image', image[0]);
            $.ajax({
                url: "php/ajax/ajax_usuarios.php",
                type: "POST",
                data: form_data,
                processData: false, // Importante usar al pasar un FormData
                contentType: false, // Importante usar al pasar un FormData
                success: function (response) {
                    $("#resp").html(response);
                }
            });
        } else {
            $.ajax({
                url: "php/ajax/ajax_usuarios.php",
                type: "POST",
                data: {
                    noAdminImage: "no"
                },
                success: function (response) {
                    $("#resp").html(response);
                }
            });
        }

        return true;
    }
}


function validateLogin() {
    if ($('#email_login').val() == "") {
        $('#email_login').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        setTimeout(function () { alerts('Error, asegurese de ingresar el email.'); }, 1);
    } else if (!emailLogin.checkValidity()) {
        $('#usuario_email').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        setTimeout(function () { alerts('Error, ingrese un email valido, ejemplo: ejemplo@itca.edu.sv'); }, 1);
    } else if ($('#password_login').val() == "") {
        $('#password_login').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        setTimeout(function () { alerts('Error, asegurese de ingresar la contraseña.'); }, 1);
    } else {
        const Obj = {};
        Obj.userEmail = $('#email_login').val();
        Obj.userPassword = $('#password_login').val();

        $.ajax({
            url: "php/ajax/ajax_login.php?" + $.param(Obj), success: function (response) {
                $("#resp").html(response);
            }
        });
        return true;
    }
}