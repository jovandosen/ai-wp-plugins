<?php
/*
Plugin Name: Extend Classic Editor
Plugin URI: https://your-website.com/
Description: This plugin extends classic editor
Version: 1.0
Author: Your Name
Author URI: https://your-website.com/
License: GPL2
*/

function custom_classic_editor_extension() {
    // Check if Classic Editor is active
    if (function_exists('wp_enqueue_editor')) {
        add_action('admin_enqueue_scripts', 'custom_classic_editor_enqueue_assets');
        add_action('admin_footer', 'custom_classic_editor_add_button');
    }
}
add_action('init', 'custom_classic_editor_extension');

function custom_classic_editor_enqueue_assets() {
    wp_enqueue_script('custom-classic-editor-script', plugin_dir_url(__FILE__) . 'custom-classic-editor.js', array('jquery'), '1.0', true);
    wp_enqueue_style('custom-classic-editor-style', plugin_dir_url(__FILE__) . 'custom-classic-editor.css');
}

function custom_classic_editor_add_button() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // QTags.addButton('custom_shortcode', 'Custom Shortcode', '[custom_shortcode]', '', '', 'Insert Custom Shortcode');
            QTags.addButton('custom_shortcode', 'Custom Shortcode', '[mp3_player src="http://wp2023.project/wp-content/plugins/mp3-player/hard-core-henry.mp3"]', '', '', 'Insert Custom Shortcode');
        });
    </script>
    <?php
}