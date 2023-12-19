<div class="page" id="page" tabindex="5">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-marca" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#marca"></use>
            </svg>
            <div class="page__card-block">
                Agregar marca
                <span>Crea una nueva marca</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-marcas" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#marcas"></use>
            </svg>
            <div class="page__card-block">
                Administrar marcas
                <span>Administra marcas existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Edita esta marca</h2>
        <div class="page__division"></div>
        <div class="table-responsive">
            <table class="page__table">
                <thead class="page__table-head">
                    <tr class="page__table-row">    
                        <th>Nombre de la marca</th>
                        <th style="text-align: center">Acciones</th>
                    </tr>
                </thead>
                
                <form>
                    <tbody class="page__table-body">
                        <?php
                        if(!isset($_SESSION["marca"])){
                            echo "
                            <tr class='page__table-row'>
                                <td colspan='5' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha seleccionado una marca a editar.</td>
                            </tr>
                            ";    
                        }
                        ?>
                        <tr class="page__table-row" <?php if(!isset($_SESSION["marca"])){echo "style='display: none'";}?>>
                            <td  class="no-padding">
                                <input type="hidden" id="marca_id" value="<?php if(isset($_SESSION["marca"])){ echo $_SESSION["marca"]["id"];}?>">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["marca"])){ echo $_SESSION["marca"]["marca_name"];}?>" class="page__table-row-input" id="marca_name" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Nombre de la marca">
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

<script src="js/admin/mantenimiento/marcas/editar-marca.js"></script>