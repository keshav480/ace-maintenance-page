<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://https://wordpress.org/plugins/ace-maintenance-page
 * @since             1.0.0
 * @package           Ace_Maintenance_Page
 *
 * @wordpress-plugin
 * Plugin Name:       Ace Maintenance Page
 * Plugin URI:        https://https://ace-maintenance-page
 * Description:       Adds a splash page to your site to inform visitors that your site is temporarily down for maintenance. Ideal for a 'Coming Soon' or landing page.
 * Version:           1.0.0
 * Author:            Acewebx
 * Author URI:        https://https://https://wordpress.org/plugins/ace-maintenance-page/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ace-maintenance-page
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ACE_MAINTENANCE_PAGE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ace-maintenance-page-activator.php
 */
function activate_ace_maintenance_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ace-maintenance-page-activator.php';
	Ace_Maintenance_Page_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ace-maintenance-page-deactivator.php
 */
function deactivate_ace_maintenance_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ace-maintenance-page-deactivator.php';
	Ace_Maintenance_Page_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ace_maintenance_page' );
register_deactivation_hook( __FILE__, 'deactivate_ace_maintenance_page' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ace-maintenance-page.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ace_maintenance_page() {

	$plugin = new Ace_Maintenance_Page();
	$plugin->run();
}
run_ace_maintenance_page();

