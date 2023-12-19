"use strict";

// Generating pdf
$('#btn-generate').mousedown(function () {
    validate();
});

// Input changes
$('#filtrarFechaIngresoDesde').each(function () {
    inputChanges(this);
});

$('#filtrarFechaIngresoHasta').each(function () {
    inputChanges(this);
});

$('#filterDepto').each(function () {
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

function validate() {
    if(!$('#filtrarFechaIngresoDesde').val()) {
        $('#filtrarFechaIngresoDesde').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes ingresar desde que fecha desea generar en base a la fecha de ingreso del material.'); }, 1);
    } else if(!$('#filtrarFechaIngresoHasta').val()) {
        $('#filtrarFechaIngresoHasta').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes ingresar desde que fecha hasta generar en base a la fecha de ingreso del material.'); }, 1);
    } else if ($('#filterDepto').val() == 0) {
        $('#filterDepto').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar el departamento de los materiales.'); }, 1);
    } else {
        $('#general-form').attr('action', 'php/controladores/generar-control-de-entrega-excel.php');
        $('#general-form').submit();
        hideAlert();
        alertGreen();
        setTimeout(function () { alerts('Has generado el pdf con exito, la descarga se almaceno en la carpeta descargas.'); }, 1);
    }
}