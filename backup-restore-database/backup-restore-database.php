<?php
/*
Plugin Name: Database Backup and Restore
Plugin URI: https://your-website.com/
Description: This plugin allows you to backup and restore the WordPress database.
Version: 1.0
Author: Your Name
Author URI: https://your-website.com/
License: GPL2
*/

class Database_Backup_Restore_Plugin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu() {
        add_submenu_page(
            'tools.php',
            'Database Backup and Restore',
            'Database Backup and Restore',
            'manage_options',
            'db-backup-restore',
            array($this, 'display_plugin_page')
        );
    }

    public function display_plugin_page() {
        if (isset($_POST['backup'])) {
            $this->backup_database();
        } elseif (isset($_POST['restore']) && isset($_FILES['backup_file'])) {
            $this->restore_database($_FILES['backup_file']);
        }

        // Display the backup and restore form
        ?>
        <div class="wrap">
            <h1>Database Backup and Restore</h1>

            <h2>Backup</h2>
            <form method="post">
                <p>Click the button below to create a backup of the WordPress database.</p>
                <p><input type="submit" name="backup" class="button button-primary" value="Backup Database"></p>
            </form>

            <h2>Restore</h2>
            <form method="post" enctype="multipart/form-data">
                <p>Select a backup file to restore the WordPress database.</p>
                <p><input type="file" name="backup_file"></p>
                <p><input type="submit" name="restore" class="button button-primary" value="Restore Database"></p>
            </form>
        </div>
        <?php
    }

    public function backup_database() {
        global $wpdb;

        // Generate a unique filename for the backup
        $backup_file = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Get the database credentials from WordPress configuration
        $db_host = DB_HOST;
        $db_name = DB_NAME;
        $db_user = DB_USER;
        $db_password = DB_PASSWORD;

        // Create the command to execute mysqldump
        $command = sprintf('mysqldump --opt -h %s -u %s -p%s %s > %s',
            escapeshellarg($db_host),
            escapeshellarg($db_user),
            escapeshellarg($db_password),
            escapeshellarg($db_name),
            escapeshellarg(WP_CONTENT_DIR . '/' . $backup_file)
        );

        // Execute the command
        exec($command);

        // Provide a download link for the backup file
        $backup_url = content_url($backup_file);
        echo '<p>Backup created successfully. <a href="' . $backup_url . '">Download backup file</a></p>';
    }

    public function restore_database($backup_file) {
        global $wpdb;

        // Verify the uploaded file
        if ($backup_file['error'] !== UPLOAD_ERR_OK) {
            echo '<p>Error uploading the backup file. Please try again.</p>';
            return;
        }

        // Move the uploaded file to a temporary location
        $temp_file = $backup_file['tmp_name'];

        // Get the database credentials from WordPress configuration
        $db_host = DB_HOST;
        $db_name = DB_NAME;
        $db_user = DB_USER;
        $db_password = DB_PASSWORD;

        // Create the command to execute mysql
        $command = sprintf('mysql -h %s -u %s -p%s %s < %s',
            escapeshellarg($db_host),
            escapeshellarg($db_user),
            escapeshellarg($db_password),
            escapeshellarg($db_name),
            escapeshellarg($temp_file)
        );

        // Execute the command
        exec($command);

        echo '<p>Database restored successfully.</p>';
    }
}

new Database_Backup_Restore_Plugin();