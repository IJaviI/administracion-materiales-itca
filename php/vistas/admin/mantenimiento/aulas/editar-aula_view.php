<div class="page" id="page" tabindex="6"  >
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-aula" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#aula"></use>
            </svg>
            <div class="page__card-block">
                Agregar aula
                <span>Crea una nueva aula</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-aulas" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#aulas"></use>
            </svg>
            <div class="page__card-block">
                Administrar aulas
                <span>Administra aulas existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Edita esta aula</h2>
        <div class="page__division"></div>
        <div class="table-responsive">
            <table class="page__table">
                <thead class="page__table-head">    
                    <tr class='page__table-row'>    
                        <th>Numero del aula o computo</th>
                        <th>Ubicacion</th>
                        <th>Descripcion</th>
                        <th>Tipo</th>
                        <th style='text-align: center'>Acciones</th>
                    </tr>
                </thead>
                
                <form>
                    <tbody class="page__table-body">
                        <?php
                        if(!isset($_SESSION["aula"])){
                            echo "
                            <tr class='page__table-row'>
                                <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha seleccionado un aula a editar.</td>
                            </tr>
                            ";    
                        }
                        ?>
                        <tr class='page__table-row' <?php if(!isset($_SESSION["aula"])){echo "style='display: none'";}?>>
                            <td class="no-padding">
                                <input type="hidden" id="aula_id" value="<?php if(isset($_SESSION["aula"])){ echo $_SESSION["aula"]["id"];}?>">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["aula"])){ echo $_SESSION["aula"]["aula_name"];}?>" class="page__table-row-input" id="aula_name" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Nombre del aula o computo">
                            </td>
                            <td class="no-padding" style="min-width: 300px">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["aula"])){ echo $_SESSION["aula"]["aula_ubication"];}?>" class="page__table-row-input" id="aula_ubication" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Ubicacion">
                            </td>
                            <td class="no-padding" style="min-width: 300px">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["aula"])){ echo $_SESSION["aula"]["aula_description"];}?>" class="page__table-row-input" id="aula_description" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Descripcion">
                            </td>
                            <td class="no-padding" style="min-width: 100px">
                                <?php $aula_or_computo = $_SESSION["aula"]["aula_type"] == 0 ? "Aula" : "Computo";?>
                                <select id="aula_type" class="page__table-row-input" style='padding-top: .9rem; padding-bottom: .9rem'>
                                    <?php
                                    if($_SESSION["aula"]["aula_type"] == 0) {
                                        echo "
                                        <option selected value='0'>$aula_or_computo</option>
                                        <option value='1'>Computo</option>
                                        ";
                                    } else if($_SESSION["aula"]["aula_type"] == 1) {
                                        echo "
                                        <option selected value='1'>$aula_or_computo</option>
                                        <option value='0'>Aula</option>
                                        ";
                                    }
                                    ?>
                                </select>
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

<script src="js/admin/mantenimiento/aulas/editar-aula.js"></script>