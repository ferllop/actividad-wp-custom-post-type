<?php 
if ( !is_user_logged_in()) {
    $error = do_shortcode( '[theme-my-login]' );
    return $error;
} 

function interested_in() {
    $userid = get_current_user_id();
    $user_interests = get_user_meta($userid, 'interesado_actividades', true);
    $user_interests = rtrim(trim($user_interests), ',');
    $user_interests_array = explode(',', $user_interests);
    ?> 
    <div class="interests-list">
        <h3>Te interesan</h3>
        <ul> 
            <?php
            if ($user_interests == ''){ ?>
                <br />
                <div class="info" id="message">
                    <p>
                        <?php echo 'No has mostrado interés en ninguna actividad.'; ?>
                    </p> 
                </div>
            <?php } else {
                foreach ($user_interests_array as $user_interest){ 
                    if ( get_post_status ( $user_interest ) == 'publish' ) {
                        $user_interest_title = get_the_title($user_interest);
                        $user_interest_slug = get_the_permalink($user_interest);
                        ?>
                        <li><a href="<?php echo $user_interest_slug ?>"><?php echo $user_interest_title ?></a></li>
                    <?php }
                }
            }?>
        </ul>
    </div>
    <?php
}

function loop_actividades() { ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
        <div class="entry-content">
            <div class="list-wrapper">
                <div class="list-header">
                    <div class="list-item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div> 
                </div> 
                <!--<div class="coupon-info">-->
                <!--    <div class="coupon-image"><?php echo get_the_post_thumbnail( get_the_ID(), array( 200, 150 ) ); ?></div> -->
                <!--    <div class="coupon-description"><?php the_content(); ?></div> -->
                <!--</div> -->
                <div class="list-items-buttons">
                    <form class="edit-button" action="/modificar-actividad/" method="post"> 
                    	<input type="hidden" name="ID_actividad" value="<?php the_ID() ?>" /> 
        				<input type="text" name="edit_falso" style="display: none" value="">
        				<?php wp_nonce_field( 'form_actividad','nonce_form' ); ?>
                        <input type="submit" name="action_actividad" value="Editar" />
                        <input type="submit" name="action_actividad" value="<?php echo (get_post_status( get_the_ID() ) == 'publish') ? 'Borrador' : 'Publicar' ?>" />
                        <input type="submit" name="action_actividad" value="Borrar" />
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div id="content" role="main">
    <?php interested_in(); ?>
    
    <h3>Publicadas</h3>
    <?php 
    query_posts( array( 
        'author' => get_current_user_id(),
        'post_status' => 'publish' , 
        'post_type' => array( 'actividad' )  
        ) ); 

    if ( !have_posts() ): ?> 
        <br />
        <div class="info" id="message">
            <p>
                <?php _e( 'No tienes actividades publicadas.', 'actividad' ); ?>
            </p> 
        </div>
    <?php endif; 

    if ( have_posts() ) while ( have_posts() ) : the_post();
    loop_actividades();
    endwhile; 
    
    wp_reset_query();?>
    
    <div class="clear"></div>

    <h3>Sin Publicar</h3>
    <?php query_posts( array( 
        'author' => get_current_user_id(),
        'post_status' => 'draft' , 
        'post_type' => array( 'actividad' )  ) ); 

    if ( !have_posts() ): ?>
        <br />
        <div class="info" id="message">
            <p><?php _e( 'No tienes actividades en borrador', 'actividad' ); ?></p> 
        </div>
    <?php endif; 

    if ( have_posts() ) while ( have_posts() ) : the_post();  
        loop_actividades();
    endwhile; 
    
    wp_reset_query(); ?>
</div>
