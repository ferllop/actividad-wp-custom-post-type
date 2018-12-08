<?php
/*
Plugin Name: Actividad Post Type
*/
function wpmudev_create_post_type() {
	$labels = array(
 		'name' => 'Actividades',
    	'singular_name' => 'Actividad',
    	'add_new' => 'Añade Nueva Actividad',
    	'add_new_item' => 'Añade una nueva Actividad',
    	'edit_item' => 'Edita la Actividad',
    	'new_item' => 'Nueva Actividad',
    	'all_items' => 'Todas las Actividades',
    	'view_item' => 'Ver Actividad',
    	'search_items' => 'Buscar Actividades',
    	'not_found' =>  'No se han encontrado Actividades',
    	'not_found_in_trash' => 'No se han encontrado Actividades en la Papelera', 
    	'parent_item_colon' => '',
    	'menu_name' => 'Actividades',
    );
    
    $args = array(
		'labels' => $labels,
		'has_archive' => true,
 		'public' => true,
		'supports' => array( 'title', 'custom-fields', 'thumbnail', 'comments', 'page-attributes', 'author' ),
		'exclude_from_search' => false,
		'capability_type' => 'post',
		//'map_meta_caps' => true,
		'rewrite' => array( 'slug' => 'actividades' ),
	);
	
	register_post_type( 'actividad', $args );
}

add_action( 'init', 'wpmudev_create_post_type' );


 
// function actividad_capabilities() {
//     //There has to be a better way to do this
//     global $wp_roles;

//     //Check if $wp_roles has been initialized
//     if ( isset($wp_roles) ) {
//         $wp_roles->add_cap( 'administrator', 'edit_actividad' );
//         $wp_roles->add_cap( 'administrator', 'read_actividad' );
//         $wp_roles->add_cap( 'administrator', 'delete_actividad' );
//         $wp_roles->add_cap( 'administrator', 'publish_actividads' );
//         $wp_roles->add_cap( 'administrator', 'edit_actividads' );
//         $wp_roles->add_cap( 'administrator', 'edit_others_actividads' );
//         $wp_roles->add_cap( 'administrator', 'delete_actividads' );
//         $wp_roles->add_cap( 'administrator', 'delete_others_actividads' );
//         $wp_roles->add_cap( 'administrator', 'read_private_actividads' );
 
//         $wp_roles->add_cap( 'editor', 'read_actividad' );
//         $wp_roles->add_cap( 'editor', 'read_private_actividads' );
 
//         $wp_roles->add_cap( 'author', 'read_actividad' );
//         $wp_roles->add_cap( 'author', 'read_private_actividads' );
         
//         $wp_roles->add_cap( 'contributor', 'read_actividad' );
//         $wp_roles->add_cap( 'contributor', 'read_private_actividads' );
        
//         $wp_roles->add_cap( 'suscriber', 'read_actividad' );
//         $wp_roles->add_cap( 'suscriber', 'read_private_actividads' );
//         $wp_roles->add_cap( 'administrator', 'edit_actividad' );
//         $wp_roles->add_cap( 'administrator', 'delete_actividad' );
//         $wp_roles->add_cap( 'administrator', 'publish_actividads' );
//         $wp_roles->add_cap( 'administrator', 'edit_actividads' );
//         $wp_roles->add_cap( 'administrator', 'delete_actividads' );
 
//     }
// }
// add_action('init', 'actividad_capabilities');

//----------------------------------------------------------
//-------REGISTRAR TAXONOMIAS-------------------------------
//----------------------------------------------------------
/*
 function wpmudev_register_taxonomy() {
	$labels = array(
		'name'              => 'Tipos de Actividad',
		'singular_name'     => 'Tipo de Actividad',
		'search_items'      => 'Busca Tipos de Actividad',
		'all_items'         => 'Todos los Tipos de Actividades',
		'edit_item'         => 'Edit Tipo de Actividad',
		'update_item'       => 'Actualiza Tipo de Actividad',
		'add_new_item'      => 'Añade un nuevo Tipo de Actividad',
		'new_item_name'     => 'Nuevo tipo de Actividad',
		'menu_name'         => 'Tipos de Actividades'
	);
	register_taxonomy( 'actividadestag', 'actividad', array(
		'hierarchical' => false,
		'labels' => $labels,
		'query_var' => true,
		'show_admin_column' => true
	) );
}
add_action( 'init', 'wpmudev_register_taxonomy' );
*/


