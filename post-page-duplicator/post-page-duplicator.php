<?php
/*
Plugin Name: Post/Page Duplicator (OOP)
Plugin URI: https://www.example.com
Description: A plugin to duplicate posts and pages in WordPress using OOP approach.
Version: 1.0
Author: Your Name
Author URI: https://www.example.com
License: GPL2
*/

class Post_Page_Duplicator 
{
    public function __construct() {
        add_filter('post_row_actions', array($this, 'duplicate_post_page_link'), 10, 2);
        add_filter('page_row_actions', array($this, 'duplicate_post_page_link'), 10, 2);
        add_action('admin_init', array($this, 'duplicate_post_page_action'));
    }

    // Add duplicate link to post/page actions
    public function duplicate_post_page_link($actions, $post) {
        if (current_user_can('edit_posts') && $post->post_type != 'attachment') {
            $actions['duplicate'] = '<a href="' . admin_url('admin.php?action=duplicate_post_page&post=' . $post->ID) . '">Duplicate</a>';
        }
        return $actions;
    }

    // Duplicate post/page action
    public function duplicate_post_page_action() {
        if (!isset($_GET['post']) || !isset($_GET['action']) || $_GET['action'] != 'duplicate_post_page') {
            return;
        }

        $post_id = $_GET['post'];
        $post = get_post($post_id);
        $new_post_author = $post->post_author;
        $new_post_data = array(
            'post_title' => $post->post_title . ' (Copy)',
            'post_content' => $post->post_content,
            'post_status' => 'draft',
            'post_type' => $post->post_type,
            'post_author' => $new_post_author,
        );
        $new_post_id = wp_insert_post($new_post_data);

        if ($new_post_id) {
            // Duplicate post meta
            $post_meta = get_post_meta($post_id);
            foreach ($post_meta as $meta_key => $meta_values) {
                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }

            // Redirect to the new post/page
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit();
        } else {
            wp_die('Error duplicating post/page.');
        }
    }
}

// Initialize the plugin
new Post_Page_Duplicator();