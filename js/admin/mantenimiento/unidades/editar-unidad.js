"use strict";

// When clicking the button "Guardar"
$("#guardar").click(function () {
    validate();
});

// When clicking the key "Enter" when writing on the input
$("#page").focus();
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Doing something with events on the input like changing the color of the border
$('#unidad_name').each(function () {
    // Save current value of element
    $(this).data('oldValue', $(this));

    // Look for changes in the value
    $(this).bind("propertychange input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldValue') != $(this).val()) {
            // Updated stored value
            $(this).data('oldValue', $(this).val());

            btnInitialState();
            $('#unidad_name').css('borderColor', 'var(--color-purple)');
            $("#alert").addClass("hidden");
        }
    });

    $(this).on("focusout", function (event) {
        // changing the color of the border
        $(this).css("border", "3px solid var(--color-light)");
    });

    $(this).on("focus", function (event) {
        // changing the color of the border
        $(this).css("border", "3px solid var(--color-purple)");
    });
});

// Changing the btn "Guardar" text and showing the check icon
function btnStateChange() {
    const btnText = document.querySelector('.guardar-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Guardado";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Guardar" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.guardar-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Guardar";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
$("#unidad_name").data('inicialValue', $("#unidad_name").val());
function validate() {
    if ($("#unidad_name").data('inicialValue') == $("#unidad_name").val()) {
        $("#unidad_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();

        setTimeout(function () { alerts("Huh?, el campo a editar (Nombre de la unidad) ya posee este valor, para editar asigne un nuevo nombre."); }, 1);
    } else if (!$("#unidad_name").val() == "") {
        if (!containsSpecialCharsNombre($('#unidad_name').val())) {
            // Passing the object to the ajax for ading, deleting from the table
            const Obj = {};
            Obj.unidad_idUpdate = $("#unidad_id").val();
            Obj.unidad_nameEdit = $("#unidad_name").val();

            $.ajax({
                url: "php/ajax/ajax_unidades.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else {
            hideAlert();
            alertRed();
            setTimeout(function () { alerts('Error, asegurese que el campo a editar no contenga ningun caracter especial.'); }, 10);
        }
    } else {
        $("#unidad_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe rellenar el campo solicitado para editar la unidad"); }, 10);
    }
}