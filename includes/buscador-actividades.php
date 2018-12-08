<form role="search" method="get" id="searchform" action="">
	<!-- <label for="se">Search for:</label> -->
	<input type="text" value="" name="se" id="se" maxlength="50"/>
	<!-- <input type="hidden" value="1" name="sentence" />
	<input type="hidden" name="post_type" value="actividad" /> -->
	<input type="submit" id="searchsubmit" value="Buscar" />
</form> 

<?php
if ( isset( $_GET['se'] ) ) {
	$thesearch = sanitize_text_field( $_GET['se'] );
	$args = array (	
		'post_type'  => 'actividad', 
		'_meta_or_title' => $thesearch,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'	 	=> 'precio',
				'value'	  	=> $thesearch,
				'compare' 	=> 'LIKE',
			),
			array(
				'key'	  	=> 'localizacion',
				'value'	  	=> $thesearch,
				'compare' 	=> 'LIKE',
			),
			array(
				'key'	  	=> 'descripcion',
				'value'	  	=> $thesearch,
				'compare' 	=> 'LIKE',
			)
		)
	);
} else {
	$thesearch = '';
	$args = array (	'post_type'  => 'actividad', 'status' => 'publish' );
}

$loop = new WP_Query( $args );
$is_logged = is_user_logged_in();

if ( $loop->have_posts() ) { 
	if ( $thesearch ) { ?>
		<p>
			Esto es lo que se ha encontrado para la búsqueda: <?php echo $thesearch ?>
		</p>
	<?php } ?>
	<ul class="galeria"> <?php
		while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<li class="item-galeria">
				<div class="ptitle">
					<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				</div>
				
				<?php 
				$fecha = get_post_meta( get_the_ID(), 'fecha', true ); 
				if (!empty($fecha)) { ?>
					<div class="fecha">Fecha: 
						<?php 
						$fecha = get_post_meta( get_the_ID(), 'fecha', true ); 
						echo $fecha; 
						?>
					</div>
				<?php 
				} 
				?>
				
				<div class="localizacion">Localización: <?php 
					$localizacion = get_post_meta( get_the_ID(), 'localizacion', true ); 
					echo $localizacion; ?>
				</div>
				<div class="descripcion">Descripción: <br /><?php 
					$descripcion = get_post_meta( get_the_ID(), 'descripcion', true ); 
					echo $descripcion; ?>
				</div>
				
				<?php 
				$edad_minima = get_post_meta( get_the_ID(), 'edad_minima', true );
				$edad_maxima = get_post_meta( get_the_ID(), 'edad_maxima', true );
				if ( !empty($edad_minima) && !empty($edad_maxima) ) { ?>
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
			</li><?php 
		endwhile; ?>
	</ul><?php
 	if (  $loop->max_num_pages > 1 ) : ?>
 		<div id="nav-below" class="navigation">-->
 			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous', 'domain' ) ); ?></div>-->
 			<div class="nav-next"><?php previous_posts_link( __( 'Next <span class="meta-nav">&rarr;</span>', 'domain' ) ); ?></div>-->
 		</div>-->
 	<?php endif;
} else { 
	echo 'No se ha encontrado ninguna actividad con la búsqueda: ' . $thesearch;
}
wp_reset_postdata();
?>