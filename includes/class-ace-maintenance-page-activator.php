<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/ace-maintenance-page
 * @since      1.0.0
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/includes
 * @author     Acewebx <developer@acewebx.com>
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Ace_Maintenance_Page_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$default_opts = [
			'enabled'          => 0,
			'title'            => 'Maintenance Mode',
			'description'      => 'We’ll be back soon.',
			'logo'             => '',
			'background'       => '',
			'background_color' => '',
			'exclude_pages'    => '',
			'logo_width'       => 160,
			'logo_height'      => 160,
			'logo_shape'       => 'circle',
		];

		if ( ! get_option( 'ace_maintenance_options' ) ) {
			add_option( 'ace_maintenance_options', $default_opts );
		}
	}

}






