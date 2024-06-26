<?php
/*
Plugin Name: Fakebook Reactions
Description: Adds a reaction bar to the bottom of every post.
Version: 1.0
Author: Atif
*/

// Enqueue necessary scripts and styles
function fakebook_reactions_enqueue_scripts() {
    wp_enqueue_style('fakebook-reactions-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('fakebook-reactions-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '1.0', true);
    wp_localize_script('fakebook-reactions-script', 'fakebook_reactions', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'fakebook_reactions_enqueue_scripts');

// Add the reaction bar to the content
function fakebook_reactions_add_bar($content) {
    if (is_single()) {
        $content .= '<div class="fakebook-reactions-bar">
                        <button class="reaction-btn" data-reaction="like">👍 Like</button>
                        <button class="reaction-btn" data-reaction="love">❤️ Love</button>
                        <button class="reaction-btn" data-reaction="haha">😂 Haha</button>
                        <button class="reaction-btn" data-reaction="wow">😮 Wow</button>
                        <button class="reaction-btn" data-reaction="sad">😢 Sad</button>
                        <button class="reaction-btn" data-reaction="angry">😡 Angry</button>
                    </div>';
    }
    return $content;
}
add_filter('the_content', 'fakebook_reactions_add_bar');

// Handle AJAX request to save reactions
function fakebook_reactions_handle_ajax() {
    $post_id = intval($_POST['post_id']);
    $reaction = sanitize_text_field($_POST['reaction']);
    $reactions = get_post_meta($post_id, '_fakebook_reactions', true);
    if (!is_array($reactions)) {
        $reactions = array();
    }
    if (!isset($reactions[$reaction])) {
        $reactions[$reaction] = 0;
    }
    $reactions[$reaction]++;
    update_post_meta($post_id, '_fakebook_reactions', $reactions);
    wp_send_json_success($reactions);
}
add_action('wp_ajax_fakebook_reactions', 'fakebook_reactions_handle_ajax');
add_action('wp_ajax_nopriv_fakebook_reactions', 'fakebook_reactions_handle_ajax');
