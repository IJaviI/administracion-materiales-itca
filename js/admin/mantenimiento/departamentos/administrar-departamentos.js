"use strict";

// function for when the btn delete is clicked, called on the "onclick" attatch the button
function btnDeleteClicked(id) {
    const Obj = {};
    Obj.depto_idRemove = id;
    Obj.filterDeptoName = $('#filterDeptoName').val();
    if (!containsSpecialCharsNombre($('#filterDeptoName').val())) {
        $.ajax({
            url: "php/ajax/ajax_departamentos.php?" + $.param(Obj), success: function (respuesta) {
                $("#table-responsive").html(respuesta);
            }
        });
    }
}

// function for when the btn edit is clicked, called on the "onclick" attatch the button
function btnEditClicked(id) {
    const Obj = {};
    Obj.depto_idEdit = id;

    $.ajax({
        url: "php/ajax/ajax_departamentos.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
}

// Filter working
$('#filterDeptoName').each(function () {
    // Save current value of element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldVal') != $(this).val()) {
            // Updated stored value
            $(this).data('oldVal', $(this).val());

            if (!$(this).val() == "") {
                if (!containsSpecialCharsNombre($(this).val())) {
                    // Filtering data on table
                    const Obj = {};
                    Obj.filterDeptoName = $(this).val();
                    hideAlert();

                    $.ajax({
                        url: "php/ajax/ajax_departamentos.php?" + $.param(Obj), success: function (respuesta) {
                            $("#table-responsive").html(respuesta);
                        }
                    });
                } else {
                    alertRed();
                    alerts('Error, asegurese que el filtro por nombre no contenga ningun caracter especial.');
                }
            } else {
                mostrarTabla();
            }
        }
    });
});

// Function to show the table
function mostrarTabla() {
    const Obj = {};
    Obj.tabla = "";

    $.ajax({
        url: "php/ajax/ajax_departamentos.php?" + $.param(Obj), success: function (respuesta) {
            $("#table-responsive").html(respuesta);
        }
    });
}

mostrarTabla();