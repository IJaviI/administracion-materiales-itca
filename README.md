# Sistema de prestamo de equipos electrónicos 
Creado para el prestamo de equipos de docentes en ITCA Fepade, Santa Ana (Posible futura expansión para estudiantes).

## Modulos del sistema
1. Cuentas de usuarios
   * **La primera vez que el sistema es usado se requiere crear un usuario, dicho usuario es administrador del sistema por defecto.**
   *  Perfil de usuario (Administrador y usuario).
   *  Administracion de cuentas de usuario (Solo administrador).
2. Inventario de equipos
   * Administracion del inventario (Solo administrador).
   * Inserción masiva mediante archivos .CSV (Solo administrador).
3. Prestamos
   * Realizar un prestamo (Administrador y usuario).
   * Historial de prestamos (Administrador y usuario).
   * Ver todos los prestamos, ya sea filtrados o no (Solo administrador).
4. Reportes
   * Generación de reportes PDF y Excel en base a fechas o usuario (Solo administrador).

## Despliegue de la aplicación mediante [XAMPP](https://www.apachefriends.org/download.html)
Si se requiere de un despliegue rapido de la app por motivos de testeo u otros, seguir las siguientes instrucciones: 
1. Agregar la carpeta descomprimida al directorio **hdocts** en XAMPP, la ruta concreta es la siguiente: **(Particion donde se instalo XAMPP) -> C:\xampp\htdocs**.
2. Ejecutar XAMPP.
3. En el panel de control de XAMPP click **start** en Apache y MySQL.
4. Abrir un navegador, por ejemplo firerfox y escribir **localhost/Prestamo-de-equipos-ITCA** en la barra de busqueda.
5. Si se desea probar en mas dispositivos tomando como servidor al computador donde se desplegó la app, por ejemplo un dispositivo movil:
   * Verificar la ip del computador donde se desplegó la app, ejecutar comando **ipconfig** en la terminal/shell para obtener la ip.
   * Asegurarnos de estar conectados a la misma red que dicho computador.
   * Entrar al navegador del dispositivo.
   * Escribir en la barra de busqueda: **ip del servidor (verificada antes, ejemplo: 192.168.5.5) -> 192.168.5.5/Prestamo-de-equipos-ITCA**.

Vista previa de la app:

![image](https://github.com/IJaviI/Prestamo-de-equipos-ITCA/assets/95502860/c555dbba-acf3-432d-8cc8-ccc56ba43978)

![image](https://github.com/IJaviI/Prestamo-de-equipos-ITCA/assets/95502860/fce8e11d-9a9b-466f-906c-70483dc81242)

![image](https://github.com/IJaviI/Prestamo-de-equipos-ITCA/assets/95502860/3fb35e74-77f2-4f0c-b5a0-983feaa5484f)

![image](https://github.com/IJaviI/Prestamo-de-equipos-ITCA/assets/95502860/e9320993-5211-40e6-b892-32c83e7343ba)