//----------------------------------------------------------
//-------ADMIN CUSTOM FIELDS--------------------------------
//----------------------------------------------------------
function validate_form(){
    $form_error = '';
    if ( empty( $_POST['title'] ) ){
		$form_error .= 'Has de poner un título<br />';
	}	
	if ( $_POST[ 'precio' ] == '' ){
		$form_error .= 'Has de poner un precio.<br />';
	}	
	if ( !empty( $_POST[ 'edad_minima' ] ) && ( (int)$_POST[ 'edad_minima' ]  < 0  || (int)$_POST[ 'edad_minima' ]  > 98 ) ){
		$form_error .= 'Has de poner una edad mínima entre 0 y 98 años<br />';
	}	
	if ( !empty( $_POST[ 'edad_maxima' ] ) && ( (int)$_POST[ 'edad_maxima' ]  < 1  || (int)$_POST[ 'edad_maxima' ]  > 99 ) ){
		$form_error .= 'Has de poner una edad máxima entre 1 y 99 años<br />';
	}	
	if ( !empty( $_POST[ 'edad_minima' ] ) && !empty( $_POST[ 'edad_maxima' ] ) && $_POST[ 'edad_minima' ] > $_POST[ 'edad_maxima' ] ) {
		$form_error .= 'La edad mínima ha de ser menor o igual a la máxima<br />';
	}
	if ( empty( $_POST[ 'localizacion' ] ) || !preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $_POST[ 'lat_mapa' ]) || !preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $_POST[ 'lng_mapa' ])){
		$form_error .= 'Has de poner una localización<br />';
	}	
	if ( empty( $_POST[ 'descripcion' ]  ) ){
		$form_error .= 'Has de poner una descripción<br />';
	}	
	return $form_error;
}
function admin_form() {
    global $post;
	$custom = get_post_custom($post->ID);
	if (!empty($custom)) {
		$fecha = $custom["fecha"][0];
		$precio = $custom["precio"][0];
		$edad_minima = $custom["edad_minima"][0];
		$edad_maxima = $custom["edad_maxima"][0];
		$localizacion = $custom["localizacion"][0];
		$lat_mapa = $custom["lat_mapa"][0];
		$lng_mapa = $custom["lng_mapa"][0];
		$descripcion = $custom["descripcion"][0];
	} else {
		$fecha = $precio = $edad_minima = $edad_maxima = $localizacion = $lat_mapa = $lng_mapa = $descripcion = '';
	} 
	if (isset($form_error)){
	    echo $form_error . '<br />';
	}
	include_once( dirname( __FILE__ ) . '/includes/formulario-actividad.php' );
	wp_nonce_field( 'admin_form_actividad','nonce_admin_form' ); 
}
function admin_init(){
	add_meta_box("info_meta", "Informacion", "admin_form", "actividad", "normal", "high");
}
add_action("admin_init", "admin_init");

function save_details(){
    global $post;
	if (isset($_POST['publish']) || isset($_POST['save']) ){
	    $form_error = validate_form();
	    if (!empty($form_error)) return;
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	    if ( ! wp_verify_nonce( $_POST[ 'nonce_admin_form' ], 'admin_form_actividad' ) ) return;
        $postid = get_the_ID($post);
    	update_post_meta($postid, "fecha", sanitize_text_field( $_POST["fecha"]));
    	update_post_meta($postid, "precio", sanitize_text_field( $_POST["precio"]));
    	update_post_meta($postid, "edad_minima", sanitize_text_field( $_POST["edad_minima"]));
    	update_post_meta($postid, "edad_maxima", sanitize_text_field( $_POST["edad_maxima"]));
    	update_post_meta($postid, "localizacion", sanitize_text_field( $_POST["localizacion"]));
    	update_post_meta($postid, "lat_mapa", sanitize_text_field( $_POST[ 'lat_mapa' ]));
    	update_post_meta($postid, "lng_mapa", sanitize_text_field( $_POST['lng_mapa']));
    	update_post_meta($postid, "descripcion", sanitize_textarea_field( $_POST["descripcion"]));
	    
	}
}
add_action('save_post', 'save_details');

