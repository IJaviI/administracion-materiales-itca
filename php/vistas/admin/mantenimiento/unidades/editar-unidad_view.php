<div class="page" id="page" tabindex="3">
<div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-unidad" class="page__card">
            <img class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3rem !important' src="img/SVG/svgs/unit2.svg"></img>
            <div class="page__card-block">
                Agregar unidad
                <span>Crea una nueva unidad</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-unidades" class="page__card">
            <img class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3rem !important' src="img/SVG/svgs/unit2.svg"></img>
            <div class="page__card-block">
                Administrar unidades
                <span>Administra unidades existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Edita esta unidad</h2>
        <div class="page__division"></div>
        <div class="table-responsive">
            <table class="page__table">
                <thead class="page__table-head">
                    <tr class="page__table-row">    
                        <th>Nombre de la unidad</th>
                        <th style="text-align: center">Acciones</th>
                    </tr>
                </thead>
                
                <form>
                    <tbody class="page__table-body">
                        <?php
                        if(!isset($_SESSION["unidad"])){
                            echo "
                            <tr class='page__table-row'>
                                <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha seleccionado un departamento a editar.</td>
                            </tr>
                            ";    
                        }
                        ?>
                        <tr class="page__table-row" <?php if(!isset($_SESSION["unidad"])){echo "style='display: none'";}?>>
                            <td class="no-padding">
                                <input  type="hidden" id="unidad_id" value="<?php if(isset($_SESSION["unidad"])){ echo $_SESSION["unidad"]["id"];}?>">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["unidad"])){ echo $_SESSION["unidad"]["unidad_name"];}?>" class="page__table-row-input" id="unidad_name" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Nombre de la unidad">
                            </td>
                            <td class="no-padding">
                                <button type="button" id="guardar">
                                    <span class="guardar-text">Guardar</span>
                                    <svg class="btn-check hidden">
                                        <use xlink:href="img/SVG/sprite.svg#check"></use>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
        </div>
    </div>
</div>




<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp" ></div>

<script src="js/admin/mantenimiento/unidades/editar-unidad.js"></script>