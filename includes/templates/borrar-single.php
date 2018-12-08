<?php 
global $userID;

global $insert;
global $interesados;
global $user_interests;
if ( is_user_logged_in() && isset($_POST['suscribed_list']) ){
    if ( !isset( $_POST['nonce_form'] ) || !wp_verify_nonce($_POST['nonce_form'], 'form_actividad') || !empty($_POST['suscribe_falso'])){
		$security_error .= 'Error de seguridad<br />';
        return $security_error;
	} 
	
	$postID = get_the_ID();
    $interesados = get_post_meta($postID ,'interesados', true);
    $userID = get_current_user_id();
	$insert = ',' . $userID . ',';
	//$user_interests = get_user_meta($userID, 'interesado', true);
	
	if ( $_POST['suscribed_list'] == 'add' && !strpos($interesados, $insert)) {
	    update_post_meta($postID, 'interesados', $userID);
	    //$user_interests = $user_interests . ',' . $postID . ',';
	    //update_user_meta( $userID, 'interesado', $user_interests );
	}
	if ( $_POST['suscribed_list'] == 'remove' && strpos($interesados, $insert)) {
        update_post_meta($postID, 'interesados', '', $userID);
        //$user_interests = str_replace( ',' . $postID . ',' ,'', $user_interests );
	    //update_user_meta( $userID, 'interesado', $user_interests );
	}
}

get_header(); 
echo $userID . '<br />';

echo $insert . '<br />';
echo $interesados . '<br />';
echo $user_interests; ?>
<style>
 #map {
   width: 100%;
   height: 400px;
   background-color: grey;
 }
</style>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			?>
		<div class="content">
			<div class="ptitle">
				<h1 id="title"><?php echo get_the_title(); ?></h1>
			</div>
			
			<?php $fecha = get_post_meta( $post->ID, 'fecha', true ); 
			if (!empty($fecha)) { ?>
				<div class="fecha">Fecha: <?php
					$fecha = get_post_meta( $post->ID, 'fecha', true ); 
					echo $fecha; ?>
				</div>
			<?php 
			} 
			?>
			
			<div id="localizacion" class="localizacion" data-lat="<?php echo get_post_meta($post->ID,'lat_mapa', true); ?>" data-lng="<?php echo get_post_meta($post->ID,'lng_mapa', true); ?>">Localización:  <?php 
				$localizacion = get_post_meta( $post->ID, 'localizacion', true ); 
				echo $localizacion; ?>
			</div>
			<div id="map"></div>
    <script>
      function initMap() {
		var lat = parseFloat(document.getElementById('localizacion').dataset.lat);
		var lng = parseFloat(document.getElementById('localizacion').dataset.lng);
		var mylatlng = {lat: lat, lng: lng};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: mylatlng
        });
		var titulo = document.getElementById('title').innerHTML;
        var marker = new google.maps.Marker({
          position: mylatlng,
          map: map,
		  title: titulo
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjeU9-8sUIfBFeM5PrwrdkonI0-hdIGQ4&callback=initMap">
    </script>
			<div class="descripcion">Descripción:  <br /><?php 
				$descripcion = get_post_meta( $post->ID, 'descripcion', true ); 
				echo $descripcion; ?>
			</div>
			
			<?php 
			$edad_minima = get_post_meta( $post->ID, 'edad_minima', true );
			$edad_maxima = get_post_meta( $post->ID, 'edad_maxima', true );
			if ( !empty($edad_minima) || !empty($edad_maxima) ) { ?>
				<div class="edad">Edad: 
					<?php
					if ( $edad_minima == $edad_maxima ) {
						echo ( $edad_minima == 1 ) ? $edad_minima . ' año' : $edad_minima . ' años'; 
					} else {						 
						echo 'De ' . get_post_meta( $post->ID, 'edad_minima', true ) . ' a ' . get_post_meta( $post->ID, 'edad_maxima', true ) . ' años'; 
					} 
					?>
				</div>
			<?php
			}
			?>
			
			<div class="precio">Precio: <?php 
				$precio = get_post_meta( $post->ID, 'precio', true ); 
				echo $precio; ?>
			</div>
			<?php 
			$user = wp_get_current_user();
			$author = get_the_author_meta('ID');
		    $postID = get_the_ID() ;
			if( is_user_logged_in() && ($post->post_author == $current_user->ID) ) { 
			    
			    ?>
				<div class="coupon-buts">
                	<form class="edit-coupon" action="/modificar-actividad/" method="post"> 
                    	<input type="hidden" name="ID_actividad" value=<?php echo $postID ?> /> 
						<input type="text" name="edit_falso" style="display: none" value="">
						<?php wp_nonce_field( 'form_actividad','nonce_form' ); ?>
                        <input type="submit" name="action_actividad" value="Editar" />
                        <input type="submit" name="action_actividad" value="<?php echo (get_post_status($postID) == 'publish') ? 'Borrador' : 'Publicar'; ?>" />
                        <input type="submit" name="action_actividad" value="Borrar" />
                    </form>
                </div>
            <?php
			} else if ( is_user_logged_in() && ($post->post_author !== $current_user->ID)){ 
			    
			    $userID = strval(get_current_user_id());
			    $interesados = get_post_meta($postID ,'interesados', true);
			    $insert = ','.$userID.','; 
			    echo $interesados . '<br />';
			    echo strpos($interesados, $userID); ?>
			    <div class="suscribe-button">
                	<form class="suscribe-form" action="" method="post"> 
                    	<input type="text" name="suscribe_falso" style="display: none" value="">
						<?php wp_nonce_field( 'form_actividad','nonce_form' ); ?>
                        <input type="submit" value="<?php echo (!strpos($interesados, $userID)) ? 'Ya no me interesa' : 'Me interesa'; ?>" />
                        <input type="hidden" name="suscribed_list" value="<?php echo (strpos($interesados, $userID)) ? 'remove' : 'add'; ?>" />
                    </form>
                </div><?php 
			}
			?>
			
		</div>
		
		<?php
		if( !($post->post_author == $current_user->ID) ) { 
		wprc_report_submission_form(); 
		}
		
		
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		
			if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation( array(
					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
				) );
			} elseif ( is_singular( 'post' ) ) {
				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
