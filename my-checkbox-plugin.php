<?php
/*
Plugin Name: My Checkbox Plugin
Description: Adds checkboxes to Advanced Custom Fields
Version: 1.0
Author: Your Name
*/

function my_checkbox_enqueue_scripts() {
    global $post;
    wp_enqueue_script('my-checkbox-script', plugins_url('/my-checkbox-script.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('my-checkbox-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'post_id' => $post->ID));
}

function my_checkbox_shortcode($atts = [], $content = null) {
    ob_start();

    // Fetch post ID from shortcode attributes
    $atts = shortcode_atts(array('post_id' => '1'), $atts, 'my_checkboxes');
    $acf_post_id = $atts['post_id'];

    // Fetch ACF fields for specified post
    $fields = get_fields($acf_post_id);
    if (is_array($fields)) {
        echo '<div class="my-checkbox-wrapper">';
        foreach($fields as $name => $value) {
            global $post;
            $unique_key = $post->ID . "_" . $name;  // unique key per page
            $checked = (get_post_meta($post->ID, $unique_key, true) == 'checked') ? 'checked' : '';
            echo '<div class="my-checkbox-item"><input type="checkbox" class="my-checkbox" id="'.$name.'" '.$checked.'><span class="my-checkbox-text">'.$value.'</span></div>';
        }
        echo '</div>';
    }

    my_checkbox_enqueue_scripts();  // Call script enqueue function here

    return ob_get_clean();
}
add_shortcode('my_checkboxes', 'my_checkbox_shortcode');

function save_checkbox_state() {
    // Make sure to sanitize the POST input
    $checkbox_id = sanitize_text_field($_POST['checkbox_id']);
    $checkbox_state = sanitize_text_field($_POST['checkbox_state']);
    $post_id = sanitize_text_field($_POST['post_id']);

    // Save checkbox state to post meta with unique key per page
    $unique_key = $post_id . "_" . $checkbox_id;
    update_post_meta($post_id, $unique_key, $checkbox_state);

    wp_die(); // this is required to terminate immediately and return a proper response
}
add_action('wp_ajax_nopriv_save_checkbox_state', 'save_checkbox_state');
add_action('wp_ajax_save_checkbox_state', 'save_checkbox_state');

function display_all_checkboxes_state($atts = [], $content = null) {
    // Extract the attributes
    extract(shortcode_atts(array(
        'post_id' => '20'
    ), $atts));

    ob_start();

    // Fetch all posts and pages
    $args = array(
        'post_type' => array('post', 'page'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $posts = get_posts($args);

    // Fetch all fields in the specified group
    $fields = acf_get_fields('group_64ba5f2f1af84');

    // Get the ID of the current page
    global $post;
    $current_page_id = $post->ID;

    // Loop through each post/page
    foreach ($posts as $post) {
        // If the post id is not equal to the specified post id from the shortcode or the current page id, then display the information
        if($post->ID != $post_id && $post->ID != $current_page_id){
            echo '<h2><!--Post/Page Title: -->' . get_the_title($post->ID) . ' (ID: ' . $post->ID . ')</h2>';
            echo '<ul>';
            // Loop through each field
            foreach ($fields as $field) {
                $name = $field['name'];
                $label = $field['label'];
                $unique_key = $post->ID . "_" . $name;  // unique key per page
                $value = get_post_meta($post->ID, $unique_key, true);
                $meta_value = get_post_meta($post_id, $name, true);  // Get the meta_value from specified post id
                $checked = ($value == 'checked') ? '<span class="checked">済み</span>' : '<span class="na">未確認</span>';
                echo '<li>Field: ' . $meta_value . ', State: ' . $checked . '</li>';  // Display the meta_value
            }
            echo '</ul>';
        }
    }

    return ob_get_clean();
}
add_shortcode('display_all_checkboxes', 'display_all_checkboxes_state');




//css読み込み
function my_checkbox_enqueue_styles() {
    wp_enqueue_style( 'my-checkbox-style', plugins_url( 'my-checkbox-style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'my_checkbox_enqueue_styles' );
