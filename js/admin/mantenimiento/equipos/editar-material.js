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

$('#equipo_stock').each(function () {
    inputChanges(this);
});

$('#equipo_price').each(function () {
    inputChanges(this);
});

$('#equipo_unidad').each(function () {
    inputChanges(this);
});

$('#equipo_marca').each(function () {
    inputChanges(this);
});

$('#equipo_depto').each(function () {
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

            if ($($this).attr('id') == "equipo_name") {
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "equipo_serie") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "equipo_description") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "equipo_price") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_stock") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_tipo") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_depto") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_marca") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
                $('#equipo_unidad').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_unidad") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_price').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_tipo').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
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
$('#equipo_name').data('inicialValue', $('#equipo_name').val());
$('#equipo_serie').data('inicialValue', $('#equipo_serie').val());
$('#equipo_description').data('inicialValue', $('#equipo_description').val());
$('#equipo_tipo').data('inicialValue', $('#equipo_tipo').val());
$('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
$('#equipo_precio').data('inicialValue', $('#equipo_price').val());
$('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
$('#equipo_depto').data('inicialValue', $('#equipo_depto').val());
$('#equipo_unidad').data('inicialValue', $('#equipo_unidad').val());

function validate() {
    if ($("#equipo_name").data('inicialValue') == $("#equipo_name").val() && $("#equipo_serie").data('inicialValue') == $("#equipo_serie").val() && $("#equipo_description").data('inicialValue') == $("#equipo_description").val() && $("#equipo_tipo").data('inicialValue') == $("#equipo_tipo").val() && $("#equipo_stock").data('inicialValue') == $("#equipo_stock").val() && $("#equipo_precio").data('inicialValue') == $("#equipo_precio").val() && $("#equipo_marca").data('inicialValue') == $("#equipo_marca").val() && $("#equipo_depto").data('inicialValue') == $("#equipo_depto").val() && $("#equipo_unidad").data('inicialValue') == $("#equipo_unidad").val()) {
        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Huh?, no se ha detectado un cambio en ninguno de los campos a editar, para editar asigne un nuevo valor al menos a uno de los campos de este material."); }, 10);

        $('#equipo_name').css("borderColor", "var(--color-wrong)");
        $('#equipo_serie').css("borderColor", "var(--color-wrong)");
        $('#equipo_description').css("borderColor", "var(--color-wrong)");
        $('#equipo_modelo').css("borderColor", "var(--color-wrong)");
        $('#equipo_stock').css("borderColor", "var(--color-wrong)");
        $('#equipo_tipo').css("borderColor", "var(--color-wrong)");
        $('#equipo_price').css("borderColor", "var(--color-wrong)");
        $('#equipo_marca').css("borderColor", "var(--color-wrong)");
        $('#equipo_depto').css("borderColor", "var(--color-wrong)");
        $('#equipo_unidad').css("borderColor", "var(--color-wrong)");
    } else if ($("#equipo_name").val() == "") {
        $("#equipo_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un nombre para editar este material."); }, 10);
    } else if ($("#equipo_serie").val() == "") {
        $("#equipo_serie").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un numero de serie para editar este material."); }, 10);
    } else if ($("#equipo_description").val() == "") {
        $("#equipo_description").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar una descripcion para editar este material."); }, 10);
    } else if ($("#equipo_price").val() == "") {
        $("#equipo_price").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un precio para editar este material."); }, 10);
    } else if ($("#equipo_stock").val() == "") {
        $("#equipo_stock").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, la cantidad no puede ser menor a 1."); }, 10);
    } else if (containsSpecialCharsGuion($("#equipo_serie").val())) {
        $("#equipo_serie").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese que el numero de serie no contenga ningun caracter especial a exepcion del guion (-).'); }, 10);
    } else if (containsSpecialCharsDescripcion($("#equipo_description").val())) {
        $("#equipo_description").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, a exepcion de los caracteres !;:.,"() asegurese que la descripcion del aula no contenga ningun otro caracter especial.'); }, 10);
    } else {
        if ($("#equipo_name").data('inicialValue') != $("#equipo_name").val()|| $("#equipo_serie").data('inicialValue') != $("#equipo_serie").val()) {
            const Obj = {};
            Obj.equipo_idEdit = $("#equipo_id").val();
            Obj.equipo_nameEdit = $("#equipo_name").val();
            Obj.equipo_serieEdit = $("#equipo_serie").val();
            Obj.equipo_descriptionEdit = $("#equipo_description").val();
            Obj.equipo_modeloEdit = $("#equipo_modelo").val();
            Obj.equipo_stockEdit = $("#equipo_stock").val();
            Obj.equipo_marcaEdit = $("#equipo_marca").val();
            Obj.equipo_deptoEdit = $("#equipo_depto").val();

            $.ajax({
                url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else {
            const Obj = {};
            Obj.equipo_RestOfFields = "";
            Obj.equipo_idEdit = $("#equipo_id").val();
            Obj.equipo_descriptionEdit = $("#equipo_description").val();
            Obj.equipo_tipoEdit = $("#equipo_tipo").val();
            Obj.equipo_stockEdit = $("#equipo_stock").val();
            Obj.equipo_priceEdit = $("#equipo_price").val();
            Obj.equipo_marcaEdit = $("#equipo_marca").val();
            Obj.equipo_deptoEdit = $("#equipo_depto").val();
            Obj.equipo_unidadEdit = $("#equipo_unidad").val();

            $.ajax({
                url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        }
    }
}