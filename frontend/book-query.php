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
                    <div>
						<?php
						if ( has_post_thumbnail() ) {

							$images = get_the_post_thumbnail_url( $post_id, 'full' );
							?>

                            <a href="<?php echo $images; ?>" data-toggle="lightbox" data-max-height="700"
                               data-gallery="lgbc_book_id_<?php echo $post_id; ?>">
                                <img class="img-fluid img_hide" src="<?php echo $images; ?>"
                                     alt="<?php echo $images['alt']; ?>"/>
                            </a>

                            <div class="lgbc_zoom_icon">
                                <img src="<?php echo plugin_dir_url( __DIR__ ) ?>/img/zoom_icon.svg">
                            </div>

							<?php

						} else { ?>

                            <img alt="Bookcover Placeholder"
                                 src="<?php echo plugin_dir_url( __DIR__ ) ?>/img/book_placeholder.svg">
							<?php
						}
						?>


                    </div>
                </div>
                <div class="lgbc_book_content">

                    <h2>
						<?php the_title(); ?>
                    </h2>

                    <!--
                    <div>
                        Published: <span class="entry-date"><?php echo get_the_date(); ?></span>
                    </div>
                    -->

                    <div class="lgbc_book_author">
						<?php

						$second_loop = false;

						echo '<div class="lgbc_book_author_author"> by ';
						foreach ( get_the_terms( $post_id, 'lgbc-writer' ) as $tax ) {

							if ($second_loop){
								echo ' & ' . $tax->name ;
							} else {
								echo $tax->name;
							}
							$second_loop = true;

						}
						echo '</div>';

						$second_loop = false;

						echo '<div class="lgbc_book_author_genre"> Genre: ';
						foreach ( get_the_terms( $post_id, 'lgbc-genre' ) as $tax ) {

						    if ($second_loop){
							    echo ' & ' . $tax->name ;
						    } else {
							    echo $tax->name;
						    }
							$second_loop = true;

						}
						echo '</div>';

						?>

                    </div>

                    <div>
                        <ul class="lgbc_book_details">
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

								echo '<li> Book Format: ' . $lgbc_book_format . '</li>';
							}

							$lgbc_isbn = esc_attr( get_post_meta( get_the_ID(), 'lgbc_isbn', true ) );
							if ( $lgbc_isbn ) {
								echo '<li> ISBN: ' . $lgbc_isbn . '</li>';
							}

							$lgbc_published_date = esc_attr( get_post_meta( get_the_ID(), 'lgbc_published_date', true ) );
							if ( $lgbc_published_date ) {
								echo '<li> Published On: ' . $lgbc_published_date . '</li>';
							}

							$lgbc_publisher = esc_attr( get_post_meta( get_the_ID(), 'lgbc_publisher', true ) );
							if ( $lgbc_publisher ) {
								echo '<li> Publisher: ' . $lgbc_publisher . '</li>';
							}
							$lgbc_pages = esc_attr( get_post_meta( get_the_ID(), 'lgbc_pages', true ) );
							if ( $lgbc_pages ) {
								echo '<li> Number of Pages: ' . $lgbc_pages . '</li>';
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