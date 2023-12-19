"use strict";

function isValidPrice(str) {
    const specialChars = /^\d+(\.\d{0,2})?$/;
    return specialChars.test(str);
}

// When clicking the button "Crear equipo"
$("#create-equipo").click(function () {
    validate();
});

// When clicking the key "Enter" when writing on the input
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Calling the function inputChanges on the inputs
$('#equipo_name').each(function () {
    inputChanges(this);
});

$('#equipo_serie').each(function () {
    inputChanges(this);
});

$('#equipo_description').each(function () {
    inputChanges(this);
});

$('#equipo_tipo').each(function () {
    inputChanges(this);
});

$('#equipo_precio').each(function () {
    inputChanges(this);
});

$('#equipo_stock').each(function () {
    inputChanges(this);
});

$('#equipo_depto').each(function () {
    inputChanges(this);
});

$('#equipo_marca').each(function () {
    inputChanges(this);
});

$('#equipo_unidad').each(function () {
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

            if ($($this).attr('id') == 'equipo_name') {
                $('#equipo_serie').css("border", "3px var(--border)");
                $('#equipo_modelo').css("border", "3px var(--border)");
            } else if ($($this).attr('id') == 'equipo_serie') {
                $('#equipo_name').css("border", "3px var(--border)");
                $('#equipo_modelo').css("border", "3px var(--border)");
            } else if ($($this).attr('id') == 'equipo_modelo') {
                $('#equipo_name').css("border", "3px var(--border)");
                $('#equipo_serie').css("border", "3px var(--border)");
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

    btnText.textContent = "Equipo creado";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Crear aula" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.create-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Crear equipo";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
function validate() {
    if (($("#equipo_name").val()) == "") {
        $("#equipo_name").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar el nombre del material."); }, 10);
    } else if (containsSpecialCharsNombre($('#equipo_name').val())) {
        $("#equipo_name").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, el nombre del material no debe contener ningun caracter especial."); }, 10);
    } else if (($("#equipo_serie").val()) == "") {
        $("#equipo_serie").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar el numero de serie del material."); }, 10);
    } else if (containsSpecialCharsNombre($('#equipo_serie').val())) {
        $("#equipo_serie").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, el numero de serie del material no debe contener ningun caracter especial."); }, 10);
    } else if ($("#equipo_description").val() == "") {
        $("#equipo_description").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar la descripcion del material."); }, 10);
    } else if (containsSpecialCharsDescripcion($('#equipo_description').val())) {
        $("#equipo_description").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts('Error, a exepcion de los caracteres !;:.,"() asegurese que la descripcion del aula no contenga ningun otro caracter especial.'); }, 10);
    } else if (($("#equipo_tipo").val()) == 0) {
        $("#equipo_tipo").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de seleccionar el tipo del material."); }, 10);
    } else if (($("#equipo_stock").val()) == "") {
        $("#equipo_stock").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar el stock del material."); }, 10);
    } else if (($("#equipo_stock").val()) == 0) {
        $("#equipo_stock").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, si se desea crear un material el stock debe ser de minimo 1."); }, 10);
    }  else if (($('#equipo_precio').val()) == "") {
        $("#equipo_precio").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de rellenar el precio del material."); }, 10);
    } else if (!isValidPrice($("#equipo_precio").val())) {
        $("#equipo_precio").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de ingresar un precio valido, ej: 4.55"); }, 10);
    } else if (($("#equipo_unidad").val()) == 0) {
        $("#equipo_unidad").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de seleccionar la unidad de la cantidad del material."); }, 10);
    } else if (($("#equipo_depto").val()) == 0) {
        $("#equipo_depto").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de seleccionar el departamento al que pertenece el material."); }, 10);
    } else if (($("#equipo_marca").val()) == 0) {
        $("#equipo_marca").css("borderColor", "var(--color-wrong)");
        hideAlert()
        setTimeout(function () { alerts("Error, asegurese de seleccionar la marca a la que pertenece el material."); }, 10);
    } else {
        // Passing the object to the ajax for adding to the table
        const Obj = {};
        Obj.addEquipo = "";
        Obj.equipo_name = $("#equipo_name").val();
        Obj.equipo_serie = $("#equipo_serie").val();
        Obj.equipo_description = $("#equipo_description").val();
        Obj.equipo_tipo = $("#equipo_tipo").val();
        Obj.equipo_stock = $("#equipo_stock").val();
        Obj.equipo_precio = $("#equipo_precio").val();
        Obj.equipo_depto = $("#equipo_depto").val();
        Obj.equipo_marca = $("#equipo_marca").val();
        Obj.equipo_unidad = $("#equipo_unidad").val();

        $.ajax({
            url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (response) {
                $("#resp").html(response);
            }
        });
        return true;
    }
}

// Adding with a csv file
$('#csv_equipos').change(function () {
    let equipos_data = new FormData();
    let file = $('#csv_equipos')[0].files;

    equipos_data.append('csv_equipos', file[0]);
    $.ajax({
        url: "php/ajax/ajax_equipos.php",
        type: "POST",
        data: equipos_data,
        processData: false, // Importante usar al pasar un FormData
        contentType: false, // Importante usar al pasar un FormData
        success: function (response) {
            $("#resp").html(response);
        }
    });
});