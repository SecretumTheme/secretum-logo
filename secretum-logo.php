<?php
namespace SecretumLogo;

/**
 * Plugin Name: Secretum Logo Shortcode
 * Plugin URI: https://github.com/SecretumTheme/secretum-logo
 * Description: The Secretum Logo plugin provides a customizable shortcode for displaying the website logo managed in the WordPress customizer.
 * Version: 0.0.1
 * License: GNU GPLv3
 * Copyright (c) 2018 Secretum Theme
 * Author: Secretum Theme
 * Author URI: https://github.com/SecretumTheme
 * Text Domain: secretum-logo
 *
 * @package Secretum
 * @subpackage SecretumLogo
 */


// Constants
define('SECRETUM_LOGO_WP_MIN_VERSION', '3.8');
define('SECRETUM_LOGO_PLUGIN_FILE',    __FILE__);
define('SECRETUM_LOGO_PLUGIN_DIR',     dirname(SECRETUM_LOGO_PLUGIN_FILE));
define('SECRETUM_LOGO_PLUGIN_BASE',    plugin_basename(SECRETUM_LOGO_PLUGIN_FILE));


// Include Functions
require SECRETUM_LOGO_PLUGIN_DIR . '/functions.php';


// Activate Plugin
register_activation_hook(SECRETUM_LOGO_PLUGIN_FILE, '\SecretumLogo\Functions\activate');


/**
 * Add Shortcode: secretum_logo
 *
 * @example [secretum_logo]
 * @example [secretum_logo brand_class="" container_class="" custom_logo_class=""]
 * @example [secretum_logo brand_class="navbar-brand"]
 * @example [secretum_logo brand_class="site-title" container_class="site-branding" custom_logo_class="site-logo"]
 *
 * @link https://developer.wordpress.org/reference/functions/add_shortcode/
 */
add_shortcode(
    'secretum_logo',
    '\SecretumLogo\Functions\shortcode'
);


// Customizer Settings
add_action('customize_register', '\SecretumLogo\Functions\customize');


// Inject Links Into Plugin.php Admin
add_filter('plugin_row_meta', '\SecretumLogo\Functions\links', 10, 2);
