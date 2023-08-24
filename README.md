Función para eliminar todo lo relacionado con los comentarios de WordPress, en resumen este código hace:

1- Elimina el soporte para comentarios y trackbacks de todos los tipos de publicaciones.
2- Oculta el widget "Comentarios recientes" en el dashboard de administración.
3- Si la barra de administración está visible, oculta el ítem de menú de comentarios.
4- Si un usuario intenta acceder directamente a la página de administración de comentarios, será redirigido al dashboard.
5- Elimina la opción de "Comentarios" del menú de administración lateral.
6- Desactiva la capacidad de interactuar con comentarios a través del API de WordPress, regresando un error si alguien intenta acceder o modificar comentarios vía API.
