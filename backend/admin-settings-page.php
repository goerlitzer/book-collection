<?php

/**
 * Custom Post Type add Subpage to Custom Post Menu
 * @description- Njengah Tutorial Custom Post Type Submenu Example
 * @link - https://gist.github.com/Njengah/0764f2c88742c19b67a212c914c9f25f
 *
 */

// Hook

add_action( 'admin_menu', 'add_book_settings_submenu' );

//admin_menu callback function

function add_book_settings_submenu() {

	add_submenu_page(
		'edit.php?post_type=books_collection', //$parent_slug
		'Settings Book Collection',  //$page_title
		'Settings',        //$menu_title
		'manage_options',           //$capability
		'book_collection_settings',//$menu_slug
		'book_collection_settings_render_page'//$function
	);

}

//add_submenu_page callback function

add_action( "admin_init", "lgbc_admin_settings_option" );

function lgbc_admin_settings_option() {

	//section Generel settings
	add_settings_section( "lgbc_general_settings_section", "Generel settings", "", "book_collection_settings" );
	add_settings_section( "lgbc_display_settings_section", "Display settings", "", "book_collection_settings" );

	register_setting( "lgbc_general_settings", "books_headline" );
	register_setting( "lgbc_general_settings", "books_headline_size" );
	register_setting( "lgbc_general_settings", "books_headline_align" );
	register_setting( "lgbc_general_settings", "books_sort_by" );
	register_setting( "lgbc_general_settings", "book_headline_size" );
	register_setting( "lgbc_general_settings", "book_headline_style" );
	register_setting( "lgbc_general_settings", "books_grids_desktop" );
	register_setting( "lgbc_general_settings", "books_color_book_headline" );

	add_settings_field( "lgbc_books_headline_field", "Headline book collection: <br> <span class='lgbc_settings_note'>(optional)</span>", "lgbc_book_headline", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_books_headline_size", "Headline size: <br> <span class='lgbc_settings_note'>in px</span>", "lgbc_books_headline_size", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_books_headline_align_field", "Headline - align:", "lgbc_books_headline_align", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_books_sort_by_field", "Books sort by:", "lgbc_book_sort_by", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_book_headline_size_field", "Book headline size:", "lgbc_book_headline_size", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_book_headline_style_field", "Book headline style:", "lgbc_book_headline_style", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_books_grids_desktop_field", "Gallary Columns (Desktop):", "lgbc_books_grids_desktop", "book_collection_settings", "lgbc_display_settings_section" );
	add_settings_field( "lgbc_books_color_book_headline", "Color Book Headline:", "lgbc_books_color_book_headline", "book_collection_settings", "lgbc_display_settings_section" );


}

function lgbc_book_headline() {
	?>
    <input type="text" id="lgbc_books_headline" name="books_headline"
           value="<?php echo get_option( "books_headline" ) ?>">
	<?php
}

function lgbc_books_headline_size(){
	?>
    <input type="number" id="lgbc_books_headline_size" name="books_headline_size"
           value="<?php echo get_option( "books_headline_size" ) ?>">
	<?php
}

function lgbc_books_headline_align() {

	$value_books_headline_align = get_option( "books_headline_align" );

	?>
    <select name="books_headline_align" id="books_headline_align">
        <option value="left" <?php selected( $value_books_headline_align, "left" ) ?> >Left</option>
        <option value="center" <?php selected( $value_books_headline_align, "center" ) ?> >Center</option>
        <option value="right" <?php selected( $value_books_headline_align, "right" ) ?> >Right</option>
    </select>
	<?php

}

function lgbc_book_sort_by() {

	$value_books_sort_by = get_option( "books_sort_by" );

	?>
    <select name="books_sort_by" id="books_sort_by">
        <option value="name" <?php selected( $value_books_sort_by, "name" ) ?> >Name (default)</option>
        <option value="date" <?php selected( $value_books_sort_by, "date" ) ?> >Date of Creation</option>
    </select>
	<?php
}

function lgbc_book_headline_size(){
	?>
    <input type="number" id="lgbc_book_headline_size" name="book_headline_size"
           value="<?php echo get_option( "book_headline_size" ) ?>">
	<?php
}

function lgbc_book_headline_style(){

	$value_book_headline_style = get_option( "book_headline_style" );

	?>
    <select name="book_headline_style" id="book_headline_style">
        <option value="normal" <?php selected( $value_book_headline_style, "normal" ) ?> >Normal</option>
        <option value="bold" <?php selected( $value_book_headline_style, "bold" ) ?> >Bold</option>
    </select>
	<?php

}

function lgbc_books_grids_desktop() {

	$value_books_grids_desktop = get_option( "books_grids_desktop" );

	?>
    <select name="books_grids_desktop" id="books_grids_desktop">
        <option value="0" <?php selected( $value_books_grids_desktop, "0" ) ?> >full width (default)</option>
        <option value="2" <?php selected( $value_books_grids_desktop, "2" ) ?> >2</option>
        <option value="3" <?php selected( $value_books_grids_desktop, "3" ) ?> >3 (comming soon)</option>
        <option value="4" <?php selected( $value_books_grids_desktop, "4" ) ?> >4 (comming soon)</option>
    </select>
	<?php
}

function lgbc_books_color_book_headline(){
    echo "Color Picker";
}

function book_collection_settings_render_page() {
	?>

    <div class="wrap lgbc_settings_page metabox-holder">

    <div class="lgbc_settings_head lgbc_admin_padding_25 postbox">

        <h1>Settings - Book Collection</h1>

        <p>by Lars Görlitzer <?php echo 'v' . LGBC_VERSION ?> </p>

    </div>

    <div class="lgbc_settings_block_left">
        <div id="pageparentdiv" class="postbox">
            <div class="postbox-header">
                <h2 class="hndle ui-sortable-handle">General</h2>
            </div>
            <form action="options.php" method="post" class="inside">
				<?php
				settings_fields( "lgbc_general_settings" );
				do_settings_sections( "book_collection_settings" );

				submit_button();

				?>

            </form>

        </div>
    </div>

    <div class="lgbc_settings_block_middle">.</div>

    <div class="lgbc_settings_block_right">
        <div id="pageparentdiv" class="postbox">
            <div class="postbox-header">
                <h2 class="hndle ui-sortable-handle">Usage</h2>
            </div>
            <div class="inside">
                <p>Shortcodes</p>
                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="blogname">Books</label></th>
                        <td><label class="label_shortcode">[book-collection-list]</label>
                            <p>Use the shortcode to display the book collection in a post or on a page</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="blogname">Writers</label></th>
                        <td><label class="label_shortcode">[book-collection-writers]</label>
                            <p>Use the shortcode for listing all authors</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="blogname">Genres</label></th>
                        <td><label class="label_shortcode">[book-collection-genres]</label>
                            <p>Use the shortcode for listing all used genres</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


	<?php
}