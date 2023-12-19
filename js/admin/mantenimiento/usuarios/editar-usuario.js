"use strict";

// When clicking the button "Guardar"
$("#guardar").click(function () {
    validate();
});

$("#page").focus();
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Cambiado nombre del input por el del archivo subido
$("#user_imagen").change(function () {
    filename = this.files[0].name;
    const labelFile = document.querySelector('.form__row-label-state');
    labelFile.textContent = filename;
});

// Calling the function inputChanges on the inputs
$('#user_carnet').each(function () {
    inputChanges(this);
});

$('#user_names').each(function () {
    inputChanges(this);
});

$('#user_lastnames').each(function () {
    inputChanges(this);
});

$('#user_type').each(function () {
    inputChanges(this);
});

$('#user_telcasa').each(function () {
    inputChanges(this);
});

$('#user_celular').each(function () {
    inputChanges(this);
});

$('#user_email').each(function () {
    inputChanges(this);
});

$('#user_depto').each(function () {
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

            btnInitialState();
            $($this).css('borderColor', 'var(--color-purple)');

            if ($($this).attr('id') == "user_carnet") {
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_names") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_lastnames") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_type") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_telcasa") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_celular") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_email") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "user_depto") {
                $('#user_carnet').css("borderColor", "var(--color-light)");
                $('#user_names').css("borderColor", "var(--color-light)");
                $('#user_lastnames').css("borderColor", "var(--color-light)");
                $('#user_type').css("borderColor", "var(--color-light)");
                $('#user_telcasa').css("borderColor", "var(--color-light)");
                $('#user_celular').css("borderColor", "var(--color-light)");
                $('#user_email').css("borderColor", "var(--color-light)");
            }

            hideAlert();
        }
    });

    $($this).on("focusout", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-light)");
    });

    $($this).on("focus", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-purple)");
    });
}

// Changing the btn "Guardar" text and showing the check icon
function btnStateChange() {
    const btnText = document.querySelector('.guardar-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Guardado";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Guardar" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.guardar-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Guardar";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
$('#user_carnet').data('inicialValue', $("#user_carnet").val());
$('#user_names').data('inicialValue', $("#user_names").val());
$('#user_lastnames').data('inicialValue', $("#user_lastnames").val());
$('#user_type').data('inicialValue', $("#user_type").val());
$('#user_telcasa').data('inicialValue', $("#user_telcasa").val());
$('#user_celular').data('inicialValue', $("#user_celular").val());
$('#user_email').data('inicialValue', $("#user_email").val());
$('#user_depto').data('inicialValue', $("#user_depto").val());


const carnetInitialValue = $("#user_carnet").data('inicialValue');
const namesInitialValue = $("#user_names").data('inicialValue');
const lastnamesInitialValue = $("#user_lastnames").data('inicialValue');
const typetialValue = $("#user_type").data('inicialValue');
const telcasaInitialValue = $("#user_telcasa").data('inicialValue');
const celularInitialValue = $("#user_celular").data('inicialValue');
const emailInitialValue = $("#user_email").data('inicialValue');
const deptoInitialValue = $("#user_depto").data('inicialValue');

const inputHousePhone = document.querySelector('#user_telcasa');
const inputPhone = document.querySelector('#user_celular');
const inputEmail = document.querySelector('#user_email');

function validate() {
    if (carnetInitialValue == $("#user_carnet").val() && namesInitialValue == $("#user_names").val() && lastnamesInitialValue == $("#user_lastnames").val() && typetialValue == $("#user_type").val() && telcasaInitialValue == $("#user_telcasa").val() && celularInitialValue == $("#user_celular").val() && emailInitialValue == $("#user_email").val() && deptoInitialValue == $("#user_depto").val()) {
        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Huh?, no se ha detectado un cambio en alguno de los campos a editar, para editar asigne un nuevo valor en uno de los campos de este usuario."); }, 10);

        $('#user_carnet').css("borderColor", "var(--color-wrong)");
        $('#user_names').css("borderColor", "var(--color-wrong)");
        $('#user_lastnames').css("borderColor", "var(--color-wrong)");
        $('#user_type').css("borderColor", "var(--color-wrong)");
        $('#user_telcasa').css("borderColor", "var(--color-wrong)");
        $('#user_celular').css("borderColor", "var(--color-wrong)");
        $('#user_email').css("borderColor", "var(--color-wrong)");
        $('#user_depto').css("borderColor", "var(--color-wrong)");
    } else if ($("#user_carnet").val() == "") {
        $("#user_carnet").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un carnet para editar este usuario."); }, 10);
    } else if (containsSpecialChars($("#user_carnet").val())) {
        $("#user_carnet").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { }, 10);
        alerts('Error, asegurese que el carnet no contenga ningun caracter especial.');
    } else if ($("#user_names").val() == "") {
        $("#user_names").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar nombres para editar este usuario."); }, 10);
    } else if (containsSpecialChars($("#user_names").val())) {
        $("#user_names").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { }, 10);
        alerts('Error, asegurese que los nombres no contenga ningun caracter especial.');
    } else if ($("#user_lastnames").val() == "") {
        $("#user_lastnames").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar apellidos para editar este usuario."); }, 10);
    } else if (containsSpecialChars($("#user_lastnames").val())) {
        $("#user_lastnames").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { }, 10);
        alerts('Error, asegurese que los apellidos no contenga ningun caracter especial.');
    } else if ($("#user_telcasa").val() == "") {
        $("#user_telcasa").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un telefono de casa para editar este usuario."); }, 10);
    } else if (containsSpecialCharsGuion($("#user_telcasa").val())) {
        $("#user_telcasa").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { }, 10);
        alerts('Error, el telefono de casa no puede contener caracteres especiales a exepcion del guion (-).');
    } else if (!inputHousePhone.checkValidity()) {
        $('#user_telcasa').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, el telefono de casa debe contener 8 digitos separados por (-) ejemplo: 2445-6789.'); }, 1);
    } else if ($("#user_celular").val() == "") {
        $("#user_celular").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un celular para editar este usuario."); }, 10);
    } else if (containsSpecialCharsGuion($("#user_celular").val())) {
        $("#user_celular").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { }, 10);
        alerts('Error, el celular no puede contener caracteres especiales a exepcion del guion (-).');
    } else if (!inputPhone.checkValidity()) {
        $('#user_celular').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, el celular debe contener 8 digitos separados por (-) ejemplo: 7665-4567.'); }, 1);
    } else if ($("#user_email").val() == "") {
        $("#user_email").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un email  para editar este usuario."); }, 10);
    } else if (!inputEmail.checkValidity()) {
        $('#user_email').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, ingrese un email valido (asegurese que no contenga espacios), ejemplo: ejemplo@itca.edu.sv'); }, 1);
    } else {
        const Obj = {};
        Obj.editUser = "";
        Obj.user_id = $("#user_id").val();
        Obj.user_names = $("#user_names").val();
        Obj.user_lastnames = $("#user_lastnames").val();
        Obj.user_type = $("#user_type").val();
        Obj.user_telcasa = $("#user_telcasa").val();
        Obj.user_celular = $("#user_celular").val();
        Obj.user_email = $("#user_email").val();
        Obj.user_depto = $("#user_depto").val();

        $.ajax({
            url: "php/ajax/ajax_usuarios.php?" + $.param(Obj), success: function (respuesta) {
                $("#resp").html(respuesta);
            }
        });
        return true;
    }
}