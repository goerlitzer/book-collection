<?php

/**
 * Custom Post Type add Subpage to Custom Post Menu
 * @description- Njengah Tutorial Custom Post Type Submenu Example
 * @link - https://gist.github.com/Njengah/0764f2c88742c19b67a212c914c9f25f
 *
 */

// Hook

add_action( 'admin_menu', 'add_tutorial_cpt_submenu_example' );

//admin_menu callback function

function add_tutorial_cpt_submenu_example() {

	add_submenu_page(
		'edit.php?post_type=books-collection', //$parent_slug
		'Buchsammlung Einstellugen',  //$page_title
		'Einstellungen',        //$menu_title
		'manage_options',           //$capability
		'book_collection_settings',//$menu_slug
		'book_collection_settings_render_page'//$function
	);

}

//add_submenu_page callback function

function book_collection_settings_render_page() {
	?>

    <div class="wrap lgbc_settings_page metabox-holder">

    <div class="lgbc_settings_head lgbc_admin_padding_25 postbox">

        <h1>Einstellung Büchersammlung</h1>

        <p>von Lars Görlitzer <?php echo 'v' . LGBC_VERSION ?> </p>

    </div>

    <div class="lgbc_settings_block_left">
        <div id="pageparentdiv" class="postbox">
            <div class="postbox-header">
                <h2 class="hndle ui-sortable-handle">Information zum Plugin</h2>
            </div>
            <p class="inside">
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
            </p>

        </div>
    </div>

    <div class="lgbc_settings_block_middle">.</div>

    <div class="lgbc_settings_block_right">
        <div id="pageparentdiv" class="postbox">
            <div class="postbox-header">
                <h2 class="hndle ui-sortable-handle">Allgemein</h2>
            </div>
            <div class="inside">
                <p>Shortcodes</p>
                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="blogname">Bücher</label></th>
                        <td><label class="label_shortcode">[book-collection-list]</label>
                            <p>Nutze den Shortcode zum Anzeigen der Bückersammlung in einem Beirag oder auf
                                einer Seite</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="blogname">Authoren</label></th>
                        <td><label class="label_shortcode">[book-collection-writers]</label>
                            <p>Nutze den Shortcode für die Auflistung aller Authoren</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="blogname">Genres</label></th>
                        <td><label class="label_shortcode">[book-collection-genres]</label>
                            <p>Nutze den Shortcode für die Auflistung aller verwendeten Gernes</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


	<?php
}