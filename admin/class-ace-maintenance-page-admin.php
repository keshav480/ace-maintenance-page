<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/ace-maintenance-page
 * @since      1.0.0
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/admin
 * @author     Acewebx <developer@acewebx.com>
 */


if ( ! defined( 'ABSPATH' ) ) { exit; }

class Ace_Maintenance_Page_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style($this->plugin_name . '-admin',plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-admin.css',[],filemtime( plugin_dir_path( __FILE__ ) . 'css/ace-maintenance-page-admin.css' ),'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-admin.js', [ 'jquery' ], filemtime( plugin_dir_path( __FILE__ ) . 'js/ace-maintenance-page-admin.js' ),true);
		
	} 
	 public function ace_maintenance_add_plugin_page() {
        add_menu_page(
            'Ace Maintenance',
            'Ace Maintenance',
            'manage_options',
            'ace-maintenance',
            [ $this, 'ace_maintenance_render_admin_page' ],
            'dashicons-hammer',
            80
        );
    }

	public function ace_maintenance_admin_bar_toggle( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) { return; }

		$ace_maintenance_opts = get_option( 'ace_maintenance_options', [] );
		$enabled = ! empty( $ace_maintenance_opts['enabled'] );

		// Toggle URL (admin-post handler with nonce)
		$toggleUrl = wp_nonce_url(
			admin_url( 'admin-post.php?action=ace_maint_toggle' ),
			'ace_maint_toggle_action',
			'ace_maint_toggle_nonce'
		);

		$label = $enabled ? 'Disable Maintenance' : 'Enable Maintenance';
		$title = ($enabled ? '🟢 ' : '⚪ ') . $label;

		$wp_admin_bar->add_node( [
			'id'    => 'ace-maint-toggle',
			'title' => $title,
			'href'  => $toggleUrl,
			'meta'  => [
				'title' => 'Toggle maintenance mode',
				'class' => 'ace-maint-toggle-node'
			],
		] );
    }

	public function ace_maintenance_handleToggle() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized' ); }
		check_admin_referer( 'ace_maint_toggle_action', 'ace_maint_toggle_nonce' );

		$ace_maintenance_opts = get_option( 'ace_maintenance_options', [] );
		$current = ! empty( $ace_maintenance_opts['enabled'] ) ? 1 : 0;
		$ace_maintenance_opts['enabled'] = $current ? 0 : 1;

		update_option( 'ace_maintenance_options', $ace_maintenance_opts );

		$redirect = wp_get_referer();
		if ( ! $redirect ) { $redirect = admin_url(); }
		wp_safe_redirect( $redirect );
		exit;
    }


    // Handle saving settings and file uploads
    public function ace_maintenance_handle_form_submit() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized' ); }
        check_admin_referer( 'ace_maint_save_action', 'ace_maint_nonce' );

        $ace_maintenance_opts = get_option( 'ace_maintenance_options', [] );

        // Basic fields
        $ace_maintenance_opts['enabled']     = isset( $_POST['enabled'] ) ? 1 : 0;
        $ace_maintenance_opts['title']       = isset( $_POST['title'] ) ? sanitize_text_field(wp_unslash( $_POST['title']) ) : '';

		$ace_maintenance_opts['description'] = isset( $_POST['description'] )
		? wp_kses_post( wp_unslash($_POST['description'] )) 
		: '';

        require_once ABSPATH . 'wp-admin/includes/file.php';

        // Logo upload
        if ( isset( $_FILES['logo_file'] ) && ! empty( $_FILES['logo_file']['name'] ) ) {
            $uploaded = wp_handle_upload( $_FILES['logo_file'], [
                'test_form' => false,
                'mimes'     => [
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'png'          => 'image/png',
                    'gif'          => 'image/gif',
                    'webp'         => 'image/webp',
                ]
            ] );
            if ( isset( $uploaded['url'] ) ) {
                $ace_maintenance_opts['logo'] = esc_url_raw( $uploaded['url'] );
            }
        } else {
            if ( isset( $_POST['logo_old'] ) ) {
                $ace_maintenance_opts['logo'] = esc_url_raw( wp_unslash($_POST['logo_old'] ));
            }
        }

        // Background upload
        if ( isset( $_FILES['background_file'] ) && ! empty( $_FILES['background_file']['name'] ) ) {
            $uploadedBg = wp_handle_upload( $_FILES['background_file'], [
                'test_form' => false,
                'mimes'     => [
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'png'          => 'image/png',
                    'gif'          => 'image/gif',
                    'webp'         => 'image/webp'
                ]
            ] );
            if ( isset( $uploadedBg['url'] ) ) {
                $ace_maintenance_opts['background'] = esc_url_raw( $uploadedBg['url'] );
            }
        } else {
            if ( isset( $_POST['background_old'] ) ) {
                $ace_maintenance_opts['background'] = esc_url_raw( wp_unslash($_POST['background_old'] ));
            }
        }

		// Background color
		$ace_maintenance_opts['background_color'] = isset($_POST['background_color'])
			? sanitize_text_field(wp_unslash($_POST['background_color']))
			: '';

		// Remove background image if requested
		if ( ! empty($_POST['remove_background']) ) {
			$ace_maintenance_opts['background'] = '';
		}

		//exclude filed
		$ace_maintenance_opts['exclude_pages'] = isset( $_POST['exclude_pages'] )
		? sanitize_text_field(wp_unslash($_POST['exclude_pages'] ))
		: '';

		//logo height and width
		$ace_maintenance_opts['logo_width']  = isset($_POST['logo_width']) ? intval($_POST['logo_width']) : '';
		$ace_maintenance_opts['logo_height'] = isset($_POST['logo_height']) ? intval($_POST['logo_height']) : '';

		//logo radius and box
		$ace_maintenance_opts['logo_shape'] = isset($_POST['logo_shape']) 
		? sanitize_text_field(wp_unslash($_POST['logo_shape'])) 
		: 'circle';
        update_option( 'ace_maintenance_options', $ace_maintenance_opts );
        wp_safe_redirect( admin_url( 'admin.php?page=ace-maintenance&updated=1' ) );
        exit;
    }

	public function ace_maintenance_render_admin_page() {
		$ace_maintenance_opts = get_option( 'ace_maintenance_options', [] );
		$ace_maintenance_previewUrl = add_query_arg(
			[
				'ace_preview'       => '1',
				'ace_preview_nonce' => wp_create_nonce( 'ace_preview' ),
			],
			home_url( '/' )
		);

		$context = [
			'opts'        => $ace_maintenance_opts,
			'preview_url' => $ace_maintenance_previewUrl,
		];

		// Instead of ACE_MAINT_PATH, use plugin_dir_path with __DIR__
		$ace_maintenance_partial = plugin_dir_path( __FILE__ ) . 'partials/ace-maintenance-page-admin-display.php';

		if ( file_exists( $ace_maintenance_partial ) ) {
			  require_once $ace_maintenance_partial;
		} else {
			echo '<div class="notice notice-error"><p>Admin partial missing.</p></div>';
		}
   }
   
}









