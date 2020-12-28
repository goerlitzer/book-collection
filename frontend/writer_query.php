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

			foreach ( $the_query->get_terms() as $term ) {

				$returnhtml .= '<li>' . $term->name . ' (' . $term->count . ') </li>';

			}

			return $returnhtml;

		} else { ?>
            <p><?php _e( 'Es wurden keine Authoren gefunden.' ); ?></p>
			<?php
		}

		?>
    </ul>

	<?php

	wp_reset_postdata();
}

add_shortcode( 'book-collection-writers', 'lgbc_shortcode_show_writers' );