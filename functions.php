// Deshabilitar la funcionalidad de comentarios en WordPress

// Elimina el soporte de comentarios y trackbacks para todos los tipos de publicaciones (post, página, etc.)
function disable_comments_post_types_support() {
    // Obtiene todos los tipos de publicaciones
    $post_types = get_post_types();

    // Itera sobre cada tipo de publicación
    foreach ($post_types as $post_type) {
        // Si el tipo de publicación tiene soporte para comentarios...
        if(post_type_supports($post_type, 'comments')) {
            // ...remueve dicho soporte
            remove_post_type_support($post_type, 'comments');
            // ...y también remueve el soporte para trackbacks
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
// Asocia la función con el hook 'admin_init'
add_action('admin_init', 'disable_comments_post_types_support');

// Oculta la meta box de comentarios recientes en el dashboard del administrador
function disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'disable_comments_dashboard');

// Si la barra de administración está activa, oculta el ítem del menú de comentarios
function disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'disable_comments_admin_bar');

// Si alguien intenta acceder directamente a 'edit-comments.php', redirecciona al dashboard
function disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}
add_action('admin_init', 'disable_comments_admin_menu_redirect');

// Elimina la página de comentarios del menú de administración
function disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'disable_comments_admin_menu');

// Desactiva la funcionalidad de comentarios a través del API de WordPress
function disable_comments_api() {
    // Desactiva los puntos finales (endpoints) del API para comentarios
    remove_action('rest_api_init', 'create_initial_comment_routes');
    
    // Elimina el filtro de encabezados de respuesta del API
    remove_filter('rest_pre_serve_request', 'rest_filter_response_headers', 10, 4);
    
    // Si hay una solicitud al API que incluye '/comments' o '/comment', regresa un error
    add_filter('rest_pre_serve_request', function($served, $result, $request, $server) {
        $route = $request->get_route();
        if (strpos($route, '/comments') > -1 || strpos($route, '/comment') > -1) {
            return new WP_Error('rest_forbidden', 'No tienes permiso para acceder a este recurso.', array('status' => 403));
        }
        return $served;
    }, 10, 4);
}
add_action('rest_api_init', 'disable_comments_api', 20);
