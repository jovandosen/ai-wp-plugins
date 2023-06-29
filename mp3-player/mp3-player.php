<?php
/*
Plugin Name: Simple MP3 Player
Plugin URI: https://www.example.com
Description: A simple MP3 player for WordPress.
Version: 1.0
Author: Your Name
Author URI: https://www.example.com
*/

function simple_mp3_player_shortcode($atts) {
    // Shortcode callback function
    ob_start();
    ?>
    <audio controls>
        <source src="<?php echo esc_url($atts['src']); ?>" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <?php
    return ob_get_clean();
}
add_shortcode('mp3_player', 'simple_mp3_player_shortcode');

// [mp3_player src="http://example.com/path/to/your-audio-file.mp3"]
// [mp3_player src="http://wp2023.project/wp-content/plugins/mp3-player/hard-core-henry.mp3"]