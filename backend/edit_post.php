<?php

add_action( "add_meta_boxes", "lgbc_books_metabox" );
function lgbc_books_metabox() {

	add_meta_box( "lgbc_books_metabox", "Buchangaben", "lgbc_book_details" );
}

add_action( "save_post", "lgbc_save_book_details" );
function lgbc_save_book_details( $post_id ) {
	if ( array_key_exists( "book_format", $_POST ) ) {
		update_post_meta( $post_id, "book_format", $_POST['book_format'] );
	}
}

function lgbc_book_details( $post ) {
	$value_book_format = get_post_meta( $post->ID, "book_format", true );
	?>
	<label id="book_format" class="metabox_label">Buchformat:</label>
	<select name="book_format" id="book_format">
		<option value="ebook" <?php selected( $value_book_format, "ebook" ) ?> >E-Book</option>
		<option value="gebunden" <?php selected( $value_book_format, "gebunden" ) ?> >Gebundenes Buch</option>
	</select>
	<?php
}