<?php

class cls_contenido {
    public function ver() {
        if(isset($_GET["url"])){
            $datos=explode("/", $_GET["url"]);

            // Retornando paginas de agregar, administrar y editar departamentos  
            if(("vistas/admin/mantenimiento/departamentos/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/departamentos/agregar-departamento_view.php"){
                return "php/vistas/admin/mantenimiento/departamentos/agregar-departamento_view.php";
            } else if(("vistas/admin/mantenimiento/departamentos/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/departamentos/administrar-departamentos_view.php"){
                return "php/vistas/admin/mantenimiento/departamentos/administrar-departamentos_view.php";
            } else if(("vistas/admin/mantenimiento/departamentos/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/departamentos/editar-departamento_view.php"){
                return "php/vistas/admin/mantenimiento/departamentos/editar-departamento_view.php";
            } 

            // Retornando paginas de agregar, administrar y editar unidades  
            if(("vistas/admin/mantenimiento/unidades/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/unidades/agregar-unidad_view.php"){
                return "php/vistas/admin/mantenimiento/unidades/agregar-unidad_view.php";
            } else if(("vistas/admin/mantenimiento/unidades/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/unidades/administrar-unidades_view.php"){
                return "php/vistas/admin/mantenimiento/unidades/administrar-unidades_view.php";
            } else if(("vistas/admin/mantenimiento/unidades/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/unidades/editar-unidad_view.php"){
                return "php/vistas/admin/mantenimiento/unidades/editar-unidad_view.php";
            } 
            
            // Retornando paginas de agregar, administrar y editar usuarios  
            else if(("vistas/admin/mantenimiento/usuarios/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/usuarios/agregar-usuario_view.php"){
                return "php/vistas/admin/mantenimiento/usuarios/agregar-usuario_view.php";
            } else if(("vistas/admin/mantenimiento/usuarios/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/usuarios/administrar-usuarios_view.php"){
                return "php/vistas/admin/mantenimiento/usuarios/administrar-usuarios_view.php";
            } else if(("vistas/admin/mantenimiento/usuarios/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/usuarios/editar-usuario_view.php"){
                return "php/vistas/admin/mantenimiento/usuarios/editar-usuario_view.php";
            } 
            
            // Retornando paginas de agregar, administrar y editar marcas  
            else if(("vistas/admin/mantenimiento/marcas/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/marcas/agregar-marca_view.php"){
                return "php/vistas/admin/mantenimiento/marcas/agregar-marca_view.php";
            } else if(("vistas/admin/mantenimiento/marcas/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/marcas/administrar-marcas_view.php"){
                return "php/vistas/admin/mantenimiento/marcas/administrar-marcas_view.php";
            } else if(("vistas/admin/mantenimiento/marcas/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/marcas/editar-marca_view.php"){
                return "php/vistas/admin/mantenimiento/marcas/editar-marca_view.php";
            }

            // Retornando paginas de agregar, administrar y editar aulas
            else if(("vistas/admin/mantenimiento/equipos/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/equipos/agregar-equipo_view.php"){
                return "php/vistas/admin/mantenimiento/equipos/agregar-equipo_view.php";
            } else if(("vistas/admin/mantenimiento/equipos/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/equipos/administrar-equipos_view.php"){
                return "php/vistas/admin/mantenimiento/equipos/administrar-equipos_view.php";
            } else if(("vistas/admin/mantenimiento/equipos/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/equipos/editar-equipo_view.php"){
                return "php/vistas/admin/mantenimiento/equipos/editar-equipo_view.php";
            }
            
            // Retornando paginas de agregar, administrar y editar aulas
            else if(("vistas/admin/mantenimiento/aulas/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/aulas/agregar-aula_view.php"){
                return "php/vistas/admin/mantenimiento/aulas/agregar-aula_view.php";
            } else if(("vistas/admin/mantenimiento/aulas/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/aulas/administrar-aulas_view.php"){
                return "php/vistas/admin/mantenimiento/aulas/administrar-aulas_view.php";
            } else if(("vistas/admin/mantenimiento/aulas/".$datos[0])."_view.php" === "vistas/admin/mantenimiento/aulas/editar-aula_view.php"){
                return "php/vistas/admin/mantenimiento/aulas/editar-aula_view.php";
            }

            // Retornando paginas de realizar nuevo prestamo y historial de prestamos
            else if(("php/vistas/admin_user/".$datos[0])."_view.php" === "php/vistas/admin_user/realizar-prestamo_view.php"){
                return "php/vistas/admin_user/realizar-prestamo_view.php";
            } else if(("php/vistas/admin_user/".$datos[0])."_view.php" === "php/vistas/admin_user/historial-prestamos_view.php"){
                return "php/vistas/admin_user/historial-prestamos_view.php";
            }

            // Retornando pagina de asignar de prestamo
            else if(("php/vistas/admin/prestamos/".$datos[0])."_view.php" === "php/vistas/admin/prestamos/asignar-prestamo_view.php"){
                return "php/vistas/admin/prestamos/asignar-prestamo_view.php";
            }

            // Retornando pagina de registro de prestamos
            else if(("php/vistas/admin/registro/".$datos[0])."_view.php" === "php/vistas/admin/registro/registro-prestamos_view.php"){
                return "php/vistas/admin/registro/registro-prestamos_view.php";
            }

            // Retornando pagina para generar excel de registro de prestamos
            else if(("php/vistas/admin/registro/".$datos[0])."_view.php" === "php/vistas/admin/registro/generar-excel_view.php"){
                return "php/vistas/admin/registro/generar-excel_view.php";
            }

            // Retornando pagina para generar pdf de registro de prestamos
            else if(("php/vistas/admin/registro/".$datos[0])."_view.php" === "php/vistas/admin/registro/generar-pdf_view.php"){
                return "php/vistas/admin/registro/generar-pdf_view.php";
            }

            // Retornando pagina para generar control de entrega de inventario
            else if(("php/vistas/admin/generar/".$datos[0])."_view.php" === "php/vistas/admin/generar/control-de-entrega_view.php"){
                return "php/vistas/admin/generar/control-de-entrega_view.php";
            }

            // Retornando pagina para generar inventario de materiales
            else if(("php/vistas/admin/generar/".$datos[0])."_view.php" === "php/vistas/admin/generar/ingreso-materiales_view.php"){
                return "php/vistas/admin/generar/ingreso-materiales_view.php";
            }

        } else {
            return "php/vistas/default_view.php";
        }
    }
}

?>