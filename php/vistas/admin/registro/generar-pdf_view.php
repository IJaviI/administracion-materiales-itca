<div class="page">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>registro-prestamos" class="page__card">
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

        <a href="<?php echo RUTA;?>generar-excel" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important; padding: .4rem'>
                <use xlink:href="img/SVG/sprite.svg#excel"></use>
            </svg>
            <div class="page__card-block">
                Generar excel de prestamos
                <span>Genera un excel apartir del registro de prestamos</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>
    </div>
    <div class="form-container">
        <form class='inside-form' id='general-form' action="php/controladores/generarPdf.php" method="post">
            <div class="form__division"></div>
            <h2 class='form__heading'>Genera un pdf de registro de prestamos</h2>
            <div class="form__division"></div>
            <div class='form__row'>
                <div class="flex_row select-none" style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 3.5rem">
                    <a id='btn-fechas' class="page__card" style='flex: 1 25rem; border: 2px var(--border)'>
                        <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important; padding: .4rem'>
                            <use xlink:href="img/SVG/sprite.svg#historial"></use>
                        </svg>
                        <div class="page__card-block">
                            Fechas
                            <span>Genera en base a rango de fechas</span>
                        </div>
                        <svg class="page__card-icon page__card-icon--right">
                            <use xlink:href="img/SVG/sprite.svg#eye"></use>
                        </svg>
                    </a>

                    <a id='btn-user' class="page__card" style='flex: 1 0 25rem; border: 2px var(--border)'>
                        <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important;'>
                            <use xlink:href="img/SVG/sprite.svg#user"></use>
                        </svg>
                            <div class="page__card-block">
                            Usuario 
                            <span>Genera en base a usuario</span>
                        </div>
                        <svg class="page__card-icon page__card-icon--right">
                            <use xlink:href="img/SVG/sprite.svg#eye"></use>
                        </svg>
                    </a>
                </div>
            </div>

        <div id='generate-rango-fechas' class="form__row hidden">
            <div class="page__filters" style="margin-bottom: 2rem; gap: 4rem">
                <div class="page__filter" style='flex: 1 1 20rem'>
                    <label for="filterFechaDestinoFrom" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Fecha destino del prestamo (desde)</label>
                    <input id="filterFechaDestinoFrom" name="fecha_from" type="date" class="form__input">
                </div>
                <div class="page__filter" style='flex: 1 1 20rem'>
                    <label for="filterFechaDestinoTo" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Fecha destino del prestamo (hasta)</label>
                    <input id="filterFechaDestinoTo" name="fecha_to" type="date" class="form__input">
                </div>
                <div class="page__filter" style='flex: 1 1 20rem'>
                    <label for="filterEstadoFechas" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Estado de los prestamos</label>
                    <select id="filterEstadoFechas" name="stateFechas" class="page__filter form__input">
                        <option selected value="0">Seleccionar estado</option>
                        <option value="Devuelto">Devuelto</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Cancelado">Cancelado</option>
                        <option value="Cancelado">Todos</option>
                    </select>
                </div>
                <div class="page__filter" style='flex: 1 1 20rem'>
                    <label for="filterDepto" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Departamento</label>
                    <?php
                        $isThereDeptos = $obj_departamentos -> consult();
                        $print = "";
                        if(mysqli_num_rows($isThereDeptos) >= 1) {
                            $print.= "
                            <select id='filterDepto' name='deptoFechas' required class='form__input form__select'>
                                <option selected value='0' class='disabled'>Seleccionar departamento</option>";
                                $deptos = $obj_departamentos -> consult();
                                foreach($deptos as $fila){
                                    $print.= "<option value='$fila[id_depto]'>$fila[depto]</option>";
                                }

                                $print.="
                                <option value='Todos'>Todos</option>
                            </select>
                            ";
                        } else {
                            $print.= "
                            <div class='warning-depto'>
                                <p>No existe ningun departamento para generar este pdf.</p>
                            </div>
                            ";
                        }

                        echo $print;
                    ?>
                </div>
            </div>

            <button type="button" id='btn-generate-fechas' class='form__btn no-margin'>
                <span class="create-text">Generar pdf</span>
                <svg class="btn-check hidden">
                    <use xlink:href="img/SVG/sprite.svg#check"></use>
                </svg>
            </button>
        </div>

        <div id='generate-user' class="form__row hidden">
            <div class="page__filters" style="margin-bottom: 2rem">
                <div class="page__filter" style='flex: 1 1 20rem;'>
                    <label for="usuario_presto" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Usuario que presto</label>
                    <?php
                        $isThereUsers = $obj_usuarios -> consult();
                        $print = "";
                        if(mysqli_num_rows($isThereUsers) >= 1) {
                            $print.= "
                            <select id='usuario_presto' name='user' required class='form__input form__select'>
                                <option selected value='0' class='disabled'>Seleccionar usuario</option>";
                                $users = $obj_usuarios -> consult();
                                foreach($users as $fila){
                                    $print.= "<option value='$fila[id_usuario]'>$fila[nom_usuario] $fila[ape_usuario]</option>";
                                }

                                $print.="
                            </select>
                            ";
                        } else {
                            $print.= "
                            <div class='warning-depto'>
                                <p>No existe ningun usuario para generar este pdf.</p>
                            </div>
                            ";
                        }

                        echo $print;
                    ?>
                </div>
                <div class="page__filter" style='flex: 1 1 20rem'>
                    <label for="filterEstadoUser" class='page__heading' style="display: block; font-size: var(--font-app); text-align: left; margin-left: .3rem; margin-bottom: .5rem">Estado de los prestamos</label>
                    <select id="filterEstadoUser" name='stateUser' class="page__filter form__input">
                        <option selected value="0">Seleccionar estado</option>
                        <option value="Devuelto">Devuelto</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Cancelado">Cancelado</option>
                        <option value="Cancelado">Todos</option>
                    </select>
                </div>
            </div>

            <button type="button" id='btn-generate-user' class='form__btn no-margin'>
                <span class="create-text">Generar pdf</span>
                <svg class="btn-check hidden">
                    <use xlink:href="img/SVG/sprite.svg#check"></use>
                </svg>
            </button>
        </div>
        </form>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp"></div>
<script src="js/admin/registro/generar-archivo.js"></script>