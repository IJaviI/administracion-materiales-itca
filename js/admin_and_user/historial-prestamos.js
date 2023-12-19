"use strict";

function cancelPrestamo($id) {
    $.ajax({
        url: "php/ajax/ajax_prestamo.php",
        data: {
            cancel_option2: "",
            id_prestamo: $id
        },
        type: "GET",
        success: function (response) {
            $('#resp').html(response);
            mostrarHistorial();
        }
    });
}

function mostrarHistorial() {
    $.ajax({
        url: "php/ajax/ajax_prestamo.php",
        data: {
            tablaPrestamos: ""
        },
        type: "POST",
        success: function (response) {
            $('#table-historial-prestamos').html(response);
        }
    });
}

mostrarHistorial();