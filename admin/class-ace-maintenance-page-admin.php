<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://https://wordpress.org/plugins/ace-maintenance-page
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

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-admin.css', array(), $this->version, 'all' );

		
		wp_enqueue_style(
			$this->plugin_name . '-admin',
			plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-admin.css',
			[],
			filemtime( plugin_dir_path( __FILE__ ) . 'css/ace-maintenance-page-admin.css' ),
			'all'
		);

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

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-admin.js', array( 'jquery' ), $this->version, false );

	
			wp_enqueue_script(
				$this->plugin_name . '-admin',
				plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-admin.js',
				[ 'jquery' ],
				filemtime( plugin_dir_path( __FILE__ ) . 'js/ace-maintenance-page-admin.js' ),
				true
			);
		
	}

 
	 public function addPluginPage() {
        add_menu_page(
            'Ace Maintenance',
            'Ace Maintenance',
            'manage_options',
            'ace-maintenance',
            [ $this, 'renderAdminPage' ],
            'dashicons-hammer',
            80
        );
    }

	public function addAdminBarToggle( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) { return; }

		$opts = get_option( 'ace_maintenance_options', [] );
		$enabled = ! empty( $opts['enabled'] );

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

	public function handleToggle() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized' ); }
		check_admin_referer( 'ace_maint_toggle_action', 'ace_maint_toggle_nonce' );

		$opts = get_option( 'ace_maintenance_options', [] );
		$current = ! empty( $opts['enabled'] ) ? 1 : 0;
		$opts['enabled'] = $current ? 0 : 1;

		update_option( 'ace_maintenance_options', $opts );

		$redirect = wp_get_referer();
		if ( ! $redirect ) { $redirect = admin_url(); }
		wp_safe_redirect( $redirect );
		exit;
    }


    // Handle saving settings and file uploads
    public function handleFormSubmit() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized' ); }
        check_admin_referer( 'ace_maint_save_action', 'ace_maint_nonce' );

        $opts = get_option( 'ace_maintenance_options', [] );

        // Basic fields
        $opts['enabled']     = isset( $_POST['enabled'] ) ? 1 : 0;
        $opts['title']       = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';

		$opts['description'] = isset( $_POST['description'] )
		? wp_kses_post( $_POST['description'] ) 
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
                $opts['logo'] = esc_url_raw( $uploaded['url'] );
            }
        } else {
            if ( isset( $_POST['logo_old'] ) ) {
                $opts['logo'] = esc_url_raw( $_POST['logo_old'] );
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
                $opts['background'] = esc_url_raw( $uploadedBg['url'] );
            }
        } else {
            if ( isset( $_POST['background_old'] ) ) {
                $opts['background'] = esc_url_raw( $_POST['background_old'] );
            }
        }

		// Background color
		$opts['background_color'] = isset($_POST['background_color'])
			? sanitize_text_field($_POST['background_color'])
			: '';

		// Remove background image if requested
		if ( ! empty($_POST['remove_background']) ) {
			$opts['background'] = '';
		}

		//exclude filed
		$opts['exclude_pages'] = isset( $_POST['exclude_pages'] )
		? sanitize_text_field( $_POST['exclude_pages'] )
		: '';

		//logo height and width
		$opts['logo_width']  = isset($_POST['logo_width']) ? intval($_POST['logo_width']) : '';
		$opts['logo_height'] = isset($_POST['logo_height']) ? intval($_POST['logo_height']) : '';

		//logo radius and box
		$opts['logo_shape'] = isset($_POST['logo_shape']) 
		? sanitize_text_field($_POST['logo_shape']) 
		: 'circle';

        update_option( 'ace_maintenance_options', $opts );
        wp_safe_redirect( admin_url( 'admin.php?page=ace-maintenance&updated=1' ) );
        exit;
    }

	public function renderAdminPage() {
		$opts = get_option( 'ace_maintenance_options', [] );
		$previewUrl = add_query_arg(
			[
				'ace_preview'       => '1',
				'ace_preview_nonce' => wp_create_nonce( 'ace_preview' ),
			],
			home_url( '/' )
		);

		$context = [
			'opts'        => $opts,
			'preview_url' => $previewUrl,
		];

		// Instead of ACE_MAINT_PATH, use plugin_dir_path with __DIR__
		$partial = plugin_dir_path( __DIR__ ) . 'admin/partials/ace-maintenance-page-admin-display.php';

		if ( file_exists( $partial ) ) {
			include $partial;
		} else {
			echo '<div class="notice notice-error"><p>Admin partial missing.</p></div>';
		}
   }
   
}









