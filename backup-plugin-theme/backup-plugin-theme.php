<?php
/**
 * Plugin Name: Backup Plugin and Theme (OOP)
 * Description: Allows you to create backups of your plugins and themes.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com/
 */

// Backup Plugin and Theme class
class Backup_Plugin_Theme 
{
    private $backup_dir;
    private $plugins_dir;
    private $themes_dir;

    public function __construct() {
        $this->backup_dir = WP_CONTENT_DIR . '/bpt-backups/';
        $this->plugins_dir = WP_CONTENT_DIR . '/plugins/';
        $this->themes_dir = WP_CONTENT_DIR . '/themes/';

        add_action('admin_menu', array($this, 'add_menu_item'));
    }

    // Register the plugin's menu item in the admin dashboard
    public function add_menu_item() {
        add_menu_page(
            'Backup Plugin and Theme',
            'Backup Plugin and Theme',
            'manage_options',
            'bpt-backup',
            array($this, 'backup_page'),
            'dashicons-backup',
            85
        );
    }

    // Render the backup page
    public function backup_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check if the backup button is clicked
        if (isset($_POST['bpt_backup'])) {
            $this->create_backup();
        }

        // Display the backup page content
        echo '<div class="wrap">';
        echo '<h1>Backup Plugin and Theme</h1>';
        echo '<p>Click the "Backup Now" button to create a backup of your plugins and themes.</p>';
        echo '<form method="post">';
        echo '<p><input type="submit" name="bpt_backup" class="button button-primary" value="Backup Now"></p>';
        echo '</form>';
        echo '</div>';
    }

    // Create a backup of plugins and themes
    private function create_backup() {
        // Create the backup directory if it doesn't exist
        if (!file_exists($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
        }

        // Zip the plugins directory
        $zip_plugins = new ZipArchive();
        $plugins_backup_file = $this->backup_dir . 'plugins_backup_' . date('Y-m-d_H-i-s') . '.zip';

        if ($zip_plugins->open($plugins_backup_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->plugins_dir));

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $local_path = str_replace($this->plugins_dir, '', $file);
                    $zip_plugins->addFile($file, $local_path);
                }
            }

            $zip_plugins->close();
            echo '<div class="notice notice-success"><p>Plugins backup created successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Failed to create plugins backup.</p></div>';
        }

        // Zip the themes directory
        $zip_themes = new ZipArchive();
        $themes_backup_file = $this->backup_dir . 'themes_backup_' . date('Y-m-d_H-i-s') . '.zip';

        if ($zip_themes->open($themes_backup_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->themes_dir));

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $local_path = str_replace($this->themes_dir, '', $file);
                    $zip_themes->addFile($file, $local_path);
                }
            }

            $zip_themes->close();
            echo '<div class="notice notice-success"><p>Themes backup created successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Failed to create themes backup.</p></div>';
        }
    }
}

// Initialize the plugin
function initialize_backup_plugin_theme() {
    new Backup_Plugin_Theme();
}
add_action('plugins_loaded', 'initialize_backup_plugin_theme');
