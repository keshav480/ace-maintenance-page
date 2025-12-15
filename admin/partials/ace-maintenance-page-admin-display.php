<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/plugins/ace-maintenance-page
 * @since      1.0.0
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
if( ! defined( 'ABSPATH' ) ) { exit; }
    $ace_maintenance_opts = isset( $context['opts'] ) ? $context['opts'] : [];
  $ace_maintenance_preview_url  = isset( $context['preview_url'] ) ? $context['preview_url'] : home_url( '/' );
?>

<div class="wrap">

    <h1>Ace Maintenance Settings</h1>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
        <?php wp_nonce_field( 'ace_maint_save_action', 'ace_maint_nonce' ); ?>
        <input type="hidden" name="action" value="ace_maint_save">
        <input type="hidden" name="logo_old" value="<?php echo esc_attr( $ace_maintenance_opts['logo'] ?? '' ); ?>">
        <input type="hidden" name="background_old" value="<?php echo esc_attr( $ace_maintenance_opts['background'] ?? '' ); ?>">

    <table class="form-table">
        <tr>
            <th scope="row">Enable Maintenance Mode</th>
            <td>
                <label class="ace-switch">
                <input type="checkbox" name="enabled" id="ace-enabled-toggle"
                        <?php checked( 1, $ace_maintenance_opts['enabled'] ?? 0 ); ?>>
                <span class="ace-slider"></span>
                </label>

                <!-- Preview button (hidden by default if toggle is off) -->
                <?php 
              $ace_maintenance_preview_url  = add_query_arg(
                    [
                    'ace_preview'       => 1,
                    'ace_preview_nonce' => wp_create_nonce( 'ace_preview' ),
                    ],
                    home_url()
                );
                ?>
                <p id="ace-preview-link" style="<?php echo ! empty( $ace_maintenance_opts['enabled'] ) ? '' : 'display:none;'; ?>">
                <a class="" href="<?php echo esc_url( $previewUrl ); ?>" target="_blank">
                    Preview Maintenance
                </a>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">Heading</th>
                <td><input type="text" name="title" class="regular-text" value="<?php echo esc_attr( $ace_maintenance_opts['title'] ?? '' ); ?>">
                    <p class="description">
                This title will be displayed prominently on the maintenance page.
                Example: <code>Site Under Maintenance</code>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">Description</th>
            <td>
                <?php
                $ace_maintenance_content   = isset( $ace_maintenance_opts['description'] ) ? $ace_maintenance_opts['description'] : '';
                $ace_maintenance_editor_id = 'ace_description';
                $ace_maintenance_setting  = [
                    'textarea_name' => 'description',
                    'media_buttons' => true,          
                    'tinymce'       => [
                        'toolbar1' => 'bold italic underline | bullist numlist | link unlink | undo redo',
                        'toolbar2' => '',
                    ],
                    'quicktags'     => true,         
                    'editor_height' => 200,
                ];
                wp_editor( $ace_maintenance_content, $ace_maintenance_editor_id, $ace_maintenance_setting );
                ?>
                    <p class="description">
                Write a short message for visitors explaining the maintenance.
                You can format text, add lists, or links.
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">Logo(file upload)</th>
            <td>
                <input type="file" name="logo_file" accept="image/*">
                <div class="ace-preview">
                    <?php if ( ! empty( $ace_maintenance_opts['logo'] ) ) : ?>
                        <img src="<?php echo esc_url( $ace_maintenance_opts['logo']); ?>" alt="Logo" style="max-width:120px;">
                            <p class="description">
                        Upload a logo image to display on the maintenance page.
                        Recommended size: square image for best results.
                        </p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
        <th scope="row">Background</th>
            <td>
                <!-- Background image upload -->
                <input type="file" name="background_file" accept="image/*">
                <div class="ace-preview">
                <?php if ( ! empty( $ace_maintenance_opts['background'] ) ) : ?>
                    <img src="<?php echo esc_url( $ace_maintenance_opts['background'] ); ?>" alt="Background" style="max-width:160px;">
                    <br>
                    <label>
                    <input type="checkbox" name="remove_background" value="1">
                    Remove current background image
                    </label>
                <?php endif; ?>
                </div>

                <!-- Background color picker -->
                <input type="text" name="background_color" 
                    value="<?php echo esc_attr( $ace_maintenance_opts['background_color'] ?? '' ); ?>" 
                    class="regular-text ace-color-field" />
                <p class="description">
                Upload a background image or enter a background color (e.g. <code>#f0f0f0</code> or <code>red</code>).  
                If both are set, the image will be used unless you check “Remove current background image”.
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">Exclude Pages</th>
            <td>
                <textarea name="exclude_pages" rows="3" class="large-text"><?php 
                    echo esc_textarea( $ace_maintenance_opts['exclude_pages'] ?? '' ); 
                ?></textarea>
                <p class="description">
                    Enter page slugs separated by commas.  
                    Example: <code>about-us, contact, blog</code>  
                    These pages will remain accessible even when maintenance mode is enabled.
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">Logo Width (px)</th>
            <td>
                <input type="number" name="logo_width" 
                    value="<?php echo esc_attr( $ace_maintenance_opts['logo_width'] ?? '' ); ?>" 
                    class="small-text"/>
                <p class="description">Set the logo width in pixels (e.g., 160).</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Logo Height (px)</th>
            <td>
                <input type="number" name="logo_height" 
                    value="<?php echo esc_attr( $ace_maintenance_opts['logo_height'] ?? '' ); ?>" 
                    class="small-text"  />
                <p class="description">Set the logo height in pixels (e.g., 160).</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Logo Shape</th>
            <td>
                <fieldset>
                <label>
                    <input type="radio" name="logo_shape" value="circle"
                    <?php checked( $ace_maintenance_opts['logo_shape'] ?? 'circle', 'circle' ); ?> />
                    Circle (default)
                </label><br>
                <label>
                    <input type="radio" name="logo_shape" value="box"
                    <?php checked( $ace_maintenance_opts['logo_shape'] ?? 'circle', 'box' ); ?> />
                    Box / Square
                </label>
                </fieldset>
                <p class="description">
                Choose how the logo should appear:<br>
                <strong>Circle</strong> → logo is rounded (default)<br>
                <strong>Box</strong> → logo keeps square/rectangle edges
                </p>
            </td>
        </tr>
        </table>
        <?php submit_button('Save changes'); ?>

    </form>
</div>







