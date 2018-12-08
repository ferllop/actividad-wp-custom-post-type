<style>
 #map {
   width: 100%;
   height: 400px;
   background-color: grey;
 }
</style>
<?php

while ( have_posts() ) : the_post(); ?>
<div class="content">
	<div class="ptitle">
		<h1 id="title"><?php the_title(); ?></h1>
	</div>
	
	<?php $fecha = get_post_meta( get_the_ID(), 'fecha', true ); 
	if (!empty($fecha)) { ?>
		<div class="fecha">Fecha: <?php
			$fecha = get_post_meta( get_the_ID(), 'fecha', true ); 
			echo $fecha; ?>
		</div>
	<?php 
	} 
	?>
	
	<div id="localizacion" class="localizacion" data-lat="<?php echo get_post_meta(get_the_ID(),'lat_mapa', true); ?>" data-lng="<?php echo get_post_meta(get_the_ID(),'lng_mapa', true); ?>">Localización:  <?php 
		$localizacion = get_post_meta( get_the_ID(), 'localizacion', true ); 
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
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjeU9-8sUIfBFeM5PrwrdkonI0-hdIGQ4&callback=initMap"></script>
	<div class="descripcion">Descripción:  <br /><?php 
		$descripcion = get_post_meta( $post->ID, 'descripcion', true ); 
		echo $descripcion; ?>
	</div>
	
	<?php 
	$edad_minima = get_post_meta( get_the_ID(), 'edad_minima', true );
	$edad_maxima = get_post_meta( get_the_ID(), 'edad_maxima', true );
	if ( !empty($edad_minima) || !empty($edad_maxima) ) { ?>
		<div class="edad">Edad: 
			<?php
			if ( $edad_minima == $edad_maxima ) {
				echo ( $edad_minima == 1 ) ? $edad_minima . ' año' : $edad_minima . ' años'; 
			} else {						 
				echo 'De ' . get_post_meta( get_the_ID(), 'edad_minima', true ) . ' a ' . get_post_meta( get_the_ID(), 'edad_maxima', true ) . ' años'; 
			} 
			?>
		</div>
	<?php
	}
	?>
	
	<div class="precio">Precio: <?php 
		$precio = get_post_meta( get_the_ID(), 'precio', true ); 
		echo $precio; ?>
	</div>
	<?php 
	$user = wp_get_current_user();
	$author = get_the_author_meta('ID');

	if( is_user_logged_in() && ($post->post_author == $current_user->ID) ) { ?>
		<div class="coupon-buts">
        	<form class="edit-coupon" action="/modificar-actividad/" method="post"> 
            	<input type="hidden" name="ID_actividad" value=<?php the_ID() ?> /> 
				<input type="text" name="edit_falso" style="display: none" value="">
				<?php wp_nonce_field( 'form_actividad','nonce_form' ); ?>
                <input type="submit" name="action_actividad" value="Editar" />
                <input type="submit" name="action_actividad" value="<?php echo (get_post_status(get_the_ID()) == 'publish') ? 'Borrador' : 'Publicar' ?>" />
                <input type="submit" name="action_actividad" value="Borrar" />
            </form>
        </div>
    <?php } ?>
	
</div>

