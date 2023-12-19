"use strict";

// When clicking the button "Crear aula"
$("#create-aula").click(function () {
    validate();
});

// When clicking the key "Enter" when writing on the input
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

            if ($($this).attr('id') == "aula_type") {
                $('#aula_name').css("border", "3px var(--border)");
            }

            $("#alert").addClass("hidden");
        }
    });

    $($this).on("focusout", function (event) {
        // changing the color of the border
        $($this).css("border", "3px var(--border)");
    });

    $($this).on("focus", function (event) {
        // changing the color of the border
        $($this).css("border", "3px solid var(--color-purple)");
    });
}

// Changing the btn "Crear aula" text and showing the check icon
function btnStateChange() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Aula creada";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Crear aula" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Crear aula";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
function validate() {
    if (($("#aula_name").val()) == "") {
        $("#aula_name").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar el nombre del aula."); }, 10);
    } else if (($("#aula_ubication").val()) == "") {
        $("#aula_ubication").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar la ubicacion."); }, 10);
    } else if ($("#aula_description").val() == "") {
        $("#aula_description").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar la descripcion."); }, 10);
    } else if ($("#aula_type").val() != "0" && $("#aula_type").val() != "1") {
        $("#aula_type").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de seleccionar el tipo."); }, 10);
    } else {
        // Passing the object to the ajax for adding to the table
        const Obj = {};
        Obj.aula_name = $("#aula_name").val();
        Obj.aula_ubication = $("#aula_ubication").val();
        Obj.aula_description = $("#aula_description").val();
        Obj.aula_type = $("#aula_type").val();

        $.ajax({
            url: "php/ajax/ajax_aulas.php?" + $.param(Obj), success: function (response) {
                $("#resp").html(response);
            }
        });
        return true;
    }
}

// Adding with a csv file
$('#csv_aulas').change(function () {
    let aulas_data = new FormData();
    let file = $('#csv_aulas')[0].files;

    aulas_data.append('csv_aulas', file[0]);
    $.ajax({
        url: "php/ajax/ajax_aulas.php",
        type: "POST",
        data: aulas_data,
        processData: false, // Importante usar al pasar un FormData
        contentType: false, // Importante usar al pasar un FormData
        success: function (response) {
            $("#resp").html(response);
        }
    });
});