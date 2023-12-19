<?php
require_once("php/controladores/cls_departamentos.php");
?>

<div class="page" id="page" tabindex="6"  >
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-usuario" class="page__card">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#person-circle"></use>
            </svg>
            <div class="page__card-block">
                Agregar cuenta de usuario
                <span>Crea una nueva cuenta de usuario</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-usuarios" class="page__card">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#people-fill"></use>
            </svg>
            <div class="page__card-block">
                Administrar cuentas de usuario
                <span>Administra cuentas de usuarios existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Edita este usuario</h2>
        <div class="page__division"></div>
        <div class="table-responsive">
            <table class="page__table">
                <thead class="page__table-head">    
                    <tr class='page__table-row'>    
                        <th>Carnet</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Cargo</th>
                        <th>Tel Casa</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Nueva imagen</th>
                        <th style='text-align: center'>Acciones</th>
                    </tr>
                </thead>
                
                <form>
                    <tbody class="page__table-body">
                        <?php
                        if(!isset($_SESSION["usuario"])){
                            echo "
                            <tr class='page__table-row'>
                                <td colspan='11' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha seleccionado un usuario a editar.</td>
                            </tr>
                            ";    
                        }
                        ?>
                        <tr class='page__table-row' <?php if(!isset($_SESSION["usuario"])){echo "style='display: none'";}?>>
                            <td class="no-padding">
                                <input type="hidden" id="user_id" value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["id"];}?>">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["usuario_carnet"];}?>" class="page__table-row-input" id="user_carnet" style='width: 100px;' placeholder="Carnet">
                            </td>
                            <td class="no-padding">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["usuario_names"];}?>" class="page__table-row-input" id="user_names" style='width: 130px;' placeholder="Nombres">
                            </td>
                            <td class="no-padding">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["usuario_lastnames"];}?>" class="page__table-row-input" id="user_lastnames" style='width: 130px;' placeholder="Apellidos">
                            </td>
                            <td class="no-padding">
                                <select id="user_type" class="page__table-row-input" style='width: 130px;'>
                                    <option <?php if($_SESSION["usuario"]["usuario_type"] == "Ingeniero"){ echo "selected"; } ?> value='Ingeniero'>Ingeniero</option>
                                    <option <?php if($_SESSION["usuario"]["usuario_type"] == "Licenciado/a"){ echo "selected"; } ?> value='Licenciado/a'>Licenciado/a</option>
                                    <option <?php if($_SESSION["usuario"]["usuario_type"] == "Tecnico"){ echo "selected"; } ?> value='Tecnico'>Tecnico</option>
                                </select>
                            </td>
                            <td class="no-padding">
                                <input onkeydown="return event.key != 'Enter'" type="tel" required pattern='[0-9]{4}-[0-9]{4}' value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["usuario_telcasa"];}?>" class="page__table-row-input" id="user_telcasa" style='width: 100px;' placeholder="Tel de casa">
                            </td>
                            <td class="no-padding">
                                <input onkeydown="return event.key != 'Enter'" type="tel" required pattern='[0-9]{4}-[0-9]{4}' value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["usuario_celular"];}?>" class="page__table-row-input" id="user_celular" style='width: 100px;' placeholder="Celular">
                            </td>
                            <td class="no-padding">
                                <input onkeydown="return event.key != 'Enter'" type="email" required value="<?php if(isset($_SESSION["usuario"])){ echo $_SESSION["usuario"]["usuario_email"];}?>" class="page__table-row-input" id="user_email" style='width: 160px;' placeholder="Email del usuario">
                            </td>
                            <td class="no-padding">
                            <select id="user_depto" class="page__table-row-input" style='width: 120px;'>
                                    <?php
                                    $obj_departamentos =  new cls_departamentos;
                                    $departamentos = $obj_departamentos -> consult();
                                    
                                    foreach($departamentos as $depto){
                                        echo"
                                        <option "; if($depto["id_depto"] == $_SESSION["usuario"]["usuario_depto"]){ echo "selected"; } echo" value='$depto[id_depto]'>$depto[depto]</option>
                                        ";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="no-padding" style="background-color: #ebebeb">
                                <label class='form__input form__row-label no-padding' for='user_imagen' style="padding: 0; border-radius: 0; border: 0;">
                                    <span style="padding: .7rem 1rem; ">Imagen</span>
                                    <div class='form__row-label-state' style="padding: 1rem;">Seleccionar</div>
                                    <input type='file' accept='image/*' id='user_imagen' class='hidden'>
                                </label>
                            </td>
                            <td class="no-padding">
                                <button type="button" id="guardar" style="padding-top: 1rem; padding-bottom: 1rem">
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

<script src="js/admin/mantenimiento/usuarios/editar-usuario.js"></script>