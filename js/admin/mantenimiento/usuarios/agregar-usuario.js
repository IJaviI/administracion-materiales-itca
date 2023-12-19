"use strict";

// Inputs de la creacion del admin
const inputCarnet1 = document.querySelector('#usuario_carnet1');
const inputNames1 = document.querySelector('#usuario_names1');
const inputLastNames1 = document.querySelector('#usuario_lastnames1');
const inputType1 = document.querySelector('usuario_type1');
const inputHousePhone1 = document.querySelector('#usuario_house-phone1');
const inputPhone1 = document.querySelector('#usuario_phone1');
const inputEmail1 = document.querySelector('#usuario_email1');
const inputPassword1 = document.querySelector('#usuario_password1');
const inputPasswordRe1 = document.querySelector('#usuario_passwordRe1');

// AVoiding user writing numbers on Names's input
inputNames1.addEventListener('keydown', function (event) {
    if ((/\d/g).test(event.key)) event.preventDefault();
});

// AVoiding user writing numbers on LastNames's input
inputLastNames1.addEventListener('keydown', function (event) {
    if ((/\d/g).test(event.key)) event.preventDefault();
});

// When clicking the button "Crear cuenta de usuario"
$("#user-account").click(function (e) {
    e.preventDefault();
    validateUser();
});

// When clicking the key 'Enter' when writing on the input
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validateUser();
    }
});

// Cambiado nombre del input por el del archivo subido
$("#user_image1").change(function () {
    filename = this.files[0].name;
    const labelFile = document.querySelector('.form__row-label-state');
    labelFile.textContent = filename;
});

// Calling the function inputChanges on the inputs
$('#usuario_carnet1').each(function () {
    inputChanges(this);
});

$('#usuario_names1').each(function () {
    inputChanges(this);
    inputChanges('#usuario_lastnames');
});

$('#usuario_lastnames1').each(function () {
    inputChanges(this);
    inputChanges('#usuario_names');
});

$('#usuario_type1').each(function () {
    inputChanges(this);
});

$('#usuario_isAdmin1').each(function () {
    inputChanges(this);
});

$('#usuario_house-phone1').each(function () {
    inputChanges(this);
});

$('#usuario_phone1').each(function () {
    inputChanges(this);
});

$('#usuario_email1').each(function () {
    inputChanges(this);
});

$('#usuario_password1').each(function () {
    inputChanges(this);
    $(this).on('input', function () {
        if ($(this).val() != "") {
            $(this).css('-webkit-text-security', 'disc');
        } else {
            $(this).css('-webkit-text-security', 'none');
        }
    });
});

$('#usuario_passwordRe1').each(function () {
    inputChanges(this);
    $(this).on('input', function () {
        if ($(this).val() != "") {
            $(this).css('-webkit-text-security', 'disc');
        } else {
            $(this).css('-webkit-text-security', 'none');
        }
    });
});

