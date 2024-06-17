<?php
/*
Plugin Name: PageWise Scripts
Description: Add custom scripts and styles to specific pages of your WordPress site.
Version: 1.0
Author: Stegback
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue admin CSS
function pws_admin_styles() {
    wp_enqueue_style('pws-admin-style', plugin_dir_url(__FILE__) . 'css/admin-style.css');
}

add_action('admin_enqueue_scripts', 'pws_admin_styles');

// Enqueue admin JS
function pws_enqueue_admin_scripts() {
    wp_enqueue_script('pws-admin-script', plugin_dir_url(__FILE__) . 'admin/admin-scripts.js', array('jquery'), null, true);
    wp_localize_script('pws-admin-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('admin_enqueue_scripts', 'pws_enqueue_admin_scripts');


// Include admin pages and functions
require_once plugin_dir_path(__FILE__) . 'admin/admin-pages.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';
