<?php

/*
Plugin Name: Books Collection
Plugin URI: https://larsgoerlitzer.de
Description: Bücher-Sammlung
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
defined( 'ABSPATH' ) or die( 'NO!' );
///Throw out if unallowed access


include( plugin_dir_path( __FILE__ ) . 'frontend/book-query.php' );
include( plugin_dir_path( __FILE__ ) . 'backend/admin-settings-page.php' );
include( plugin_dir_path( __FILE__ ) . 'backend/edit_post.php' );


//load frontend scripts
add_action( "wp_enqueue_scripts", "add_scripts" );

function add_scripts() {
	wp_enqueue_style( "lgbc_style", plugins_url( "css/lgbc_style.css", __FILE__ ), "", null );
	wp_enqueue_script( "lgbc_script", plugins_url( "js/lgbc_script.js", __FILE__ ), "", null );

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
		'name'               => _x( 'Bücher', 'Post Type General Name', 'twentytwenty' ),
		'singular_name'      => _x( 'Buch', 'Post Type Singular Name', 'twentytwenty' ),
		'menu_name'          => __( 'Bücher', 'twentytwenty' ),
		'parent_item_colon'  => __( 'Parent Movie', 'twentytwenty' ),
		'all_items'          => __( 'Alle Bücher', 'twentytwenty' ),
		'view_item'          => __( 'Buch anzeigen', 'twentytwenty' ),
		'add_new_item'       => __( 'Buch hinzufügen', 'twentytwenty' ),
		'add_new'            => __( 'Hinzufügen', 'twentytwenty' ),
		'edit_item'          => __( 'Buch bearbeiten', 'twentytwenty' ),
		'featured_image'     => __( 'Buchcover', 'textdomain' ),    //used in post.php
		'set_featured_image' => __( 'Buchcover hinzufügen', 'textdomain' ),    //used in post.php
		'update_item'        => __( 'Update Movie', 'twentytwenty' ),
		'search_items'       => __( 'Search Movie', 'twentytwenty' ),
		'not_found'          => __( 'Not Found', 'twentytwenty' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'twentytwenty' ),
	);

// Set other options for Custom Post Type

	$args = array(
		'label'               => __( 'books-collection', 'twentytwenty' ),
		'description'         => __( 'Büchersammulung', 'twentytwenty' ),
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
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => false,

	);

	// Registering your Custom Post Type
	register_post_type( 'books-collection', $args );

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
		'add_new_item'      => __( 'Genre hinzufügen', 'textdomain' ),
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

	register_taxonomy( 'genre', array( 'books-collection' ), $args );

	unset( $args );
	unset( $labels );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Authoren', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Author', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Author suchen', 'textdomain' ),
		'popular_items'              => __( 'Popular Writers', 'textdomain' ),
		'all_items'                  => __( 'Alle Authoren', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Writer', 'textdomain' ),
		'update_item'                => __( 'Update Writer', 'textdomain' ),
		'add_new_item'               => __( 'Author hinzufügen', 'textdomain' ),
		'new_item_name'              => __( 'New Writer Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate writers with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove writers', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used writers', 'textdomain' ),
		'not_found'                  => __( 'No writers found.', 'textdomain' ),
		'menu_name'                  => __( 'Authoren', 'textdomain' ),
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

	register_taxonomy( 'writer', 'books-collection', $args );
}

// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'wpdocs_create_book_taxonomies', 0 );


//add settings link on plugin page
function plugin_add_settings_link( $links ) {

	$settings_link = '<a href="edit.php?post_type=books-collection&page=book_collection_settings">Einstellungen</a>';
	array_push( $links, $settings_link );

	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );


