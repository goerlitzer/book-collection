<?php

function lgbc_shortcode_show_genres() {


	?>
    <ul>
		<?php
		$args      = array(
			'taxonomy'   => 'lgbc-genre',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
		);

		$the_query = new WP_Term_Query( $args );

		if ( ! empty( $the_query ) ) {
			ob_start();

			foreach ( $the_query->get_terms() as $term ) {
				if ( $term->count > 0 ) {
					?>
                    <li> <?php echo $term->name . ' (' . $term->count . ')' ?> </li>
					<?php
				}
			}

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


}

add_shortcode( 'book-collection-genres', 'lgbc_shortcode_show_genres' );