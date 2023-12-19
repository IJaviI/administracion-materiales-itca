"use strict";

$('#create-prestamo').mousedown(function () {
    validate();
});

$('#finalizar-prestamo').mousedown(function () {
    hideAlert();
    setTimeout(function () {
        $('#resp').html(
            `<div id='alertFinalizarPrestamo' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
            <button type='button' onmousedown='closeAlertFinalizarPrestamo()' class='alert__btn-close'>&times;</button>
            <p class='alert__message'>¿Estas seguro de finalizar tu prestamo actual? Una vez finalizado no es posible editarlo.</p>
            <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                <button type='button' onmousedown='finalizarPrestamo()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, finalizar</button>
                <button type='button' onmousedown='closeAlertFinalizarPrestamo()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
            </div>
        </div>`
        )
    }, 1);
});

function closeAlertFinalizarPrestamo() {
    $('#alertFinalizarPrestamo').addClass('hidden');
}

function finalizarPrestamo() {
    $.ajax({
        url: "php/ajax/ajax_prestamo.php",
        data: {
            finalizar_prestamo: ""
        },
        type: "GET",
        success: function (response) {
            $('#resp').html(response);
        }
    });
}

$('#cancel-prestamo-option1').mousedown(function () {
    hideAlert();
    setTimeout(function () {
        $('#resp').html(
            `<div id='alertCancelPrestamo' class='alert' style='display: block; color: #000; background-color: var(--color-edit);'>
            <button type='button' onmousedown='closeAlertCancelPrestamo()' class='alert__btn-close'>&times;</button>
            <p class='alert__message'>¿Estas seguro de cancelar tu prestamo actual? Una vez cancelado no es posible re activarlo.</p>
            <div class='user__detail' style='display: flex; justify-content: flex-end; gap: 1rem; margin-top: .5rem'>  
                <button type='button' onmousedown='cancelarPrestamo()' style='color: var(--color-dark); background-color: var(--color-save); border-color: #444444'>Si, cancelar</button>
                <button type='button' onmousedown='closeAlertCancelPrestamo()' style='background-color: var(--color-wrong); border-color: #444444'>No, cerrar</button>
            </div>
        </div>`
        )
    }, 1);
});

function closeAlertCancelPrestamo() {
    $('#alertCancelPrestamo').addClass('hidden');
}

function cancelarPrestamo() {
    $.ajax({
        url: "php/ajax/ajax_prestamo.php",
        data: {
            cancel_option1: ""
        },
        type: "GET",
        success: function (response) {
            $('#resp').html(response);
        }
    });
}

function validate() {
    const date = new Date();
    const fecha = new Date($('#fecha_destino').val());

    const currentDate = new Date(`${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`);
    const fechaObjetivo = new Date(`${fecha.getFullYear()}-${fecha.getMonth() + 1}-${fecha.getDate()}`);
    fechaObjetivo.setDate(fechaObjetivo.getDate() + 1);

    if (!$('#fecha_destino').val()) {
        $('#fecha_destino').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, debe ingresar la fecha en la que se prestaran los equipos.'); }, 1);
    } else if (fechaObjetivo < currentDate) {
        $('#fecha_destino').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts(`Error, para abrir un prestamo debes seleccionar ya sea la fecha actual o una fecha futura.`); }, 1);
    } else if (!$('#prestamoAula').val()) {
        $('#prestamoAula').css('borderColor', 'var(--color-wrong)');
        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, debe ingresar el aula o computo en el que se tendran los equipos.'); }, 1);
    } else {
        const createPrestamo = "";
        const id_aula = $('#prestamoAula').val();
        const fecha_destino = $('#fecha_destino').val();

        $.ajax({
            url: "php/ajax/ajax_prestamo.php",
            data: {
                createPrestamo: "",
                id_aula: id_aula,
                fecha_destino: fecha_destino
            },
            type: "POST",
            success: function (respuesta) {
                $("#resp").html(respuesta);
            }
        });
    }
}

// Filter Name working
$('#filterEquipoName').each(function () {
    // Save current value of element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldVal') != $(this).val()) {
            // Updated stored value
            $(this).data('oldVal', $(this).val());

            if (!containsSpecialCharsNombre($(this).val())) {
                // Filtering data on table
                const Obj = {};
                Obj.filterEquipoName = $(this).val();
                Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
                Obj.filterEquipoModelo = $('#filterEquipoModelo').val();
                hideAlert();

                $.ajax({
                    url: "php/ajax/ajax_prestamo.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-equipos").html(respuesta);
                    }
                });
            } else {
                alertRed();
                alerts('Error, asegurese que el filtro por nombre no contenga ningun caracter especial.');
            }
        }
    });
});

// Filter Marca working
$('#filterEquipoMarca').each(function () {
    // Save current value of element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldVal') != $(this).val()) {
            // Updated stored value
            $(this).data('oldVal', $(this).val());

            // Filtering data on table
            const Obj = {};
            Obj.filterEquipoName = $('#filterEquipoName').val();
            Obj.filterEquipoMarca = $(this).val();
            Obj.filterEquipoModelo = $('#filterEquipoModelo').val();
            hideAlert();

            $.ajax({
                url: "php/ajax/ajax_prestamo.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-equipos").html(respuesta);
                }
            });
        }
    });
});

// Filter Modelo working
$('#filterEquipoModelo').each(function () {
    // Save current value of element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldVal') != $(this).val()) {
            // Updated stored value
            $(this).data('oldVal', $(this).val());

            if (!containsSpecialCharsNombre($(this).val())) {
                // Filtering data on table
                const Obj = {};
                Obj.filterEquipoName = $('#filterEquipoName').val();
                Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
                Obj.filterEquipoModelo = $(this).val();
                hideAlert();

                $.ajax({
                    url: "php/ajax/ajax_prestamo.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-equipos").html(respuesta);
                    }
                });
            } else {
                alertRed();
                alerts('Error, asegurese que el filtro por nombre no contenga ningun caracter especial.');
            }
        }
    });
});


$('#fecha_destino').each(function () {
    inputChanges(this);
});

$('#prestamoAula').change(function () {
    hideAlert();
    alertRed();
    $('#prestamoAula').css('borderColor', 'var(--color-border)');
});

$('#prestamoAula').focus(function () {
    $('#prestamoAula').css('borderColor', 'var(--color-purple)');
});

$('#prestamoAula').focusout(function () {
    $('#prestamoAula').css('borderColor', 'var(--color-border)');
});

// Doing something with events on the input like changing the color of the border
function inputChanges($this) {
    $($this).change(function () {
        hideAlert();
        alertRed();
    });

    $($this).on("focusout", function (event) {
        // changing the color of the border
        $($this).css("border", "3px var(--border)");
    });

    $($this).on("focus", function (event) {
        // changing the color of the border
        $($this).css("border", "3px solid var(--color-purple)");
    });
}


// Function to mostrar la tabla equipos
function mostrarTabla() {
    const Obj = {};
    Obj.tablaEquipos = "";

    $.ajax({
        url: "php/ajax/ajax_prestamo.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-equipos").html(respuesta);
        }
    });
}

mostrarTabla();

// Function para mostrar la tabla equipos agregados
function mostrarTablaAgregados() {
    const Obj = {};
    Obj.tablaEquiposAgregados = "";

    $.ajax({
        url: "php/ajax/ajax_prestamo-equipos-agregados.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-equipos-agregados").html(respuesta);
        }
    });
}

mostrarTablaAgregados();