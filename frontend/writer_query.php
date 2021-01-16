<?php

function lgbc_shortcode_show_writers() {

	$returnhtml = '';

	?>
    <ul>
		<?php
		$args      = array(
			'taxonomy'   => 'lgbc-writer',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
		);
		$the_query = new WP_Term_Query( $args );

		if ( ! empty( $the_query ) ) {
			ob_start();
			?>

            <ul class="lgbc_writer_query">

				<?php

				foreach ( $the_query->get_terms() as $term ) {
					if ( $term->count > 0 ) {
						?>
                        <li>
							<?php echo $term->name  ?> <br>
                            <div class="lgbc_books_by_author"> Books by this author: <?php echo $term->count ?></div>
                        </li>
						<?php
					}
				}
				?>
            </ul>

			<?php
			$lgbc_output_genre_query = ob_get_contents();
			ob_end_clean();

			return $lgbc_output_genre_query;

			wp_reset_postdata();

		} else {

			$lgbc_output_book_query = '<div class="lgbc_no_books_found_wrapper"> <div class="lgbc_no_books_found">There are no genres.</div> </div>';

			return $lgbc_output_book_query;

		}

		?>
    </ul>

	<?php

	wp_reset_postdata();
}

add_shortcode( 'book-collection-writers', 'lgbc_shortcode_show_writers' );