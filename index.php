<?php

/*
Plugin Name: Book Collection
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Entwicklung eines Plugins mit Nutzerverwaltung, Bücher anlegen/verwalten (Buchtitel, Coverbild, Buch in ca. 6 Sprachen), Impressum upload

Userflow im CMS: User kann sich anmelden, ein Buch anlegen (Titel eingeben, Cover hochladen, 5-6 PDFs in verschiedenen Sprachen hochladen) und speichern. Er sollte auch ein Buch löschen und bearbeiten können (anderer Titel, PDF austauchen, Cover austauschen).

Das dazugehörige Frontend, eine Webapplikation soll dann die Bücher in einer Art Galerie anzeigen und zum Lesen bereitstellen und ist nicht GEgenstand des Auftrags.

Da der Endkunde Wordpess bei sich im Haus installiert hat, würde sich das anbieten. Wir denken uns, dass man dafür vielleicht ein Plugin schreiben kann und sind hier für Ideen/Anmerkungen offen. Ich bitte um Angebote zur preislichen Einschätzung.
Version: 0.1
Author: Lars
Author URI: https://larsgoerlitzer.de
License: A "Slug" license name e.g. GPL2
*/


//https://wordpress.org/plugins/wp-books-gallery/


defined( 'ABSPATH' ) or die( 'NO!' );
///Throw out if unallowed access

add_action("wp_enqueue_scripts" ,"add_scripts");

function add_scripts(){
    wp_enqueue_style("add_style", plugins_url("style.css" , __FILE__));
    wp_enqueue_script("add_script" , plugins_url("script.js", __FILE__));

}

/*
* Creating a function to create our CPT
*/

function custom_post_type() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Bücher', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Buch', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Bücher', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Movie', 'twentytwenty' ),
        'all_items'           => __( 'Alle Bücher', 'twentytwenty' ),
        'view_item'           => __( 'Buch anzeigen', 'twentytwenty' ),
        'add_new_item'        => __( 'Buch hinzufügen', 'twentytwenty' ),
        'add_new'             => __( 'Hinzufügen', 'twentytwenty' ),
        'edit_item'           => __( 'Buch bearbeiten', 'twentytwenty' ),
        'update_item'         => __( 'Update Movie', 'twentytwenty' ),
        'search_items'        => __( 'Search Movie', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );

// Set other options for Custom Post Type

    $args = array(
        'label'               => __( 'books', 'twentytwenty' ),
        'description'         => __( 'Büchersammulung', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'menu_icon' => 'dashicons-book-alt',
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical'        => false,
        'public'              => true,
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
        'show_in_rest' => false,

    );

    // Registering your Custom Post Type
    register_post_type( 'books', $args );

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

    register_taxonomy( 'genre', array( 'books' ), $args );

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

    register_taxonomy( 'writer', 'books', $args );
}
// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'wpdocs_create_book_taxonomies', 0 );


function admin_menu(){
    add_menu_page("Bucheinstellung" , "Bucheinstellung" , "manage_options" , "buch_menu" , "buch_admin_site" , "dashicons-book-alt" , "50");
}
function buch_admin_site(){
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Buch Collection Einstellung</h1>
        <div>
            stuff
        </div>
    </div>


    <?php
}

add_action("admin_menu" , "admin_menu");