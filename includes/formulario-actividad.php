<?php 
if (!is_admin()) {
    global $postid, $title, $precio, $fecha, $edad_minima, $edad_maxima, $localizacion, $lat_mapa, $lng_mapa, $descripcion;
    
    if (!empty($form_error)) { 
        $out = '<div id="error">' . $form_error . '</div>' . $inc;
    }
}
wp_enqueue_style( 'maps',  plugins_url( '../css/maps.css', __FILE__ ));
wp_enqueue_style( 'jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css' );
wp_enqueue_script( 'maps-options',  plugins_url( '../js/maps-options.js', __FILE__ ), array(), '', true );
wp_enqueue_script('maps-init', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBjeU9-8sUIfBFeM5PrwrdkonI0-hdIGQ4&libraries=places&callback=initAutocomplete', array('maps-options'), '', true );
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'jquery-ui-datepicker' );
wp_enqueue_script( 'jquery-ui-dialog' );
wp_enqueue_script( 'datepicker-options',  plugins_url( '../js/datepicker-options.js', __FILE__ ), array('jquery-ui-datepicker') );
wp_enqueue_script( 'dialog-options',  plugins_url( '../js/dialog-options.js', __FILE__ ), array('jquery-ui-dialog') );

if (!is_admin()) { ?>
    <form id="edit_post" name="edit_post" method="post" action="" >
      
        <p>
            <label for="title">T&iacute;tulo de la actividad:</label><br />
            <input type="text" id="title" value="<?php echo esc_attr( $title ); ?>" maxlength="50" name="title" required/>
        </p>
<?php } ?>
	<p>
		<label for="fecha">Fecha:</label><br />
		<input type="text" id="fecha" value="<?php echo esc_attr( $fecha ); ?>" name="fecha" maxlength="50"/>	
	</p>
	
	<p>
		<label for="precio">Precio:</label><br />
		<input type="number" id="precio" name="precio" value="<?php echo esc_attr( $precio ); ?>" maxlength="20" required/>
		<span style="display:block;font-size:0.7em">Si hay varios precios, pon el más bajo y aclara el resto en la descripción.<br />0 si es gratis.</span>
	</p>
	
	<p>
		<label for="edad">Edad:</label><br />
		De <input type="number" id="edad_minima" value="<?php echo esc_attr( $edad_minima ); ?>" name="edad_minima" min="0" max="98" placeholder="0" maxlength="2"/>
		a <input type="number" id="edad_maxima" value="<?php echo esc_attr( $edad_maxima ); ?>" name="edad_maxima" min="1" max="99" placeholder="99" maxlength="2"/> a&ntilde;os
	</p>
	
	<p>			
		<label for="localizacion">Localizaci&oacute;n:</label><br />
		<input id="pac-input" value="<?php echo esc_attr( $localizacion ); ?>"  class="controls" type="text" name="localizacion" maxlength="50" required />
	</p>
	<div id="map"></div>
	<input type="hidden" id="lat" name="lat_mapa" value="<?php echo esc_attr( $lat_mapa ); ?>" maxlength="15"/>
	<input type="hidden" id="lng" name="lng_mapa" value="<?php echo esc_attr( $lng_mapa ); ?>" maxlength="15" />
	
	<p>
		<label for="descripcion">Descripci&oacute;n:</label><br />
		<textarea id="descripcion" name="descripcion" cols="100" rows="5" required><?php echo esc_textarea( $descripcion ); ?></textarea>
	</p>
<?php if (!is_admin()) { ?>
    	<input type="text" name="form_falso" style="display: none" value="" >
    	<?php if (isset($_POST['ID_actividad']) || $postid) { ?>
    	    <input type="submit" name="action_actividad" value="Guardar" />
    	<?php } else { ?>
    	    <input type="submit" name="action_actividad" value="Guardar como borrador" />
    	    <input type="submit" name="action_actividad" value="Guardar y publicar" />
    	<?php } ?>
    	<input type="hidden" id="idpost" name="postid" value="<?php echo $postid; ?>" />
    	
    	<?php wp_nonce_field( 'form_actividad','nonce_form' ); ?>
    </form>
<?php } ?>