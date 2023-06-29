<?php
/*
Plugin Name: Custom Book Post Type
Plugin URI: https://www.example.com
Description: A plugin to register a custom post type for books.
Version: 1.0
Author: Your Name
Author URI: https://www.example.com
License: GPL2
*/

class Custom_Book_Post_Type
{
    public function __construct() {
        add_action('init', array($this, 'custom_book_post_type'));
        add_action('init', array($this, 'custom_book_genre_taxonomy'));
    }

    // Register the custom post type
    public function custom_book_post_type() {
        $labels = array(
            'name' => 'Books',
            'singular_name' => 'Book',
            'menu_name' => 'Books',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Book',
            'edit' => 'Edit',
            'edit_item' => 'Edit Book',
            'new_item' => 'New Book',
            'view' => 'View',
            'view_item' => 'View Book',
            'search_items' => 'Search Books',
            'not_found' => 'No books found',
            'not_found_in_trash' => 'No books found in trash',
            'parent' => 'Parent Book',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'books'),
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'taxonomies' => array('genre'),
        );

        register_post_type('book', $args);
    }

    // Register a custom taxonomy for genres
    public function custom_book_genre_taxonomy() {
        $labels = array(
            'name' => 'Genres',
            'singular_name' => 'Genre',
            'search_items' => 'Search Genres',
            'all_items' => 'All Genres',
            'parent_item' => 'Parent Genre',
            'parent_item_colon' => 'Parent Genre:',
            'edit_item' => 'Edit Genre',
            'update_item' => 'Update Genre',
            'add_new_item' => 'Add New Genre',
            'new_item_name' => 'New Genre Name',
            'menu_name' => 'Genres',
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'genre'),
        );

        register_taxonomy('genre', 'book', $args);
    }
}

new Custom_Book_Post_Type();