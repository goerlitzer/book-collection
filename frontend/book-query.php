<?php
// >> Create Shortcode to Display Movies Post Types

function lgbc_shortcode_show_books() {

	?>
	<?php if ( get_option( "books_headline" ) ) { ?>
        <h2>
			<?php echo get_option( "books_headline" ); ?>
        </h2>
	<?php } ?>
	<?php

	$value_books_sort_by = get_option( "books_sort_by" );

	$value_books_grids_desktop = get_option( "books_grids_desktop" );
	echo '<div>Grid ' . $value_books_grids_desktop . '</div>';

	$args = array(
		'post_type'      => 'books-collection',
		'posts_per_page' => '10000',
		'publish_status' => 'published',
		'order'          => 'ASC',
		'orderby'        => $value_books_sort_by,
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :

		// pagination here
		// the loop
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$post_id = get_the_ID();

			?>

            <div class="lgbc_book lgbc_grid_0">
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
                <div class="lgbc_book_content">

                    <h2>
						<?php the_title(); ?>
                    </h2>

                    <div>
                        Erstellt am: <span class="entry-date"><?php echo get_the_date(); ?></span>
                    </div>

                    <div>
						<?php

						echo '<p> Genre: ';
						foreach ( get_the_terms( $post_id, 'lgbc-writer' ) as $tax ) {
							echo $tax->name . ', ';
						}
						echo '</p>';

						echo '<p> Genre: ';
						foreach ( get_the_terms( $post_id, 'lgbc-genre' ) as $tax ) {
							echo $tax->name . ', ';
						}
						echo '</p>';

						?>

                    </div>

                    <div>
                        <ul>
							<?php

							$lgbc_book_format = get_post_meta( $post_id, "lgbc_book_format", true );
							if ( $lgbc_book_format ) {

								switch ( $lgbc_book_format ) {
									case "ebook":
										$lgbc_book_format = "E-Book";
										break;
									case "gebunden":
										$lgbc_book_format = "Gebundes Buch";
										break;
								}

								echo '<li> Buchformat: ' . $lgbc_book_format . '</li>';
							}

							$lgbc_isbn = esc_attr( get_post_meta( get_the_ID(), 'lgbc_isbn', true ) );
							if ( $lgbc_isbn ) {
								echo '<li> ISBN: ' . $lgbc_isbn . '</li>';
							}

							$lgbc_published_date = esc_attr( get_post_meta( get_the_ID(), 'lgbc_published_date', true ) );
							if ( $lgbc_published_date ) {
								echo '<li> Veröffentlichungsdatum: ' . $lgbc_published_date . '</li>';
							}

							$lgbc_publisher = esc_attr( get_post_meta( get_the_ID(), 'lgbc_publisher', true ) );
							if ( $lgbc_publisher ) {
								echo '<li> Verlag: ' . $lgbc_publisher . '</li>';
							}
							$lgbc_pages = esc_attr( get_post_meta( get_the_ID(), 'lgbc_pages', true ) );
							if ( $lgbc_pages ) {
								echo '<li> Seiteanzahl: ' . $lgbc_pages . '</li>';
							}

							?>

                        </ul>
                    </div>

	                <?php the_content(); ?>

                </div>

            </div>

		<?php endwhile;
		// end of the loop
		// pagination here

		wp_reset_postdata();

	else : ?>
        <p><?php _e( 'Es wurden keine Bücher gefunden.' ); ?></p>
	<?php endif;
}

add_shortcode( 'book-collection-list', 'lgbc_shortcode_show_books' );