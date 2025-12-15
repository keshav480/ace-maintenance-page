<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://https://https://wordpress.org/plugins/ace-maintenance-page
 * @since      1.0.0
 *
 * @package    Ace_Maintenance_Page
 * @subpackage Ace_Maintenance_Page/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
    $title       = isset( $context['title'] ) ? $context['title'] : 'Maintenance Mode';
    $ace_maintenance_description = isset( $context['description'] ) ? $context['description'] : 'We’ll be back soon.';
    $ace_maintenance_logo        = isset( $context['logo'] ) ? $context['logo'] : '';
    $ace_maintenance_background  = isset( $context['background'] ) ? $context['background'] : '';
    $ace_maintenance_Preview  = ! empty( $context['is_preview'] );
?>

  <div class="ace-maintenance" style=" <?php if ( ! empty( $context['background'] ) ) : ?> background-image:url('<?php echo esc_url( $context['background'] ); ?>');
     <?php elseif ( ! empty( $context['background_color'] ) ) : ?>
         background-color:<?php echo esc_attr( $context['background_color'] ); ?>;
       <?php endif; ?>
      ">

    <div class="ace-container">

      <?php if ( $ace_maintenance_logo ) : ?>
      <div class="ace-logo">
        <img src="<?php echo esc_url( $ace_maintenance_logo ); ?>"
            alt="Logo"
            style="
              <?php if ( ! empty( $ace_maintenance_opts['logo_width'] ) )  echo 'width:' . intval( $ace_maintenance_opts['logo_width'] ) . 'px;'; ?>
              <?php if ( ! empty( $ace_maintenance_opts['logo_height'] ) ) echo 'height:' . intval( $ace_maintenance_opts['logo_height'] ) . 'px;'; ?>
              <?php if ( ( $ace_maintenance_opts['logo_shape'] ?? 'circle' ) === 'circle' ) {
                echo 'border-radius:50%;';
              } else {
                echo 'border-radius:0;';
              } ?>
            " />
      </div>
    <?php endif; ?>

    <h1 class="ace-title"><?php echo esc_html( $title ); ?></h1>

    <p class="ace-description">

    <?php
      $ace_maintenance_shortcode = wp_kses_post( $ace_maintenance_description );
      echo do_shortcode( $ace_maintenance_shortcode );
      ?>
    </p>

    <?php if ( $ace_maintenance_Preview ) : ?>
        <p class="ace-preview-note">Preview mode</p>
    <?php endif; ?>
    </div>
  </div>
  




