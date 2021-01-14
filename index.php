<?php

/*
Plugin Name: Books Collection
Plugin URI: https://larsgoerlitzer.de
Description: Plugin to collect books, comics or similar, with separate listing of authors and genres.
Version: 0.1
Author: LGdesign
Author URI: https://larsgoerlitzer.de
License: A "Slug" license name e.g. GPL2
*/


//https://wordpress.org/plugins/wp-books-gallery/


/*
 * settings page
 * https://deliciousbrains.com/create-wordpress-plugin-settings-page/
 *
 * details page
 * https://wordpress.stackexchange.com/questions/162146/plugin-view-details-link
 */

if ( ! defined( 'ABSPATH' ) ) die( 'Nope' );
///Throw out if unallowed access

// Defining constants
if( ! defined( 'LGBC_VERSION' ) ) define( 'LGBC_VERSION', '0.1' );
if( ! defined( 'LGBC_MENU_POSITION' ) ) define( 'LGBC_MENU_POSITION', 30 );
if( ! defined( 'LGBC_PLUGIN_DIR' ) ) define( 'LGBC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
//if( ! defined( 'LGBC_PLUGIN_URI' ) ) define( 'LGBC_PLUGIN_URI', plugins_url( '', __FILE__ ) );
//if( ! defined( 'LGBC_FILES_DIR' ) ) define( 'LGBC_FILES_DIR', LGBC_PLUGIN_DIR );
//if( ! defined( 'LGBC_FILES_URI' ) ) define( 'LGBC_FILES_URI', LGBC_PLUGIN_URI );

require_once LGBC_PLUGIN_DIR . '/frontend/book-query.php';
require_once LGBC_PLUGIN_DIR . '/frontend/writer_query.php';
require_once LGBC_PLUGIN_DIR . '/frontend/genre_query.php';
require_once LGBC_PLUGIN_DIR . '/backend/admin-settings-page.php';
require_once LGBC_PLUGIN_DIR . '/backend/edit_post.php';





//load frontend scripts
add_action( "wp_enqueue_scripts", "add_scripts" );

function add_scripts() {
	//css
	wp_enqueue_style( "lgbc_style", plugins_url( "css/lgbc_style.css", __FILE__ ), "", null );
	wp_enqueue_style( "lgbc_style_ekko-lightbox", plugins_url( "css/lgbc_ekko-lightbox.css", __FILE__ ), "", null );
	wp_enqueue_style( "lgbc_style_bootstrap", plugins_url( "css/lgbc_bootstrap.min.css", __FILE__ ), "", null );
	//js
	wp_enqueue_script( "lgbc_script_jq", plugins_url( "js/lgbc_query-3.5.1.min.js", __FILE__ ), "", null );
	wp_enqueue_script( "lgbc_script_propper", plugins_url( "js/lgbc_popper.min.js", __FILE__ ), "", null );
	wp_enqueue_script( "lgbc_script_bootstrap", plugins_url( "js/lgbc_bootstrap.min.js", __FILE__ ), "", null );
	wp_enqueue_script( "lgbc_script", plugins_url( "js/lgbc_script.js", __FILE__ ), "", null );
	wp_enqueue_script( "lgbc_script_ekko_lightbox", plugins_url( "js/lgbc_ekko-lightbox.js", __FILE__ ), "", null );

}

//load admin page scripts
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

function load_custom_wp_admin_style() {
	wp_enqueue_style( "lgbc_admin_style", plugins_url( "css/lgbc_admin_style.css", __FILE__ ), "", null );
}


//CPT

function custom_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'               => _x( 'Book Collection', 'Post Type General Name', 'twentytwenty' ),
		'singular_name'      => _x( 'Book', 'Post Type Singular Name', 'twentytwenty' ),
		'menu_name'          => __( 'Book Collection', 'twentytwenty' ),
		'parent_item_colon'  => __( 'Parent Movie', 'twentytwenty' ),
		'all_items'          => __( 'All Books', 'twentytwenty' ),
		'view_item'          => __( 'View Book', 'twentytwenty' ),
		'add_new_item'       => __( 'Add Book', 'twentytwenty' ),
		'add_new'            => __( 'Add Book', 'twentytwenty' ),
		'edit_item'          => __( 'Edit Book', 'twentytwenty' ),
		'featured_image'     => __( 'Book Cover', 'textdomain' ),    //used in post.php
		'set_featured_image' => __( 'Add Book Cover', 'textdomain' ),    //used in post.php
		'update_item'        => __( 'Update Movie', 'twentytwenty' ),
		'search_items'       => __( 'Search Movie', 'twentytwenty' ),
		'not_found'          => __( 'Not Found', 'twentytwenty' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'twentytwenty' ),
	);

// Set other options for Custom Post Type

	$args = array(
		'label'               => __( 'books_collection', 'twentytwenty' ),
		'description'         => __( 'Book Collection', 'twentytwenty' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		//'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		// You can associate this CPT with a taxonomy or custom taxonomy.
		'menu_icon'           => 'dashicons-book-alt',
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => LGBC_MENU_POSITION,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => false,

	);

	// Registering your Custom Post Type
	register_post_type( 'books_collection', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action( 'init', 'custom_post_type', 0 );


/**
 * Create two taxonomies, genres and writers for the post type "book".
 *
 * @see register_post_type() for registering custom post types.
 */
function wpdocs_create_book_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Genres', 'textdomain' ),
		'all_items'         => __( 'All Genres', 'textdomain' ),
		'parent_item'       => __( 'Parent Genre', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
		'edit_item'         => __( 'Edit Genre', 'textdomain' ),
		'update_item'       => __( 'Update Genre', 'textdomain' ),
		'add_new_item'      => __( 'Add Genre', 'textdomain' ),
		'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
		'menu_name'         => __( 'Genres', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'genre' ),
	);

	register_taxonomy( 'lgbc-genre', array( 'books_collection' ), $args );

	unset( $args );
	unset( $labels );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Writers', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Writer', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Search Writers', 'textdomain' ),
		'popular_items'              => __( 'Popular Writers', 'textdomain' ),
		'all_items'                  => __( 'All Writers', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Writer', 'textdomain' ),
		'update_item'                => __( 'Update Writer', 'textdomain' ),
		'add_new_item'               => __( 'Add Writers', 'textdomain' ),
		'new_item_name'              => __( 'New Writer Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate writers with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove writers', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used writers', 'textdomain' ),
		'not_found'                  => __( 'No writers found.', 'textdomain' ),
		'menu_name'                  => __( 'Writers', 'textdomain' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'writer' ),
	);

	register_taxonomy( 'lgbc-writer', 'books_collection', $args );
}

// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'wpdocs_create_book_taxonomies', 0 );


//add settings link on plugin page
function plugin_add_settings_link( $links ) {

	$settings_link = '<a href="edit.php?post_type=books_collection&page=book_collection_settings">Einstellungen</a>';
	array_push( $links, $settings_link );

	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );


//Replace “Enter Title Here” Placeholder Text
function wpb_change_title_text( $title ){
	$screen = get_current_screen();

	if  ( 'books_collection' == $screen->post_type ) {
		$title = 'Add book title';
	}
	return $title;
}
add_filter( 'enter_title_here', 'wpb_change_title_text' );




