"use strict";

// Filtros
$('#filterUsuarioCarnet').each(function () {
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
            Obj.filterCarnet = $(this).val();
            Obj.filterEmail = $('#filterUsuarioEmail').val();
            Obj.filterNombres = $('#filterUsuarioNombres').val();
            Obj.filterApellidos = $('#filterUsuarioApellidos').val();

            $("#alert").addClass("hidden");

            $.ajax({
                url: "php/ajax/ajax_usuarios.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-responsive").html(respuesta);
                }
            });
        }
    });
});

$('#filterUsuarioEmail').each(function () {
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
            Obj.filterCarnet = $('#filterUsuarioCarnet').val();
            Obj.filterEmail = $(this).val();
            Obj.filterNombres = $('#filterUsuarioNombres').val();
            Obj.filterApellidos = $('#filterUsuarioApellidos').val();

            $("#alert").addClass("hidden");

            $.ajax({
                url: "php/ajax/ajax_usuarios.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-responsive").html(respuesta);
                }
            });
        }
    });
});

$('#filterUsuarioNombres').each(function () {
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
            Obj.filterCarnet = $('#filterUsuarioCarnet').val();
            Obj.filterEmail = $('#filterUsuarioEmail').val();
            Obj.filterNombres = $(this).val();
            Obj.filterApellidos = $('#filterUsuarioApellidos').val();

            $("#alert").addClass("hidden");

            $.ajax({
                url: "php/ajax/ajax_usuarios.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-responsive").html(respuesta);
                }
            });
        }
    });
});

$('#filterUsuarioApellidos').each(function () {
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
            Obj.filterCarnet = $('#filterUsuarioCarnet').val();
            Obj.filterEmail = $('#filterUsuarioEmail').val();
            Obj.filterNombres = $('#filterUsuarioNombres').val();
            Obj.filterApellidos = $(this).val();

            $("#alert").addClass("hidden");

            $.ajax({
                url: "php/ajax/ajax_usuarios.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-responsive").html(respuesta);
                }
            });
        }
    });
});

function mostrarTabla() {
    const obj = {};
    obj.tabla = "";

    $.ajax({
        url: "php/ajax/ajax_usuarios.php?" + $.param(obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
}

mostrarTabla();

$('#modalFoto__overlay').mousedown(function () {
    $('#modal-foto-usuario').addClass('hidden');
    $(this).addClass('hidden');
});

$('#btnCloseFotoUsuario').mousedown(function () {
    $('#modal-foto-usuario').addClass('hidden');
    $('#modalFoto__overlay').addClass('hidden');
});