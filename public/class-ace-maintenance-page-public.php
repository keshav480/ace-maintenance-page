<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/ace-maintenance-page
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

		$ace_maintenance_opts    = get_option( 'ace_maintenance_options', [] );
		$enabled = ! empty( $ace_maintenance_opts['enabled'] );
		$ace_maintenance_Preview = isset($_GET['ace_preview']) && current_user_can('manage_options') && isset($_GET['ace_preview_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['ace_preview_nonce'])), 'ace_preview'); 
	
		if ( $enabled || $ace_maintenance_Preview ) {$css_file = plugin_dir_path( __FILE__ ) . 'css/ace-maintenance-page-public.css';wp_enqueue_style($this->plugin_name . '-public',plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-public.css',[],file_exists( $css_file ) ? filemtime( $css_file ) : $this->version,'all');

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

		 $ace_maintenance_opts    = get_option( 'ace_maintenance_options', [] );
   		 $enabled = ! empty( $ace_maintenance_opts['enabled'] );
		$ace_maintenance_Preview = isset($_GET['ace_preview'])
			&& current_user_can('manage_options')
			&& isset($_GET['ace_preview_nonce'])
			&& wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['ace_preview_nonce'])), 'ace_preview');

		if ( $enabled || $ace_maintenance_Preview ) {
			wp_enqueue_script(
				$this->plugin_name . '-public',
				plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-public.js',
				[ 'jquery' ],
				$this->version,
				true
			);
		}
	}
	public function ace_maintenance_page_display() {
		$ace_maintenance_opts    = get_option( 'ace_maintenance_options', [] );
		$enabled = ! empty( $ace_maintenance_opts['enabled'] );
		$ace_maintenance_Preview = isset( $_GET['ace_preview'] )
			&& current_user_can( 'manage_options' )
			&& isset( $_GET['ace_preview_nonce'] )
			&& wp_verify_nonce( sanitize_text_field(wp_unslash($_GET['ace_preview_nonce'] )), 'ace_preview' );
		if ( ! $enabled && ! $ace_maintenance_Preview ) {
			return;
		}
		if ( $enabled && current_user_can( 'manage_options' ) && ! $ace_maintenance_Preview ) {
			return;
		}
		if ( $enabled && ! current_user_can( 'manage_options' ) ) {
			$excluded = array_map( 'trim', explode( ',', $ace_maintenance_opts['exclude_pages'] ?? '' ));
			global $post;
			if ( $post ) {
				$current_slug = $post->post_name;
				if ( in_array( $current_slug, $excluded, true ) ) {
					return; 
				}
			}
		}
		nocache_headers();
		$context = [
			'title'       => esc_html( $ace_maintenance_opts['title'] ?? 'Maintenance Mode' ),
			'description' => wp_kses_post( $ace_maintenance_opts['description'] ?? 'We’ll be back soon.' ),
			'logo'        => ! empty( $ace_maintenance_opts['logo'] )
                     ? esc_url( $ace_maintenance_opts['logo'] )
                     : '',
            'background'  => ! empty( $ace_maintenance_opts['background'] )
                     ? esc_url( $ace_maintenance_opts['background'] )
                     : '',

			'background_color' => ! empty( $ace_maintenance_opts['background_color'] )
			? esc_attr( $ace_maintenance_opts['background_color'] )
			: '',
			'is_preview'  => $ace_maintenance_Preview,
		];
	$partial = plugin_dir_path(__FILE__ ) . 'partials/ace-maintenance-page-public-display.php';

	if ( file_exists( $partial ) ) {
		ob_start();
		wp_head();
		require_once $partial; 
		wp_footer();
		wp_die(
			 wp_kses_post($html),
			esc_html__( 'Maintenance Mode', 'ace-maintenance-page' ),
			[ 'response' => $ace_maintenance_Preview ? 200 : 503 ]
		);
		} else {
		wp_die(
			esc_html__( 'Maintenance page template missing.', 'ace-maintenance-page' ),
			esc_html__( 'Maintenance Mode', 'ace-maintenance-page' ),
			[ 'response' => 503 ]
		);
		$html = ob_get_clean();

	}

    } 
}

