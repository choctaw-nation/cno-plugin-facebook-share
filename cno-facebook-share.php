<?php
/**
 * Plugin Name: CNO Facebook Share
 * Plugin URI: https://github.com/choctaw-nation/facebook-share
 * Description: A simple app that allows devs to share a URL to Facebook
 * Version: 1.0.1
 * Author: Choctaw Nation of Oklahoma
 * Author URI: https://www.choctawnation.com
 * Text Domain: cno
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 8.2
 * Requires at least: 6.6.0
 * Tested up to: 6.6.2
 *
 * @package ChoctawNation
 * @subpackage FacebookShare
 */

use ChoctawNation\FacebookShare\Plugin_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once __DIR__ . '/inc/class-plugin-loader.php';
$plugin_loader = new Plugin_Loader();

register_activation_hook( __FILE__, array( $plugin_loader, 'activate' ) );
register_deactivation_hook( __FILE__, array( $plugin_loader, 'deactivate' ) );