$('#usuario_depto1').change(function () {
    hideAlert2();
    $('#usuario_depto1').css('borderColor', 'var(--color-border)');
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
            $('#usuario_lastnames').css("borderColor", "var(--color-border)");
            $('#usuario_names').css("borderColor", "var(--color-border)");
            hideAlert();
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

// Validating the inputs
function validateUser() {
    if ($('#usuario_carnet1').val() == "") {
        $('#usuario_carnet1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar el carnet.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_carnet1').val())) {
        $('#usuario_carnet1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, el carnet no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
    } else if ($('#usuario_names1').val() == "") {
        $('#usuario_names1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar los nombres.'); }, 1);
    } else if (containsSpecialChars($('#usuario_names1').val())) {
        $('#usuario_names1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, los nombres no pueden contener ningun caracter especial.'); }, 1);
    } else if ($('#usuario_lastnames1').val() == "") {
        $('#usuario_lastnames1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar los apellidos.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_lastnames1').val())) {
        $('#usuario_lastnames1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, los apellidos no pueden contener ningun caracter especial.'); }, 1);
    } else if ($("#usuario_type1").val() == 0) {
        $('#usuario_type1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de seleccionar un cargo.'); }, 1);
    } else if ($("#usuario_isAdmin1").val() == -1) {
        $('#usuario_isAdmin1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de seleccionar un tipo de usuario.'); }, 1);
    } else if ($("#usuario_house-phone1").val() == "") {
        $('#usuario_house-phone1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar un telefono de casa.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_house-phone1').val())) {
        $('#usuario_house-phone1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, el telefono de casa no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
    } else if (!inputHousePhone1.checkValidity()) {
        $('#usuario_house-phone1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, telefono de casa no puede contener letras y debe contener 8 digitos separados por (-) ejemplo: 4454-3423.'); }, 1);
    } else if ($("#usuario_phone1").val() == "") {
        $('#usuario_phone1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar un celular.'); }, 1);
    } else if (containsSpecialCharsGuion($('#usuario_phone1').val())) {
        $('#usuario_phone1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, el celular no puede contener caracteres especiales a exepcion del guion (-).'); }, 1);
    } else if (!inputPhone1.checkValidity()) {
        $('#usuario_phone1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, celular no puede contener letras y debe contener 8 digitos separados por (-) ejemplo: 6745-2565.'); }, 1);
    } else if ($("#usuario_email1").val() == "") {
        $('#usuario_email1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar un email.'); }, 1);
    } else if (!inputEmail1.checkValidity()) {
        $('#usuario_email1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, ingrese un email valido (asegurese que no contenga espacios), ejemplo: ejemplo@itca.edu.sv'); }, 1);
    } else if ($("#usuario_password1").val() == "") {
        $('#usuario_password1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de ingresar una contraseña.'); }, 1);
    } else if (containsSpecialCharsPassword($('#usuario_password1').val())) {
        $('#usuario_password1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, la contraseña no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
    } else if (!inputPassword1.checkValidity()) {
        $('#usuario_password1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, la contraseña debe tener almenoz 6 caracteres y no debe contener espacios en blanco.'); }, 1);
    } else if ($("#usuario_passwordRe1").val() == "") {
        $('#usuario_passwordRe1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de confirmar la contraseña.'); }, 1);
    } else if (containsSpecialCharsPassword($('#usuario_passwordRe1').val())) {
        $('#usuario_passwordRe1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, la confirmacion de la contraseña no puede contener caracteres especiales a exepcion de @!#$.'); }, 1);
    } else if ($('#usuario_password1').val() != $('#usuario_passwordRe1').val()) {
        $('#usuario_password1').css('borderColor', 'var(--color-wrong)');
        $('#usuario_passwordRe1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, las contraseñas no coinciden.'); }, 1);
    } else if ($("#usuario_depto1").val() == 0) {
        $('#usuario_depto1').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, seleccione un departamento.'); }, 0);
    } else {
        const obj = {};
        obj.carnetUser = $('#usuario_carnet1').val();
        obj.namesUser = $('#usuario_names1').val();
        obj.lastNamesUser = $('#usuario_lastnames1').val();
        obj.typeUser = $('#usuario_type1').val();
        obj.typeOfUser = $('#usuario_isAdmin1').val();
        obj.housePhoneUser = $('#usuario_house-phone1').val();
        obj.phoneUser = $('#usuario_phone1').val();
        obj.emailUser = $('#usuario_email1').val();
        obj.passwordUser = $('#usuario_passwordRe1').val();
        obj.deptoUser = $('#usuario_depto1').val();
        obj.createdBy = $('#createdBy').val();

        let form_data = new FormData();
        let image = $('#user_image1')[0].files;

        $.ajax({
            url: "php/ajax/ajax_usuarios.php",
            type: "POST",
            data: obj,
            success: function (response) {
                $("#resp").html(response);
            }
        });

        // Solo pasa si se inserta una imagen en el input
        if (image.length > 0) {
            form_data.append('user_image', image[0]);
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
                    noUserImage: "no"
                },
                success: function (response) {
                    $("#resp").html(response);
                }
            });
        }

        return true;
    }
}

// Adding with a csv file
$('#csv_usuarios').change(function () {
    let usuarios_data = new FormData();
    let file = $('#csv_usuarios')[0].files;

    usuarios_data.append('csv_usuarios', file[0]);
    $.ajax({
        url: "php/ajax/ajax_usuarios.php",
        type: "POST",
        data: usuarios_data,
        processData: false, // Importante usar al pasar un FormData
        contentType: false, // Importante usar al pasar un FormData
        success: function (response) {
            $("#resp").html(response);
        }
    });
});