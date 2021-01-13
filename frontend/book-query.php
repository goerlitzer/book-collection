<?php
// >> Create Shortcode to Display Movies Post Types

function lgbc_shortcode_show_books() {

	$lgbc_output_book_query = '';
	?>
	<?php if ( get_option( "books_headline" ) ) {

		$value_books_headline_align = get_option( "books_headline_align" );
		$value_books_headline_size  = get_option( "books_headline_size" );

		$lgbc_output_book_query = '<h2 style="text-align: ' . $value_books_headline_align . ' ; font-size:' . $value_books_headline_size . 'px  ">' . get_option( "books_headline" ) . ' </h2>';

	} ?>

	<?php

	$value_books_sort_by = get_option( "books_sort_by" );

	$value_books_grids_desktop = get_option( "books_grids_desktop" );
	echo '<div>Grid ' . $value_books_grids_desktop . '</div>';

	$lgbc_output_book_query .= '<div class="lgbc_book_query_wrapper lgbc_grid_wrapper_' . $value_books_grids_desktop .'">';

	$args = array(
		'post_type'      => 'books_collection',
		'posts_per_page' => '10000',
		'publish_status' => 'published',
		'order'          => 'ASC',
		'orderby'        => $value_books_sort_by,
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		ob_start();

		while ( $the_query->have_posts() ) : $the_query->the_post();
			$post_id = get_the_ID();

			?>

            <div class="lgbc_book lgbc_grid_<?php echo $value_books_grids_desktop ?>">
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
                                <img src="<?php echo plugin_dir_url( __DIR__ ) ?>/img/zoom_icon.svg" alt="zoom">
                            </div>

							<?php

						} else { ?>

                            <img alt="Bookcover Placeholder"
                                 src="<?php echo plugin_dir_url( __DIR__ ) ?>/img/book_placeholder.jpg">
							<?php
						}
						?>


                    </div>
                </div>
                <div class="lgbc_book_content">

                    <h2>
						<?php the_title(); ?>
                    </h2>

                    <div class="lgbc_book_author">
						<?php

						$second_loop = false;

						echo '<div class="lgbc_book_author_author"> by ';
						foreach ( get_the_terms( $post_id, 'lgbc-writer' ) as $tax ) {

							if ( $second_loop ) {
								echo ' & ' . $tax->name;
							} else {
								echo $tax->name;
							}
							$second_loop = true;

						}
						echo '</div>';


						?>

                    </div>

                    <div class="lgbc_book_author">
                        Published: <?php echo get_the_date(); ?>
                    </div>

                    <div>
                        <ul class="lgbc_book_details">
							<?php

							$second_loop = false;

							echo '<li class="lgbc_book_author_genre"> Genre: ';
							foreach ( get_the_terms( $post_id, 'lgbc-genre' ) as $tax ) {

								if ( $second_loop ) {
									echo ' & ' . $tax->name;
								} else {
									echo $tax->name;
								}
								$second_loop = true;

							}
							echo '</li>';

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

					<?php


					echo $content_full = apply_filters( 'the_content', get_the_content() );

					/*
					$text  = $content_full;
					$words = 50;
					$more  = ' …';

					echo $content_short = wp_trim_words( $text, $words, $more );

					echo '<a class="moretag" href="' . get_permalink( $post_id ) . '"> read more</a>' */

					?>

                </div>

            </div>

		<?php endwhile;

		$lgbc_output_book_query .= ob_get_contents();
		ob_end_clean();

		return $lgbc_output_book_query;

		wp_reset_postdata();

		$lgbc_output_book_query .= '</div>';

	else :

		$lgbc_output_book_query .= '<div class="lgbc_no_books_found_wrapper"> <div class="lgbc_no_books_found">No books were found.</div> </div>';

		return $lgbc_output_book_query;
	endif;
}

add_shortcode( 'book-collection-list', 'lgbc_shortcode_show_books' );