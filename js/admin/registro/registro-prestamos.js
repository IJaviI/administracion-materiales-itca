"use strict";

function marcarDev(id) {
    const obj = {};
    obj.marcarDevuelto = "";
    obj.id_prestamo = id;
    $.ajax({
        url: "php/ajax/ajax_registro-prestamos.php?" + $.param(obj),
        success: function (response) {
            $('#resp').html(response);
            mostrarRegistroPrestamos();
        }
    });
}

function cancelRegistro(id) {
    const obj = {};
    obj.cancel = "";
    obj.id_prestamo = id;
    $.ajax({
        url: "php/ajax/ajax_registro-prestamos.php?" + $.param(obj),
        success: function (response) {
            $('#resp').html(response);
            mostrarRegistroPrestamos();
        }
    });
}

// Filters
$('#filterFechaDestino').each(function () {
    // Save current value of the element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("input", function (event) {
        // if value changes...
        if ($(this).data('oldVal') != $(this).val()) {
            // Update old value to the new one
            $(this).data('oldVal', $(this).val())

            // Filtering data on table
            const Obj = {};
            Obj.filterFechaDestino = $(this).val();
            Obj.filterFechaHecho = $('#filterFechaHecho').val();
            Obj.filterCarnet = $('#filterCarnet').val();
            Obj.filterEquipo = $('#filterEquipo').val();
            hideAlert();

            $.ajax({
                url: "php/ajax/ajax_registro-prestamos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-registro-prestamos").html(respuesta);
                }
            });
        }
    })
});

$('#filterFechaHecho').each(function () {
    // Save current value of the element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("input", function (event) {
        // if value changes...
        if ($(this).data('oldVal') != $(this).val()) {
            // Update old value to the new one
            $(this).data('oldVal', $(this).val())

            // Filtering data on table
            const Obj = {};
            Obj.filterFechaDestino = $('#filterFechaDestino').val();
            Obj.filterFechaHecho = $(this).val();
            Obj.filterCarnet = $('#filterCarnet').val();
            Obj.filterEquipo = $('#filterEquipo').val();
            hideAlert();

            $.ajax({
                url: "php/ajax/ajax_registro-prestamos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-registro-prestamos").html(respuesta);
                }
            });
        }
    })
});

$('#filterCarnet').each(function () {
    // Save current value of the element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // if value changes...
        if ($(this).data('oldVal') != $(this).val()) {
            // Update old value to the new one
            $(this).data('oldVal', $(this).val())

            if (!containsSpecialCharsNombre($(this).val())) {
                // Filtering data on table
                const Obj = {};
                Obj.filterFechaDestino = $('#filterFechaDestino').val();
                Obj.filterFechaHecho = $('#filterFechaHecho').val();
                Obj.filterCarnet = $(this).val();
                Obj.filterEquipo = $('#filterEquipo').val();
                hideAlert();

                $.ajax({
                    url: "php/ajax/ajax_registro-prestamos.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-registro-prestamos").html(respuesta);
                    }
                });
            } else {
                alertRed();
                alerts('Error, asegurese que el filtro por nombre no contenga ningun caracter especial.');
            }
        }
    })
});

$('#filterEquipo').each(function () {
    // Save current value of the element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // if value changes...
        if ($(this).data('oldVal') != $(this).val()) {
            // Update old value to the new one
            $(this).data('oldVal', $(this).val())

            if (!containsSpecialCharsNombre($(this).val())) {
                // Filtering data on table
                const Obj = {};
                Obj.filterFechaDestino = $('#filterFechaDestino').val();
                Obj.filterFechaHecho = $('#filterFechaHecho').val();
                Obj.filterCarnet = $('#filterCarnet').val();
                Obj.filterEquipo = $(this).val();
                hideAlert();

                $.ajax({
                    url: "php/ajax/ajax_registro-prestamos.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-registro-prestamos").html(respuesta);
                    }
                });
            } else {
                alertRed();
                alerts('Error, asegurese que el filtro por nombre no contenga ningun caracter especial.');
            }
        }
    })
});

function mostrarRegistroPrestamos() {
    $.ajax({
        url: "php/ajax/ajax_registro-prestamos.php",
        data: {
            tablaRegistroPrestamos: ""
        },
        type: "POST",
        success: function (response) {
            $('#table-registro-prestamos').html(response);
        }
    });
}

mostrarRegistroPrestamos();