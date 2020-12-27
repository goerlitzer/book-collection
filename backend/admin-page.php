<?php

/**
 * Custom Post Type add Subpage to Custom Post Menu
 * @description- Njengah Tutorial Custom Post Type Submenu Example
 * @link - https://gist.github.com/Njengah/0764f2c88742c19b67a212c914c9f25f
 *
 */

// Hook

add_action('admin_menu', 'add_tutorial_cpt_submenu_example');

//admin_menu callback function

function add_tutorial_cpt_submenu_example(){

	add_submenu_page(
		'edit.php?post_type=books', //$parent_slug
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

	<div class="wrap">
		<h1 class="wp-heading-inline">Buch Collection Einstellung</h1>
		<div>
			Einstellungen
		</div>
	</div>

<?php
}