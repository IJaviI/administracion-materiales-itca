<?php
$isThereDeptos = $obj_departamentos -> consult();
?>

<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>ingreso-materiales" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important; padding: .4rem'>
                <use xlink:href="img/SVG/sprite.svg#equipo"></use>
            </svg>
            <div class="page__card-block">
                Ingreso de materiales
                <span>Genera ingreso de materiales</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>registro-prestamos" class="page__card hide">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important;'>
                <use xlink:href="img/SVG/sprite.svg#historial-2"></use>
            </svg>
                <div class="page__card-block">
                Registro de prestamos
                <span>Administra el registro de todos los prestamos</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="form-container">
        <form class='inside-form' id='general-form' action="" method="post">
            <div class="form__division"></div>
            <h2 class='form__heading'>Genera control de entrega de inventario</h2>
            <div class="form__division"></div>

            <div id='generate-rango-fechas' class="form__row">
                <div class="page__filters" style="margin-bottom: 2rem;">
                    <label for="filtrarFechaIngresoDesde" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: -.5rem">Fecha de ingreso del material (desde)</label>
                    <input id="filtrarFechaIngresoDesde" name="fecha_ingreso_desde" type="date" class="form__input">

                    <label for="filtrarFechaIngresoHasta" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: -.5rem">Fecha de ingreso del material (hasta)</label>
                    <input id="filtrarFechaIngresoHasta" name="fecha_ingreso_hasta" type="date" class="form__input">

                    <label for="filterDepto" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: -.5rem">Departamento de los materiales</label>
                    <?php
                        $print="";
                        if(mysqli_num_rows($isThereDeptos) >= 1) {
                            $print.= "
                            <select id='filterDepto' name='depto' required class='form__input form__select'>
                                <option selected value='0' class='disabled'>Seleccionar departamento</option>";
                                $deptos = $obj_departamentos -> consult();
                                foreach($deptos as $fila){
                                    $print.= "<option value='$fila[id_depto]'>$fila[depto]</option>";
                                }
                                
                                $print.="
                            </select>
                            ";
                        } else {
                            $print.= "
                            <div class='warning-depto'>
                                <p>No existe ningun departamento para generar este documento.</p>
                            </div>
                            ";
                        }
                        
                        echo $print;
                    ?>
                </div>

                <?php
                    $print="";
                    if(mysqli_num_rows($isThereDeptos) >= 1) {
                        $print.= "
                        <button type='button' id='btn-generate' class='form__btn'>
                            <span class='create-text'>Generar documento</span>
                        </button>
                        ";
                    }

                    echo $print;
                ?>
            </div>
        </form>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp"></div>

<script src="js/admin/generar/generar-control-de-entrega-de-inventario.js"></script>
