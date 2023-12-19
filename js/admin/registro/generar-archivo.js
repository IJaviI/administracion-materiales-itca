"use strict";

// Changing between filters
$('#btn-fechas').mousedown(function () {
    $('#generate-rango-fechas').toggleClass('hidden');
    $('#generate-user').addClass('hidden');

    // Reset every input
    $('#filterFechaDestinoFrom').val('');
    $('#filterFechaDestinoTo').val('');
    $('#filterEstadoFechas').val(0);
    $('#filterDepto').val(0);

    hideAlert();
    $('#filterFechaDestinoFrom').css("borderColor", "var(--color-border)");
    $('#filterFechaDestinoTo').css("borderColor", "var(--color-border)");
    $('#filterEstadoFechas').css("borderColor", "var(--color-border)");
    $('#filterDepto').css("borderColor", "var(--color-border)");
});

$('#btn-user').mousedown(function () {
    $('#generate-user').toggleClass('hidden');
    $('#generate-rango-fechas').addClass('hidden');

    // Reset every input
    $('#usuario_presto').val(0);
    $('#filterEstadoUser').val(0);

    hideAlert();
    $('#usuario_presto').css("borderColor", "var(--color-border)");
    $('#filterEstadoUser').css("borderColor", "var(--color-border)");
});

// Generating excel
$('#btn-generate-fechas').mousedown(function () {
    validateRangoFechas();
});

$('#btn-generate-user').mousedown(function () {
    validateUser();
});

// Input changes
$('#filterFechaDestinoFrom').each(function () {
    inputChanges(this);
});

$('#filterFechaDestinoTo').each(function () {
    inputChanges(this);
});

$('#filterEstadoFechas').each(function () {
    inputChanges(this);
});

$('#filterDepto').each(function () {
    inputChanges(this);
});

$('#filterEstadoUser').each(function () {
    inputChanges(this);
});

$('#usuario_presto').each(function () {
    inputChanges(this);
});

function inputChanges($this) {
    $($this).change(function () {
        hideAlert();
        alertRed();
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

function validateRangoFechas() {
    if (!$('#filterFechaDestinoFrom').val()) {
        $('#filterFechaDestinoFrom').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, debe ingresar desde que fecha desea generar.'); }, 1);
    } else if (!$('#filterFechaDestinoTo').val()) {
        $('#filterFechaDestinoTo').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, debe ingresar hasta que fecha desea generar.'); }, 1);
    } else if ($('#filterFechaDestinoTo').val() < $('#filterFechaDestinoFrom').val()) {
        $('#filterFechaDestinoTo').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar una fecha superior a la fecha destino (desde).'); }, 1);
    } else if ($('#filterEstadoFechas').val() == 0) {
        $('#filterEstadoFechas').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar el estado de los prestamos.'); }, 1);
    } else if ($('#filterDepto').val() == 0) {
        $('#filterDepto').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar el departamento de los prestamos.'); }, 1);
    } else {
        $('#general-form').submit();
        hideAlert();
        alertGreen();
        setTimeout(function () { alerts('Has generado el excel con exito, la descarga se almaceno en la carpeta descargas.'); }, 1);
    }
}

function validateUser() {
    if ($('#usuario_presto').val() == 0) {
        $('#usuario_presto').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar el usuario que presto.'); }, 1);
    } else if ($('#filterEstadoUser').val() == 0) {
        $('#filterEstadoUser').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar el estado de los prestamos.'); }, 1);
    } else {
        $('#general-form').submit();
        hideAlert();
        alertGreen();
        setTimeout(function () { alerts('Has generado el excel con exito, la descarga se almaceno en la carpeta descargas.'); }, 1);
    }
}