//----------------------------------------------------------
//-------FORMULARIO-----------------------------------------
//----------------------------------------------------------

function formulario_actividad_shortcode( $atts ) {
    global $postid, $title, $precio, $fecha, $edad_minima, $edad_maxima, $localizacion, $lat_mapa, $lng_mapa, $descripcion;
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
 
    // default attributes for shortcode
    $actividad_shorcode_atts = shortcode_atts(array('action' => 'edit'), $atts);
    
    
    if ( !is_user_logged_in()) {
        $login_error = '<p>Debes acceder con tu usuario, o registrate, para publicar una actividad</p>';
        $login_error .= do_shortcode( '[theme-my-login]' );
        return $login_error;
    } 
    
    function form(){
        $form = include_once( dirname( __FILE__ ) . '/includes/formulario-actividad.php' );
        return $form;
    }
    
    // if ($actividad_shorcode_atts['action'] == 'new') {
    // 	$postid = $title = $precio = $fecha = $edad_minima = $edad_maxima = $localizacion = $lat_mapa = $lng_mapa = $descripcion = '';
    // 	return form();
    // }
    if ( !isset($_POST['action_actividad']) ) { 
        $_POST['action_actividad'] = 'new';
    }
    	
    switch ( $_POST['action_actividad'] ) {
        case 'new':
            $postid = $title = $precio = $fecha = $edad_minima = $edad_maxima = $localizacion = $lat_mapa = $lng_mapa = $descripcion = '';
    	    form();
    	    break;
        case 'Editar':
	   		if ( !isset( $_POST['nonce_form'] ) || !wp_verify_nonce($_POST['nonce_form'], 'form_actividad') || !empty($_POST['form_falso']) ){
    			$security_error .= 'Error de seguridad<br />';
    	        return $security_error;
    		}
    		$post = get_post( $_POST['ID_actividad']  );
    	    $postid = $post->ID;
    	    $title = $post->post_title ;
    	    $precio = get_post_meta($_POST['ID_actividad'] ,'precio', true);
    		$fecha = get_post_meta($_POST['ID_actividad'] ,'fecha', true);
    		$edad_minima = get_post_meta($_POST['ID_actividad'] ,'edad_minima', true);
    		$edad_maxima = get_post_meta($_POST['ID_actividad'] ,'edad_maxima', true);
    		$localizacion = get_post_meta($_POST['ID_actividad'] ,'localizacion', true);
    		$lat_mapa = get_post_meta($_POST['ID_actividad'] ,'lat_mapa', true);
    		$lng_mapa = get_post_meta($_POST['ID_actividad'] ,'lng_mapa', true);
    		$descripcion = get_post_meta($_POST['ID_actividad'],'descripcion', true);
    		form();
    		break;
	 
    	case 'Borrador':
    		if ( !isset( $_POST['nonce_form'] ) || !wp_verify_nonce($_POST['nonce_form'], 'form_actividad') || !empty($_POST['form_falso']) ){
    			$security_error .= 'Error de seguridad<br />';
    	        echo $security_error;
    	        break;
    		}
    		wp_update_post ( array(
    		    'ID' => $_POST['ID_actividad'], 
    		    'post_status'   =>  'draft'
    		    ));
    		echo '<p>La actividad se ha guardado como borrador.<br />';
    		echo 'Volver a <a href="/tus-actividades/">mis actividades</a></p>';
    		break;
     	
    	case 'Publicar':
    		if ( !isset( $_POST['nonce_form'] ) || !wp_verify_nonce($_POST['nonce_form'], 'form_actividad') || !empty($_POST['form_falso']) ){
    			$security_error .= 'Error de seguridad<br />';
    	        echo $security_error;
    	        break;
    		}
    		wp_update_post( array(
    		    'ID' => $_POST['ID_actividad'], 
    		    'post_status'   =>  'publish'
    		    ));
    		echo '<p>La actividad se ha publicado correctamente.<br />';
    		echo '<a href=" ' . get_permalink( $_POST['ID_actividad'] ) . '">Ver actividad</a></p>';
    		echo '<p>Volver a <a href="/tus-actividades/">mis actividades</a></p>';
    		break;
    	
    	case 'Borrar':
    		if ( !isset( $_POST['nonce_form'] ) || !wp_verify_nonce($_POST['nonce_form'], 'form_actividad') || !empty($_POST['form_falso']) ){
    			$security_error .= 'Error de seguridad<br />';
    	        return $security_error;
    		} 
    		
    		$interested_users = get_post_meta($_POST['ID_actividad'], 'usuarios_interesados', true);
    		if ( !empty($interested_users) ) {
        		$interested_users = rtrim(trim($interested_users), ',');
                $interested_users_array = explode(',', $interested_users);
                foreach ($interested_users_array as $interested_user) {
                    $user_interests = get_user_meta($interested_user, interesado_actividades, true);
                    $user_interests = str_replace( $_POST['ID_actividad'] . ',' ,'', $user_interests );
    	            update_user_meta( $interested_user, 'interesado_actividades', $user_interests );
                }
    		}
    		
    		wp_trash_post ( $_POST['ID_actividad'], true );
    		echo '<p>La actividad se ha borrado.<br />';
    		echo 'Volver a <a href="/tus-actividades/">mis actividades</a></p>';
    	    break;
    	
    	case 'Guardar':
    	case 'Guardar como borrador':
    	case 'Guardar y publicar':
    		if ( !isset( $_POST['nonce_form'] ) || !wp_verify_nonce($_POST['nonce_form'], 'form_actividad') || !empty($_POST['form_falso'])){
    			$security_error .= 'Error de seguridad<br />';
    	        return $security_error;
    		} 
    		
    		$form_error = validate_form();
    		
    		$post_to_edit = array();
    		$title = wp_strip_all_tags( $_POST['title'] );
    		$precio = sanitize_text_field( $_POST['precio'] );
    		$fecha = sanitize_text_field( $_POST['fecha'] );
    		$edad_minima = sanitize_text_field( intval( $_POST[ 'edad_minima' ] ));
    		$edad_maxima = sanitize_text_field( intval( $_POST[ 'edad_maxima' ] ));
    		$localizacion = sanitize_text_field( $_POST['localizacion'] );
    		$lat_mapa = sanitize_text_field( $_POST[ 'lat_mapa' ] );
    		$lng_mapa = sanitize_text_field( $_POST[ 'lng_mapa' ] );
    		$descripcion = sanitize_textarea_field( $_POST['descripcion'] );
            
            if (!empty($_POST['postid'])) {
                $postid = $_POST['postid'];
            } else {
        		$new_post = array(
        		    'post_title' => $title,
                    'post_content' => '',
        		    //'post_category' => $_POST['cat'],  // Usable for custom taxonomies too
        			'post_status' => 'publish',            // Choose: publish, preview, future, etc.
        			'post_type' => 'actividad'  // Use a custom post type if you want to
        		);
        		
        		if ($_POST['action_actividad'] == "Guardar como borrador"){
        		    $new_post['post_status'] = 'draft';
        		}
        		
        		$postid = wp_insert_post($new_post);
    		}
    		
    		if ( !empty($form_error) ) {
    		  echo 'Hay errores: ' . $form_error . '<br />';
    		  form();
    		  break;
    		}
    		
			update_post_meta($postid, 'precio', $precio);
			update_post_meta($postid, 'fecha', $fecha);
			update_post_meta($postid, 'edad_minima', $edad_minima);
			update_post_meta($postid, 'edad_maxima', $edad_maxima);
			update_post_meta($postid, 'localizacion', $localizacion);
			update_post_meta($postid, 'lat_mapa', $lat_mapa);
			update_post_meta($postid, 'lng_mapa', $lng_mapa);
			update_post_meta($postid, 'descripcion', $descripcion);
    
    		if ($_POST['action_actividad'] == "Guardar como borrador"){
    			echo '<p> La actividad se ha guardado como borrador. <br />';
    		} else if ($_POST['action_actividad'] == "Guardar"){
    		    echo '<p> La actividad se ha editado correctamente. <br />';
    		} else { 
    		    echo '<p> La actividad se ha publicado correctamente. <br />';
    		}
    		
    		echo '<a href=" ' .get_permalink( $postid ) . '">Ver actividad</a></p>';
    		form();
    		break;
    		
	}//end of switch
    	
} // end of shortcode function
add_shortcode( 'formulario-actividad', 'formulario_actividad_shortcode' );



