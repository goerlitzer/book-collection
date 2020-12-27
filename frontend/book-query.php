<?php
// >> Create Shortcode to Display Movies Post Types

function diwp_create_shortcode_movies_post_type() {

	$args = array(
		'post_type'      => 'books',
		'posts_per_page' => '10',
		'publish_status' => 'published',
	);

	$the_query = new WP_Query( $args );


	if ( $the_query->have_posts() ) :

		// pagination here

		// the loop
		while ( $the_query->have_posts() ) : $the_query->the_post(); ?>


            <div>
                <div class="lgbc_book_cover">
					<?php
					if ( the_post_thumbnail() ) {
						the_post_thumbnail();
					} else {


						?>
                        <img src="<?php echo plugin_dir_url( __DIR__ ) ?>/img/book_placeholder.svg">
						<?php
					}


					?>
                </div>

				<?php the_title(); ?>
				<?php the_content(); ?>
            </div>


		<?php endwhile;
		// end of the loop
		// pagination here

		wp_reset_postdata();

	else : ?>
        <p><?php _e( 'Es wurden keine Bücher gefunden.' ); ?></p>
	<?php endif;
}

add_shortcode( 'book-collection', 'diwp_create_shortcode_movies_post_type' );