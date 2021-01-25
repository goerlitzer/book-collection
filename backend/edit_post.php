<?php

add_action( "add_meta_boxes", "lgbc_books_metabox" );
function lgbc_books_metabox() {

	add_meta_box( "lgbc_books_metabox", "Buchangaben", "lgbc_book_details", "books_collection" );
}


add_action( "save_post", "lgbc_save_book_details" );
function lgbc_save_book_details( $post_id ) {
	if ( array_key_exists( "lgbc_book_format", $_POST ) ) {
		update_post_meta( $post_id, "lgbc_book_format", $_POST['lgbc_book_format'] );
	}
	if ( array_key_exists( "lgbc_published_date", $_POST ) ) {
		update_post_meta( $post_id, "lgbc_published_date", $_POST['lgbc_published_date'] );
	}
	if ( array_key_exists( "lgbc_publisher", $_POST ) ) {
		update_post_meta( $post_id, "lgbc_publisher", $_POST['lgbc_publisher'] );
	}
	if ( array_key_exists( "lgbc_pages", $_POST ) ) {
		update_post_meta( $post_id, "lgbc_pages", $_POST['lgbc_pages'] );
	}
	if ( array_key_exists( "lgbc_isbn", $_POST ) ) {
		update_post_meta( $post_id, "lgbc_isbn", $_POST['lgbc_isbn'] );
	}
}

function lgbc_book_details( $post ) {

	if ( get_post_type( get_the_ID() ) == 'books_collection' ) {

		$value_book_format = get_post_meta( $post->ID, "lgbc_book_format", true );

		?>
        <p class="meta-options hcf_field">
            <label for="lgbc_book_format" id="book_format" class="metabox_label">Book format:</label>
            <select name="lgbc_book_format" id="lgbc_book_format">
                <option value="ebook" <?php selected( $value_book_format, "ebook" ) ?> >E-Book</option>
                <option value="gebunden" <?php selected( $value_book_format, "gebunden" ) ?> >Gebundenes Buch</option>
            </select>
        </p>

        <p class="meta-options hcf_field">
            <label class="metabox_label" for="lgbc_isbn">ISBN:</label>
            <input id="lgbc_isbn" type="text" name="lgbc_isbn"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'lgbc_isbn', true ) ); ?>">
        </p>

        <p class="meta-options hcf_field">
            <label class="metabox_label" for="lgbc_published_date">Published On:</label>
            <input id="lgbc_published_date" type="date" name="lgbc_published_date"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'lgbc_published_date', true ) ); ?>">
        </p>


        <p class="meta-options hcf_field">
            <label class="metabox_label" for="lgbc_publisher">Publisher:</label>
            <input id="lgbc_publisher" type="text" name="lgbc_publisher"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'lgbc_publisher', true ) ); ?>">
        </p>

        <p class="meta-options hcf_field">
            <label class="metabox_label" for="lgbc_pages">Number of Pages:</label>
            <input id="lgbc_pages" type="number" name="lgbc_pages"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'lgbc_pages', true ) ); ?>">
        </p>


		<?php

	}


}