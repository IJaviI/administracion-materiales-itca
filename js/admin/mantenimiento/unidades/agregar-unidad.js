"use strict";

// Cuando clickeamos en "Crear departamento"
$("#create-unidad").click(function () {
    validate();
});

// Cuando clickeamos la tecla "Enter"
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Doing something with events on the input like changing the color of the border
$('#unidad_name').each(function () {
    // Save current value of element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldVal') != $(this).val()) {
            // Updated stored value
            $(this).data('oldVal', $(this).val());

            btnInitialState();
            $('#unidad_name').css('borderColor', 'var(--color-purple)');
            $("#alert").addClass("hidden");
        }
    });

    $(this).on("focusout", function (event) {
        // changing the color of the border
        $(this).css("border", "3px var(--border)");
    });

    $(this).on("focus", function (event) {
        // changing the color of the border
        $(this).css("border", "3px solid var(--color-purple)");
    });
});

// Changing the btn "Crear unidad" text and showing the check icon
function btnStateChange() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Unidad creada";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Crear unidad" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Crear unidad";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
function validate() {
    if (!$("#unidad_name").val() == "") {
        if (!containsSpecialCharsNombre($('#unidad_name').val())) {
            // Passing the object to the ajax for adding to table
            const Obj = {};
            Obj.unidad_nameAdd = $("#unidad_name").val();

            $.ajax({
                url: "php/ajax/ajax_unidades.php",
                type: "GET",
                data: Obj,
                success: function (response) {
                    $("#resp").html(response);
                }
            });
            return true;
        } else {
            hideAlert();
            alertRed();
            setTimeout(function () { alerts('Error, el nombre de la unidad no puede contener ningun caracter especial.'); }, 1);
        }
    } else {
        $("#unidad_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese de rellenar el nombre de la unidad.'); }, 1);
    }
}

// Adding with a csv file
$('#csv_unidades').change(function () {
    let unidad_data = new FormData();
    let file = $('#csv_unidades')[0].files;

    unidad_data.append('csv_unidades', file[0]);
    $.ajax({
        url: "php/ajax/ajax_unidades.php",
        type: "POST",
        data: unidad_data,
        processData: false, // Importante usar al pasar un FormData
        contentType: false, // Importante usar al pasar un FormData
        success: function (response) {
            $("#resp").html(response);
        }
    });
});