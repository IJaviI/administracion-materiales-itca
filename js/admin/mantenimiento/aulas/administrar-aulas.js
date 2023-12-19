"use strict";

// function for when the btn delete is clicked, called on the "onclick" attatch the button
function btnDeleteClicked(id) {
    const Obj = {};
    Obj.aula_idRemove = id;
    Obj.filterAulaName = $('#filterAulaName').val();
    Obj.filterAulaType = $('#filterAulaType').val();
    $.ajax({
        url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
}

// function for when the btn edit is clicked, called on the "onclick" attatch the button
function btnEditClicked(id) {
    const Obj = {};
    Obj.aula_id = id;

    $.ajax({
        url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
}

// Filter based on name working
$('#filterAulaName').each(function () {
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
            Obj.filterAulaName = $(this).val();
            Obj.filterAulaType = $('#filterAulaType').val();
            $("#alert").addClass("hidden");

            $.ajax({
                url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-responsive").html(respuesta);
                }
            });
        }
    });
});

$('#filterAulaType').on('change', function () {
    const Obj = {};
    Obj.filterAulaType = $('#filterAulaType').val();
    Obj.filterAulaName = $('#filterAulaName').val();
    $("#alert").addClass("hidden");

    $.ajax({
        url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
});

// Function to show the table
function mostrarTabla() {
    const Obj = {};
    Obj.tabla = "";

    $.ajax({
        url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
}

mostrarTabla();