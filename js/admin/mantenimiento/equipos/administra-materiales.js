// function for when the btn delete is clicked, called on the "onclick" attatch the button
    function btnDeleteClicked(id) {
    const Obj = {};
        Obj.equipo_idRemove = id;
        Obj.filterEquipoName = $('#filterEquipoName').val();
        Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
        Obj.filterEquipoDepto = $('#filterEquipoDepto').val();
        Obj.filterEquipoModelo = $('#filterEquipoModelo').val();

        if (!containsSpecialCharsNombre($('#filterDeptoName').val())) {
            $.ajax({
                url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#table-responsive").html(respuesta);
                }
            });
        }
    }

    // function for when the btn edit is clicked, called on the "onclick" attatch the button
    function btnEditClicked(id) {
        const Obj = {};
        Obj.equipo_sendToEdit = id;

        $.ajax({
            url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                $("#table-responsive").html(respuesta);
            }
        });
    }

    // Filtros
    $('#filterEquipoName').each(function () {
        // Save current value of element
        $(this).data('oldVal', $(this));

        // Look for changes in the value
        $(this).bind("propertychange keyup keydown input paste", function (event) {
            // If value has changed...
            if ($(this).data('oldVal') != $(this).val()) {
                // Updated stored value
                $(this).data('oldVal', $(this).val());

                // Filtering data on table
                const Obj = {};
                Obj.filterEquipoName = $(this).val();
                Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
                Obj.filterEquipoDepto = $('#filterEquipoDepto').val();
                Obj.filterEquipoModelo = $('#filterEquipoModelo').val();


                $("#alert").addClass("hidden");

                $.ajax({
                    url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-responsive").html(respuesta);
                    }
                });
            }
        });
    });

    $('#filterEquipoMarca').each(function () {
        // Save current value of element
        $(this).data('oldVal', $(this));

        // Look for changes in the value
        $(this).bind("propertychange keyup keydown input paste", function (event) {
            // If value has changed...
            if ($(this).data('oldVal') != $(this).val()) {
                // Updated stored value
                $(this).data('oldVal', $(this).val());

                // Filtering data on table
                const Obj = {};
                Obj.filterEquipoName = $('#filterEquipoName').val();
                Obj.filterEquipoMarca = $(this).val();
                Obj.filterEquipoDepto = $('#filterEquipoDepto').val();
                Obj.filterEquipoModelo = $('#filterEquipoModelo').val();

                $("#alert").addClass("hidden");

                $.ajax({
                    url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-responsive").html(respuesta);
                    }
                });
            }
        });
    });

    $('#filterEquipoDepto').each(function () {
        // Save current value of element
        $(this).data('oldVal', $(this));

        // Look for changes in the value
        $(this).bind("propertychange keyup keydown input paste", function (event) {
            // If value has changed...
            if ($(this).data('oldVal') != $(this).val()) {
                // Updated stored value
                $(this).data('oldVal', $(this).val());

                // Filtering data on table
                const Obj = {};
                Obj.filterEquipoName = $('#filterEquipoName').val();
                Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
                Obj.filterEquipoDepto = $(this).val();
                Obj.filterEquipoModelo = $('#filterEquipoModelo').val();

                $("#alert").addClass("hidden");

                $.ajax({
                    url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-responsive").html(respuesta);
                    }
                });
            }
        });
    });

    $('#filterEquipoModelo').each(function () {
        // Save current value of element
        $(this).data('oldVal', $(this));

        // Look for changes in the value
        $(this).bind("propertychange keyup keydown input paste", function (event) {
            // If value has changed...
            if ($(this).data('oldVal') != $(this).val()) {
                // Updated stored value
                $(this).data('oldVal', $(this).val());

                // Filtering data on table
                const Obj = {};
                Obj.filterEquipoName = $('#filterEquipoName').val();
                Obj.filterEquipoMarca = $('#filterEquipoMarca').val();
                Obj.filterEquipoDepto = $('#filterEquipoDepto').val();
                Obj.filterEquipoModelo = $(this).val();

                $("#alert").addClass("hidden");

                $.ajax({
                    url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                        $("#table-responsive").html(respuesta);
                    }
                });
            }
        });
    });

    // Function to show the table
    function mostrarTabla() {
        const Obj = {};
        Obj.tabla = "";

        $.ajax({
            url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                $("#table-responsive").html(respuesta);
            }
        });
    }

    mostrarTabla();