<?php
// >> Create Shortcode to Display Movies Post Types

function diwp_create_shortcode_movies_post_type() {

	$args = array(
		'post_type'      => 'books-collection',
		'posts_per_page' => '10',
		'publish_status' => 'published',
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :

		// pagination here
		// the loop
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$post_id = get_the_ID();

			?>

            <div>
                <div class="lgbc_book_cover">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail();
					} else {
						?>
                        <img alt="Bookcover Placeholder"
                             src="<?php echo plugin_dir_url( __DIR__ ) ?>/img/book_placeholder.svg">
						<?php
					}
					?>
                </div>
                <h2>
					<?php the_title(); ?>
                </h2>
                <div>
					<?php
					$value_book_format = get_post_meta( $post_id, "book_format", true );

					switch ( $value_book_format ) {
						case "ebook":
							$value_book_format = "E-Book";
							break;
						case "gebunden":
							$value_book_format = "Gebundes Buch";
							break;
					}
					echo 'Buchformat: ' . $value_book_format;
					?>

                    <ul>
                        <li>Buchformat: <?php echo $value_book_format ?></li>
                        <li>
                            ISBN: <?php echo esc_attr( get_post_meta( get_the_ID(), 'hcf_published_date', true ) ); ?></li>
                        <li>
                            Veröffentlichungsdatum: <?php echo esc_attr( get_post_meta( get_the_ID(), 'hcf_price', true ) ); ?></li>
                        <li>Verlag: xxx</li>
                        <li>Seiteanzahl:</li>
                    </ul>
                </div>

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

add_shortcode( 'book-collection-list', 'diwp_create_shortcode_movies_post_type' );