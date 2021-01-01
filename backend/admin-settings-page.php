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
		'edit.php?post_type=books-collection', //$parent_slug
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
	register_setting( "lgbc_general_settings", "books_headline" );
	register_setting( "lgbc_general_settings", "books_sort_by" );

	add_settings_section("lgbc_general_settings_section" , "Generel settings", "" , "book_collection_settings");

	add_settings_field( "lgbc_books_headline_field", "Headline books collection:", "lgbc_book_headline", "book_collection_settings", "lgbc_general_settings_section" );
	add_settings_field( "lgbc_books_sort_by_field", "Sort By:", "lgbc_book_sort_by", "book_collection_settings", "lgbc_general_settings_section" );
}

function lgbc_book_headline() {
	?>
    <input type="text" id="lgbc_books_headline" name="books_headline" value="<?php echo get_option("books_headline") ?>">
	<?php
}

function lgbc_book_sort_by() {

	$value_books_sort_by = get_option("books_sort_by");

	?>
    <select name="books_sort_by" id="books_sort_by">
        <option value="name" <?php selected( $value_books_sort_by, "name" ) ?> >Name</option>
        <option value="author" <?php selected( $value_books_sort_by, "author" ) ?> >Author</option>
        <option value="date" <?php selected( $value_books_sort_by, "date" ) ?> >Date</option>
    </select>
	<?php
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
                settings_fields("lgbc_general_settings");
                do_settings_sections("book_collection_settings");
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