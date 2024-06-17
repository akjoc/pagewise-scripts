<?php

function pws_enqueue_admin_styles() {
    wp_enqueue_style('pws-admin-css', plugins_url('admin-style.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'pws_enqueue_admin_styles');

// Adds a main menu page and several submenu pages for script management in the admin panel.
function pws_add_admin_menu() {
    // Main menu page
    add_menu_page('PageWise Scripts', 'PageWise Scripts', 'manage_options', 'pws_scripts_header', 'pws_scripts_header_page', 'dashicons-admin-generic');
    
    // Submenu for Header Scripts
    add_submenu_page('pws_scripts_header', 'Header Scripts', 'Header Scripts', 'manage_options', 'pws_scripts_header', 'pws_scripts_header_page');
    
    // Submenu for Footer Scripts
    add_submenu_page('pws_scripts_header', 'Footer Scripts', 'Footer Scripts', 'manage_options', 'pws_scripts_footer', 'pws_scripts_footer_page');
    
    // Submenu for Body Scripts
    add_submenu_page('pws_scripts_header', 'Body Scripts', 'Body Scripts', 'manage_options', 'pws_scripts_body', 'pws_scripts_body_page');
}

// Register the admin menu function to the 'admin_menu' action hook
add_action('admin_menu', 'pws_add_admin_menu');

// Functions to display the settings pages for header, footer, and body scripts.
function pws_scripts_header_page() {
    pws_scripts_form('Header');
}

function pws_scripts_footer_page() {
    pws_scripts_form('Footer');
}

function pws_scripts_body_page() {
    pws_scripts_form('Body');
}

// Displays the form for adding or editing scripts/styles. Allows specifying the placement.
//OLD code
// function pws_scripts_form($type) {
//     // Convert type to lowercase for option naming consistency
//     $type = strtolower($type);
//     $scripts_option_name = 'pws_' . $type . '_scripts';
    
//     // Retrieve scripts from the database
//     $scripts = get_option($scripts_option_name, []);
    
//     // Begin the form output
//     echo '<div class="wrap">';
//     echo '<h1>' . ucfirst($type) . ' Scripts</h1>';

//     // Loop through each script and provide an edit button
//     foreach ($scripts as $index => $script) {
//         // Apply stripslashes to display scripts without escaping backslashes
//         $script['code'] = stripslashes($script['code']);

//         echo '<div id="script-entry-' . $type . '-' . $index . '"><strong>' . esc_html($script['name']) . '</strong> ';
//         echo '<button class="button edit-script-button" data-index="' . $index . '" data-type="' . $type . '">Edit Script</button></div>';
//     }

//     // Button to add a new script
//     echo '<button id="add-script-button-' . $type . '" class="button button-primary">Add ' . ucfirst($type) . ' Script</button>';

//     // Form for adding or editing scripts/styles, initially hidden
//     echo '<div id="script-form-' . $type . '" style="display: none;">';
//     echo '<form id="script-form-' . $type . '-form" method="post" action="' . admin_url('admin-post.php') . '">';
//     echo '<input type="hidden" name="action" value="pws_save_script">';
//     echo '<input type="hidden" name="pws_script_type" value="' . $type . '">';
//     echo '<input type="hidden" name="pws_script_index" value="new">'; // Default 'new' for adding
//     echo wp_nonce_field('pws_save_script_action', 'pws_save_script_nonce', true, false);
//     // Fields for name, code, and placement with dynamic options based on script type
//     echo '<table class="form-table">';
//     echo '<tr valign="top"><th scope="row">Script/Style Name</th><td><input type="text" name="pws_script_name" value="" /></td></tr>';
//     echo '<tr valign="top"><th scope="row">Script/Style Code</th><td><textarea name="pws_script_code" rows="10" cols="50"></textarea></td></tr>';
//     echo '<tr valign="top"><th scope="row">Placement</th><td>';
//     echo '<select name="pws_script_placement" class="page-select" data-type="' . $type . '">';
//     echo '<option value="global">Global</option>';
//     echo '<option value="checkout_page">Checkout Page</option>';
//     echo '<option value="cart_page">Cart Page</option>';
//     echo '<option value="single_product_page">Single Product Page</option>';
//     echo '<option value="custom_page">Custom Page</option>';
//     echo '</select></td></tr>';
//     echo '<tr valign="top" class="custom-page-select" style="display:none;"><th scope="row">Select Page</th><td>';
//     wp_dropdown_pages(array('name' => 'pws_custom_page', 'id' => 'pws_custom_page-' . $type));
//     echo '</td></tr>';
//     echo '</table>';
//     echo submit_button('Save Script');
//     echo '</form></div></div>';
// }


//New Code 
// function pws_scripts_form($type) {
//     // Convert type to lowercase for option naming consistency
//     $type = strtolower($type);
//     $scripts_option_name = 'pws_' . $type . '_scripts';
    
//     // Retrieve scripts from the database
//     $scripts = get_option($scripts_option_name, []);
    
//     echo '<div class="wrap">';
//     echo '<h1>' . ucfirst($type) . ' Scripts</h1>';

//     foreach ($scripts as $index => $script) {
//         // Apply stripslashes to display scripts without escaping backslashes
//         $script['code'] = stripslashes($script['code']);

//         echo '<div id="script-entry-' . $type . '-' . $index . '">';
//         echo '<strong>' . esc_html($script['name']) . '</strong> ';
//         echo '<button class="button edit-script-button" data-index="' . $index . '" data-type="' . $type . '">Edit Script</button>';
//         echo '</div>';
//     }

//     // Button to add a new script
//     echo '<button id="add-script-button-' . $type . '" class="button button-primary">Add ' . ucfirst($type) . ' Script</button>';

//     // Form for adding or editing scripts/styles
//     echo '<div id="script-form-' . $type . '" class="script-form" style="display: none;">';
//     echo '<form method="post" action="' . admin_url('admin-post.php') . '">';
//     echo '<input type="hidden" name="action" value="pws_save_script">';
//     echo '<input type="hidden" name="pws_script_type" value="' . $type . '">';
//     echo '<input type="hidden" name="pws_script_index" value="new">';  // Default 'new' for adding
//     echo wp_nonce_field('pws_save_script_action', 'pws_save_script_nonce', true, false);
    
//     echo '<table class="form-table">';
//     echo '<tr valign="top"><th scope="row">Script/Style Name</th><td><input type="text" name="pws_script_name" value="" /></td></tr>';
//     echo '<tr valign="top"><th scope="row">Script/Style Code</th><td><textarea name="pws_script_code" rows="10" cols="50"></textarea></td></tr>';
//     echo '<tr valign="top"><th scope="row">Placement</th><td>';
//     echo '<select name="pws_script_placement" class="page-select" data-type="' . $type . '">';
//     echo '<option value="global">Global</option>';
//     echo '<option value="checkout_page">Checkout Page</option>';
//     echo '<option value="cart_page">Cart Page</option>';
//     echo '<option value="single_product_page">Single Product Page</option>';
//     echo '<option value="product_category">Product Category</option>';
//     echo '<option value="order_success">Order Success Page</option>';
//     echo '<option value="view_order">View Order Page</option>';
//     echo '<option value="custom_page">Custom Page</option>';
//     echo '</select></td></tr>';
//     echo '<tr valign="top" class="custom-page-select" style="display:none;"><th scope="row">Select Page</th><td>';
//     wp_dropdown_pages(array(
//         'name' => 'pws_custom_page_id', // Ensure this matches exactly what the PHP handler expects
//         'id' => 'pws_custom_page-' . $type,
//         'selected' => (isset($script['custom_page_id']) ? $script['custom_page_id'] : 0), // This assumes you're editing and $script['custom_page_id'] is available
//         'echo' => 1
//     ));
//     echo '</td></tr>';
//     echo '</table>';
//     echo submit_button('Save Script');
//     echo '</form></div></div>';
// }




//Newest code - this is working
function pws_scripts_form($type) {
    $type = strtolower($type);
    $scripts_option_name = 'pws_' . $type . '_scripts';
    $scripts = get_option($scripts_option_name, []);
    
    echo '<div class="wrap pws-wrap">';
    echo '<h1 class="pws-header">' . ucfirst($type) . ' Scripts</h1>';

    foreach ($scripts as $index => $script) {
        $script['code'] = stripslashes($script['code']);
        echo '<div id="script-entry-' . $type . '-' . $index . '" class="pws-script-entry row">';
        echo '<div class="pws-script-section"> <strong class="pws-script-name">' . esc_html($script['name']) . '</strong> </div>';
        echo '<div class="pws-script-section"> <button class="edit-script-button pws-edit-button" data-index="' . $index . '" data-type="' . $type . '">Edit Script</button> </div>';
        echo '<button class="delete-script-button pws-delete-button" data-index="' . $index . '" data-type="' . $type . '">Delete</button>';
        echo '</div>';
    }

    echo '<button id="add-script-button-' . $type . '" class="pws-add-button">Add ' . ucfirst($type) . ' Script</button>';

    echo '<div id="script-form-' . $type . '" class="script-form pws-script-form" style="display: none;">';
    echo '<form method="post" action="' . admin_url('admin-post.php') . '" class="pws-form">';
    echo '<input type="hidden" name="action" value="pws_save_script">';
    echo '<input type="hidden" name="pws_script_type" value="' . $type . '">';
    echo '<input type="hidden" name="pws_script_index" value="new">';
    echo wp_nonce_field('pws_save_script_action', 'pws_save_script_nonce', true, false);
    
    echo '<table class="form-table pws-form-table">';
    echo '<tr valign="top"><th scope="row">Script/Style Name</th><td><input type="text" name="pws_script_name" value="" class="pws-input pws-input-name" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Script/Style Code</th><td><textarea name="pws_script_code" rows="10" cols="50" class="pws-textarea"></textarea></td></tr>';
    echo '<tr valign="top"><th scope="row">Placement</th><td><select name="pws_script_placement" class="page-select pws-select" data-type="' . $type . '">';
    echo '<option value="global">Global</option>';
    echo '<option value="checkout_page">Checkout Page</option>';
    echo '<option value="cart_page">Cart Page</option>';
    echo '<option value="single_product_page">Single Product Page</option>';
    echo '<option value="product_category">Product Category</option>';
    echo '<option value="order_success">Order Success Page</option>';
    echo '<option value="view_order">View Order Page</option>';
    echo '<option value="custom_page">Custom Page</option>';
    echo '</select></td></tr>';
    echo '<tr valign="top" class="custom-page-select pws-custom-page" style="display:none;"><th scope="row">Select Page</th><td>';
    wp_dropdown_pages([
        'name' => 'pws_custom_page_id',
        'id' => 'pws_custom_page-' . $type,
        'selected' => (isset($script['custom_page_id']) ? $script['custom_page_id'] : 0),
        'echo' => 1,
        'class' => 'pws-page-dropdown'
    ]);
    echo '</td></tr>';
    echo '</table>';
    echo submit_button('Save Script', 'primary', 'submit', true, ['class' => 'pws-submit-button']);
    echo '</form></div></div>';
}







