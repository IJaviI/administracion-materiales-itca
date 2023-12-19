"use strict";

// When clicking the button "Crear marca"
$("#create-marca").click(function () {
    validate();
});

// When clicking the key "Enter" when writing on the input
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Doing something with events on the input like changing the color of the border
$('#marca_name').each(function () {
    // Save current value of element
    $(this).data('oldVal', $(this));

    // Look for changes in the value
    $(this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldVal') != $(this).val()) {
            // Updated stored value
            $(this).data('oldVal', $(this).val());

            btnInitialState();
            $('#marca_name').css('borderColor', 'var(--color-purple)');
            hideAlert();
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

// Changing the btn "Crear marca" text and showing the check icon
function btnStateChange() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Marca creada";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Crear marca" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Crear marca";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
function validate() {
    if (!$("#marca_name").val() == "") {
        if (!containsSpecialCharsNombre($('#marca_name').val())) {
            // Passing the object to the ajax for adding to the table
            const Obj = {};
            Obj.marca_nameAdd = $("#marca_name").val();

            $.ajax({
                url: "php/ajax/ajax_marcas.php?" + $.param(Obj), success: function (response) {
                    $("#resp").html(response);
                }
            });
            return true;
        } else {
            hideAlert();
            alertRed();
            setTimeout(function () { alerts('Error, el nombre de la marca no puede contener ningun caracter especial.'); }, 10);
        }
    } else {
        $("#marca_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe rellenar el campo solicitado para crear una marca."); }, 10)
    }
}

// Adding with a csv file
$('#csv_marcas').change(function () {
    let depto_data = new FormData();
    let file = $('#csv_marcas')[0].files;

    depto_data.append('csv_marcas', file[0]);
    $.ajax({
        url: "php/ajax/ajax_marcas.php",
        type: "POST",
        data: depto_data,
        processData: false, // Importante usar al pasar un FormData
        contentType: false, // Importante usar al pasar un FormData
        success: function (response) {
            $("#resp").html(response);
        }
    });
});