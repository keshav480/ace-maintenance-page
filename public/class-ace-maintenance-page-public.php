<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://https://wordpress.org/plugins/ace-maintenance-page
 * @since      1.0.0
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/public
 * @author     Acewebx <developer@acewebx.com>
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Ace_Maintenance_Page_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	


	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ace_Maintenance_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ace_Maintenance_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-public.css', array(), $this->version, 'all' );

		$opts    = get_option( 'ace_maintenance_options', [] );
    $enabled = ! empty( $opts['enabled'] );

    $isPreview = isset($_GET['ace_preview'])
        && current_user_can('manage_options')
        && isset($_GET['ace_preview_nonce'])
        && wp_verify_nonce(sanitize_text_field($_GET['ace_preview_nonce']), 'ace_preview');

    if ( $enabled || $isPreview ) {
        wp_enqueue_style(
            $this->plugin_name . '-public',
            plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-public.css',
            [],
            $this->version,
            'all'
        );
    }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ace_Maintenance_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ace_Maintenance_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-public.js', array( 'jquery' ), $this->version, false );

		 $opts    = get_option( 'ace_maintenance_options', [] );
    $enabled = ! empty( $opts['enabled'] );

    $isPreview = isset($_GET['ace_preview'])
        && current_user_can('manage_options')
        && isset($_GET['ace_preview_nonce'])
        && wp_verify_nonce(sanitize_text_field($_GET['ace_preview_nonce']), 'ace_preview');

    if ( $enabled || $isPreview ) {
        wp_enqueue_script(
            $this->plugin_name . '-public',
            plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-public.js',
            [ 'jquery' ],
            $this->version,
            true
        );
    }
	}

	


	public function displayMaintenancePage() {

		$opts    = get_option( 'ace_maintenance_options', [] );
		$enabled = ! empty( $opts['enabled'] );

		// Admin preview detection
		$isPreview = isset( $_GET['ace_preview'] )
			&& current_user_can( 'manage_options' )
			&& isset( $_GET['ace_preview_nonce'] )
			&& wp_verify_nonce( sanitize_text_field( $_GET['ace_preview_nonce'] ), 'ace_preview' );

		// If maintenance not enabled and not preview → do nothing
		if ( ! $enabled && ! $isPreview ) {
			return;
		}

		// If admin and not preview → do nothing
		if ( $enabled && current_user_can( 'manage_options' ) && ! $isPreview ) {
			return;
		}

		// If enabled for visitors, check exclude slugs
		if ( $enabled && ! current_user_can( 'manage_options' ) ) {
			$excluded = array_map( 'trim', explode( ',', $opts['exclude_pages'] ?? '' ));
			global $post;
			if ( $post ) {
				$current_slug = $post->post_name;
				if ( in_array( $current_slug, $excluded, true ) ) {
					return; // skip maintenance, show normal page
				}
			}
		}

		// Build context for template
		nocache_headers();
		$context = [
			'title'       => esc_html( $opts['title'] ?? 'Maintenance Mode' ),
			'description' => wp_kses_post( $opts['description'] ?? 'We’ll be back soon.' ),
			'logo'        => ! empty( $opts['logo'] )
                     ? esc_url( $opts['logo'] )
                     : '',
            'background'  => ! empty( $opts['background'] )
                     ? esc_url( $opts['background'] )
                     : '',

			'background_color' => ! empty( $opts['background_color'] )
			? esc_attr( $opts['background_color'] )
			: '',

			'is_preview'  => $isPreview,
		];

			
	$partial = plugin_dir_path( __DIR__ ) . 'public/partials/ace-maintenance-page-public-display.php';

	if ( file_exists( $partial ) ) {
		ob_start();
		// Print enqueued CSS/JS before and after template
		wp_head();
		include $partial;
		wp_footer();

		$html = ob_get_clean();

		wp_die(
			$html,
			'Maintenance Mode',
			[ 'response' => $isPreview ? 200 : 503 ]
		);
	} else {
		wp_die(
			'Maintenance page template missing.',
			'Maintenance Mode',
			[ 'response' => 503 ]
		);
	}

    } 
}

