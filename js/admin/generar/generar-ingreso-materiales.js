"use strict";

// Generating pdf
$('#btn-generate-pdf').mousedown(function () {
    validatePdf();
});

// Generating excel
$('#btn-generate-excel').mousedown(function () {
    validateExcel();
});

// Input changes
$('#filterFechaIngreso').each(function () {
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
    if(!$('#filterFechaIngreso').val()) {
        $('#filterFechaIngreso').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes ingresar la fecha de ingreso.'); }, 1);
    } else if ($('#filterDepto').val() == 0) {
        $('#filterDepto').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, para generar debes seleccionar el departamento de los materiales.'); }, 1);
    } else {
        return true;
    }
}

function validatePdf() {
    if (validate()) {
        $('#general-form').attr('action', 'php/controladores/generar-ingreso-materiales-pdf.php');
        $('#general-form').submit();
        hideAlert();
        alertGreen();
        setTimeout(function () { alerts('Has generado el pdf con exito, la descarga se almaceno en la carpeta descargas.'); }, 1);
    }
}

function validateExcel() {
    if (validate()) {
        $('#general-form').attr('action', 'php/controladores/generar-ingreso-materiales-excel.php');
        $('#general-form').submit();
        hideAlert();
        alertGreen();
        setTimeout(function () { alerts('Has generado el excel con exito, la descarga se almaceno en la carpeta descargas.'); }, 1);
    }
}