//----------------------------------------------------------
//-------BUSCADOR DE ACTIVIDADES----------------------------
//----------------------------------------------------------
function buscador_actividades_shortcode() {
    include_once( dirname( __FILE__ ) . '/includes/buscador-actividades.php' );
}
add_shortcode( 'buscador-actividades', 'buscador_actividades_shortcode' );



//----------------------------------------------------------
//-------LISTADO ACTIVIDADES PROPIAS------------------------
//----------------------------------------------------------
function listado_actividades_propias_shortcode() {
    include_once( dirname( __FILE__ ) . '/includes/listado-actividades-propias.php' );
}
add_shortcode( 'listado-actividades-propias', 'listado_actividades_propias_shortcode' );



//----------------------------------------------------------
//----CARGAR TEMPLATE DE PLUGIN AL MOSTRAR UNA ACTIVIDAD----
//----------------------------------------------------------
function portfolio_page_template( $template ) {
    $post_id = get_the_ID();
    if ( get_post_type( $post_id ) == 'actividad' && is_single() ) {
        $new_template = dirname( __FILE__ ) . '/includes/templates/single.php';
        if ( $new_template != '' ) {
            return $new_template ;
        }
    }
    return $template;
}
add_filter( 'template_include', 'portfolio_page_template', 99 );

