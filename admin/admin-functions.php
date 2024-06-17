<?php

// $scripts = get_option('pws_header_scripts');

// if ($scripts === false) {
//     echo "The option 'pws_header_scripts' does not exist or has no value.";
// } else {
//     echo "Hi\n";
//     print_r($scripts); // This will print the structure of $scripts
//     echo "\n";
//     echo "Serialized data: " . serialize($scripts); // Optionally, view it as a serialized string
// }

// die(); // Ensure this is the end of the script execution


// Register settings to store the configurations for each type of script
function pws_register_script_options() {
    $types = ['header', 'footer', 'body']; // Define script types
    foreach ($types as $type) {
        register_setting('pws_scripts_group', 'pws_' . $type . '_scripts');
    }
}
add_action('admin_init', 'pws_register_script_options');

//Code to minify
// function pws_minify_code($code) {
//     $search = array(
//         '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
//         '/[^\S ]+\</s',  // strip whitespaces before tags, except space
//         '/(\s)+/s',       // shorten multiple whitespace sequences
//         "/\r|\n/"        // Remove newlines
//     );
//     $replace = array(
//         '>',
//         '<',
//         '\\1',
//         ''
//     );
//     $code = preg_replace($search, $replace, $code);

//     return $code;
// }

// Handle both AJAX and regular POST requests for form submissions
function pws_handle_form_submission() {
    $is_ajax = defined('DOING_AJAX') && DOING_AJAX; // Check if it's an AJAX request

    // Security check for nonce to prevent CSRF attacks
    if (!isset($_POST['pws_save_script_nonce']) || !wp_verify_nonce($_POST['pws_save_script_nonce'], 'pws_save_script_action')) {
        $error_message = 'Security check failed';
        pws_handle_error($error_message, $is_ajax);
    }

    // Permission check to ensure only authorized users can modify settings
    if (!current_user_can('manage_options')) {
        $error_message = 'No permission';
        pws_handle_error($error_message, $is_ajax);
    }

    // Check if script type is provided and handle the data accordingly
    if (isset($_POST['pws_script_type'])) {
        $type = sanitize_text_field($_POST['pws_script_type']);
        $scripts_option_name = 'pws_' . $type . '_scripts';
        $scripts = get_option($scripts_option_name, []); // Ensure default to array
    
        if (!is_array($scripts)) { // Ensure $scripts is always an array
            $scripts = [];
        }
    
        // Define allowed HTML tags and attributes for script and style elements
        $allowed_html = [
            'script' => ['type' => [], 'src' => [], 'async' => [], 'defer' => []],
            'style' => ['type' => []]
        ];
    
        // Prepare and sanitize script data
        $script_data = [
            'name' => sanitize_text_field($_POST['pws_script_name']),
            'code' => wp_kses($_POST['pws_script_code'], $allowed_html),
            'placement' => sanitize_text_field($_POST['pws_script_placement']),
            'custom_page_id' => ($_POST['pws_script_placement'] === 'custom_page' && isset($_POST['pws_custom_page_id'])) ? intval($_POST['pws_custom_page_id']) : null // Only capture custom page ID if 'custom_page' is selected
        ];
    
        // Determine if updating an existing script or adding a new one
        $index = isset($_POST['pws_script_index']) && $_POST['pws_script_index'] !== 'new' ? intval($_POST['pws_script_index']) : false;
        if ($index !== false && isset($scripts[$index])) {
            $scripts[$index] = $script_data; // Update existing script
        } else {
            $scripts[] = $script_data; // Add new script
        }
    
        // Save updated scripts back to the option
        update_option($scripts_option_name, $scripts);
    
        pws_finalize_submission($is_ajax);
    } else {
        $error_message = 'Required data is missing';
        pws_handle_error($error_message, $is_ajax);
    }
    
}

// Centralize error handling
function pws_handle_error($message, $is_ajax) {
    if ($is_ajax) {
        wp_send_json_error(['message' => $message]);
    } else {
        wp_die($message);
    }
}

// Centralize successful submission handling
function pws_finalize_submission($is_ajax) {
    if ($is_ajax) {
        wp_send_json_success(['message' => 'Script saved successfully']);
    } else {
        wp_redirect(add_query_arg('settings-updated', 'true', wp_get_referer()));
        exit;
    }
}

// Function to fetch script data for editing via AJAX, ensuring clean display
function pws_fetch_script_data() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'No permission']);
    }

    $type = sanitize_text_field($_POST['script_type']);
    $index = intval($_POST['script_index']);
    $scripts_option_name = 'pws_' . $type . '_scripts';
    $scripts = get_option($scripts_option_name, []);

    if (isset($scripts[$index])) {
        // Apply stripslashes to ensure no escaping characters interfere with display
        $scripts[$index]['code'] = stripslashes($scripts[$index]['code']);
        wp_send_json_success($scripts[$index]);
    } else {
        wp_send_json_error(['message' => 'Script not found']);
    }
}
add_action('wp_ajax_fetch_pws_script_data', 'pws_fetch_script_data');
add_action('wp_ajax_pws_save_script', 'pws_handle_form_submission');
add_action('admin_post_pws_save_script', 'pws_handle_form_submission');


//Delete Script
add_action('wp_ajax_delete_pws_script_data', 'pws_delete_script_data');
function pws_delete_script_data() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'No permission']);
    }

    $type = sanitize_text_field($_POST['script_type']);
    $index = intval($_POST['script_index']);
    $scripts_option_name = 'pws_' . $type . '_scripts';
    $scripts = get_option($scripts_option_name, []);

    // Check if the script exists and remove it
    if (isset($scripts[$index])) {
        array_splice($scripts, $index, 1); // Remove the script at the specified index
        update_option($scripts_option_name, $scripts); // Update the option with the modified array
        wp_send_json_success(['message' => 'Script deleted']);
    } else {
        wp_send_json_error(['message' => 'Script not found']);
    }
}
