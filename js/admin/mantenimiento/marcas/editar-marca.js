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
$('#marca_name').each(function () {
    // Save current value of element
    $(this).data('oldValue', $(this));

    // Look for changes in the value
    $(this).bind("propertychange input paste", function (event) {
        // If value has changed...
        if ($(this).data('oldValue') != $(this).val()) {
            // Updated stored value
            $(this).data('oldValue', $(this).val());

            btnInitialState();
            $('#marca_name').css('borderColor', 'var(--color-purple)');
            hideAlert();
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
$("#marca_name").data('inicialValue', $("#marca_name").val());
function validate() {
    if ($("#marca_name").data('inicialValue') == $("#marca_name").val()) {
        $("#marca_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Huh?, el campo a editar (Nombre de la marca) ya posee este valor, para editar asigne un nuevo nombre."); }, 1);
    }
    else if (!$("#marca_name").val() == "") {
        if (!containsSpecialCharsNombre($('#marca_name').val())) {
            // Passing the object to the ajax for editing the table
            const Obj = {};
            Obj.marca_idUpdate = $("#marca_id").val();
            Obj.marca_nameEdit = $("#marca_name").val();

            $.ajax({
                url: "php/ajax/ajax_marcas.php?" + $.param(Obj), success: function (respuesta) {
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
        $("#marca_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe rellenar el campo solicitado para editar esta marca"); }, 10);
    }
}