//----------------------------------------------------------
//----PARA VER INTERESES DE CADA USUARIO EN ADMIN-----------
//----------------------------------------------------------
function interestedIn_update() {
    global $user_id;
    if (isset($_POST['intereses'])) {
        update_user_meta( $user_id, 'interesado_actividades', $_POST['intereses'] );
    }
}
add_action( 'edit_user_profile_update', 'interestedIn_update' );

function interestedIn_fields() {
    global $user_id;
    $user_interests = get_user_meta($user_id, 'interesado_actividades', true);
    ?>
    
    <p>
        <label for="intereses">Interesado en las siguiente actividades:</label><br />
		<input type="texto" id="intereses" name="intereses" value="<?php echo esc_attr( $user_interests ); ?>" />
	</p>
	
	<?php
}
add_action( 'edit_user_profile', 'interestedIn_fields' );


/**
 *  Meta OR Title query in WP_Query
 *  Activated through the '_meta_or_title' argument of WP_Query 
 *  http://wordpress.stackexchange.com/a/178492/26350
 */
add_action( 'pre_get_posts', function( $q )
{
    if( $title = $q->get( '_meta_or_title' ) )
    {
        add_filter( 'get_meta_sql', function( $sql ) use ( $title )
        {
            global $wpdb;

            // Only run once:
            static $nr = 0; 
            if( 0 != $nr++ ) return $sql;

            // Modified WHERE
            $sql['where'] = sprintf(
                " AND ( %s OR %s ) ",
                $wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title),
                mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
            );

            return $sql;
        });
    }
});



?>