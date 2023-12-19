"use strict";

// When clicking the button "Guardar"
$("#guardar").click(function () {
    validate();
});

$("#page").focus();
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Calling the function inputChanges on the inputs
$('#aula_name').each(function () {
    inputChanges(this);
});

$('#aula_ubication').each(function () {
    inputChanges(this);
});

$('#aula_description').each(function () {
    inputChanges(this);
});

$('#aula_type').each(function () {
    inputChanges(this);
});

// Doing something with events on the input like changing the color of the border
function inputChanges($this) {
    // Save current value of element
    $($this).data('oldVal', $($this));

    // Look for changes in the value
    $($this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($($this).data('oldVal') != $($this).val()) {
            // Updated stored value
            $($this).data('oldVal', $($this).val());

            btnInitialState();
            $($this).css('borderColor', 'var(--color-purple)');

            if ($($this).attr('id') == "aula_name") {
                $('#aula_ubication').css("borderColor", "var(--color-light)");
                $('#aula_description').css("borderColor", "var(--color-light)");
                $('#aula_type').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "aula_ubication") {
                $('#aula_name').css("borderColor", "var(--color-light)");
                $('#aula_description').css("borderColor", "var(--color-light)");
                $('#aula_type').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "aula_description") {
                $('#aula_name').css("borderColor", "var(--color-light)");
                $('#aula_ubication').css("borderColor", "var(--color-light)");
                $('#aula_type').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "aula_type") {
                $('#aula_name').css("borderColor", "var(--color-light)");
                $('#aula_ubication').css("borderColor", "var(--color-light)");
                $('#aula_description').css("borderColor", "var(--color-light)");
            }

            hideAlert();
        }
    });

    $($this).on("focusout", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-light)");
    });

    $($this).on("focus", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-purple)");
    });
}

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
$("#aula_name").data('inicialValue', $("#aula_name").val());
$("#aula_ubication").data('inicialValue', $("#aula_ubication").val());
$("#aula_description").data('inicialValue', $("#aula_description").val());
$("#aula_type").data('inicialValue', $("#aula_type").val());

function validate() {
    if ($("#aula_name").data('inicialValue') == $("#aula_name").val() && $("#aula_ubication").data('inicialValue') == $("#aula_ubication").val() && $("#aula_description").data('inicialValue') == $("#aula_description").val() && $("#aula_type").data('inicialValue') == $("#aula_type").val()) {
        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Huh?, no se ha detectado un cambio en alguno de los campos a editar, para editar asigne un nuevo valor en uno de los campos de esta aula."); }, 1);

        $("#aula_name").css("borderColor", "var(--color-wrong)");
        $("#aula_ubication").css("borderColor", "var(--color-wrong)");
        $("#aula_description").css("borderColor", "var(--color-wrong)");
        $("#aula_type").css("borderColor", "var(--color-wrong)");
    } else if ($("#aula_name").val() == "") {
        $("#aula_name").css("borderColor", "var(--color-wrong)");

        alertRed();
        alerts("Error, debe asignar un nombre para editar esta marca.");
    } else if ($("#aula_ubication").val() == "") {
        $("#aula_ubication").css("borderColor", "var(--color-wrong)");

        alertRed();
        alerts("Error, debe asignar una ubicacion para editar esta marca.");
    } else if ($("#aula_description").val() == "") {
        $("#aula_description").css("borderColor", "var(--color-wrong)");

        alertRed();
        alerts("Error, debe asignar una descripcion para editar esta marca.");
    } else if (containsSpecialCharsNombre($("#aula_name").val())) {
        $("#aula_name").css("borderColor", "var(--color-wrong)");

        alertRed();
        alerts('Error, asegurese que el nombre del aula no contenga ningun caracter especial.');
    } else if (containsSpecialCharsUbicacion($("#aula_ubication").val())) {
        $("#aula_ubication").css("borderColor", "var(--color-wrong)");

        alertRed();
        alerts('Error, a exepcion de los caracteres ;:.,"() asegurese que la ubicacion del aula no contenga ningun otro caracter especial.');
    } else if (containsSpecialCharsDescripcion($("#aula_description").val())) {
        $("#aula_description").css("borderColor", "var(--color-wrong)");

        alertRed();
        alerts('Error, a exepcion de los caracteres !;:.,"() asegurese que la descripcion del aula no contenga ningun otro caracter especial.');
    } else {
        if ($("#aula_name").data('inicialValue') != $("#aula_name").val() && $("#aula_ubication").data('inicialValue') != $("#aula_ubication").val() && $("#aula_description").data('inicialValue') != $("#aula_description").val() && $("#aula_type").data('inicialValue') != $("#aula_type").val()) {
            const Obj = {};
            Obj.aula_idEdit = $("#aula_id").val();
            Obj.aula_nameEdit = $("#aula_name").val();
            Obj.aula_ubicationEdit = $("#aula_ubication").val();
            Obj.aula_descriptionEdit = $("#aula_description").val();
            Obj.aula_typeEdit = $("#aula_type").val();

            $.ajax({
                url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else if ($("#aula_name").data('inicialValue') != $("#aula_name").val() && ($("#aula_ubication").data('inicialValue') != $("#aula_ubication").val() || $("#aula_description").data('inicialValue') != $("#aula_description").val())) {
            const Obj = {};
            Obj.aula_idEdit = $("#aula_id").val();
            Obj.aula_nameEdit = $("#aula_name").val();
            Obj.aula_ubicationEdit = $("#aula_ubication").val();
            Obj.aula_descriptionEdit = $("#aula_description").val();
            Obj.aula_typeEdit = $("#aula_type").val();

            $.ajax({
                url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else if ($("#aula_name").data('inicialValue') != $("#aula_name").val() || $("#aula_type").data('inicialValue') != $("#aula_type").val()) {
            const Obj = {};
            Obj.aula_idEdit = $("#aula_id").val();
            Obj.aula_nameEdit = $("#aula_name").val();
            Obj.aula_typeEdit = $("#aula_type").val();

            $.ajax({
                url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else if ($("#aula_ubication").data('inicialValue') != $("#aula_ubication").val() || $("#aula_description").data('inicialValue') != $("#aula_description").val()) {
            // Passing the object to the ajax for editing the table
            const Obj = {};
            Obj.aula_idEdit = $("#aula_id").val();
            Obj.aula_ubicationEdit = $("#aula_ubication").val();
            Obj.aula_descriptionEdit = $("#aula_description").val();

            $.ajax({
                url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        }
    }
}