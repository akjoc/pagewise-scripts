<?php

//Old Code
// function pws_enqueue_custom_content() {
//     // Retrieve content settings from options
//     $header_items = get_option('pws_header_scripts', []);
//     $body_items = get_option('pws_body_scripts', []);
//     $footer_items = get_option('pws_footer_scripts', []);

//     // Function to inject content based on placement
//     $inject_content = function($items, $context) {
//         foreach ($items as $item) {
//             if (
//                 ($item['placement'] === 'global') ||
//                 ($item['placement'] === 'checkout_page' && is_checkout()) ||
//                 ($item['placement'] === 'cart_page' && is_cart()) ||
//                 ($item['placement'] === 'single_product_page' && is_product()) ||
//                 ($item['placement'] === 'custom_page' && is_page($item['custom_page']))
//             ) {
//                 // Unescape the code to handle any stored escapes and output it
//                 echo stripslashes($item['code']) . "\n";
//             }
//         }
//     };

//     // Hook functions to appropriate WordPress actions
//     add_action('wp_head', function() use ($header_items, $inject_content) { $inject_content($header_items, 'header'); });
//     add_action('wp_body_open', function() use ($body_items, $inject_content) { $inject_content($body_items, 'body'); });
//     add_action('wp_footer', function() use ($footer_items, $inject_content) { $inject_content($footer_items, 'footer'); });
// }

// add_action('init', 'pws_enqueue_custom_content');


function pws_enqueue_custom_content() {
    // Retrieve content settings from options
    $header_items = get_option('pws_header_scripts', []);
    $body_items = get_option('pws_body_scripts', []);
    $footer_items = get_option('pws_footer_scripts', []);

    // Function to inject content based on placement
    $inject_content = function($items, $context) {
        foreach ($items as $item) {
            if (
                ($item['placement'] === 'global') ||
                ($item['placement'] === 'checkout_page' && is_checkout()) ||
                ($item['placement'] === 'cart_page' && is_cart()) ||
                ($item['placement'] === 'single_product_page' && is_product()) ||
                ($item['placement'] === 'custom_page' && !empty($item['custom_page_id']) && is_page($item['custom_page_id'])) ||
                ($item['placement'] === 'product_category' && is_product_category()) ||  // For product category pages
                ($item['placement'] === 'order_success' && is_wc_endpoint_url('order-received')) ||  // For order success page
                ($item['placement'] === 'view_order' && is_wc_endpoint_url('view-order'))  // For viewing single order details
            ) {
                // Unescape the code to handle any stored escapes and output it
                echo stripslashes($item['code']) . "\n";
            }
        }
    };

    // Hook functions to appropriate WordPress actions
    add_action('wp_head', function() use ($header_items, $inject_content) { $inject_content($header_items, 'header'); });
    add_action('wp_body_open', function() use ($body_items, $inject_content) { $inject_content($body_items, 'body'); });
    add_action('wp_footer', function() use ($footer_items, $inject_content) { $inject_content($footer_items, 'footer'); });
}

add_action('init', 'pws_enqueue_custom_content');
