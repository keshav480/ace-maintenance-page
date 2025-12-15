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
    $description = isset( $context['description'] ) ? $context['description'] : 'We’ll be back soon.';
    $logo        = isset( $context['logo'] ) ? $context['logo'] : '';
    $background  = isset( $context['background'] ) ? $context['background'] : '';
    $isPreview  = ! empty( $context['is_preview'] );
?>

<style>
  
/* Full screen background with dark overlay */
.ace-maintenance {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: #111 no-repeat center center;
  background-size: cover;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Segoe UI', Arial, sans-serif;
  color: #fff;
  text-align: center;
  overflow: hidden;
}

.ace-maintenance::before {
  content: "";
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.55); /* subtle overlay */
  z-index: -1; /* push behind content */
}

/* Content wrapper */
.ace-content {
  position: relative;
  z-index: 1;
  padding: 20px;
  animation: fadeIn 1s ease-in-out;
}

/* Logo styled as circle */
.ace-logo img {
  width: 160px;
  height: 160px;
  object-fit: cover;       /* ensures image fills circle */
  border-radius: 50%;      /* makes it circular */
  border: 4px solid #fff;  /* white border for contrast */
  margin-bottom: 25px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 4px 10px rgba(0,0,0,0.4);
}
.ace-logo img:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 14px rgba(0,0,0,0.6);
}

/* Title styling with gradient that works on most images */
.ace-title {
  font-size: 2.8em;
  margin-bottom: 20px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  background: linear-gradient(90deg, #FFD700, #FF8C00, #FF4500); /* gold → orange → red */
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
  animation: fadeInDown 1s ease;
}

/* Description styling */
.ace-description {
  font-size: 1.3em;
  margin-bottom: 30px;
  line-height: 1.6;
  color: #f0f0f0;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
  animation: fadeInUp 1.2s ease;
}

/* Email form */
.ace-form {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  gap: 10px;
}
.ace-form input[type="email"] {
  padding: 12px;
  width: 60%;
  border: none;
  border-radius: 6px;
  outline: none;
  transition: box-shadow 0.3s, transform 0.2s;
}
.ace-form input[type="email"]:focus {
  box-shadow: 0 0 10px rgba(255,255,255,0.9);
  transform: scale(1.02);
}
.ace-form button {
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  background: linear-gradient(135deg, #ff5722, #e64a19);
  color: #fff;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
}
.ace-form button:hover {
  background: linear-gradient(135deg, #ff7043, #d84315);
  transform: scale(1.05);
}

/* Preview note */
.ace-preview-note {
  margin-top: 15px;
  font-size: 0.9em;
  color: #ddd;
  opacity: 0.8;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>


  <div class="ace-maintenance" style=" <?php if ( ! empty( $context['background'] ) ) : ?> background-image:url('<?php echo esc_url( $context['background'] ); ?>');
     <?php elseif ( ! empty( $context['background_color'] ) ) : ?>
         background-color:<?php echo esc_attr( $context['background_color'] ); ?>;
       <?php endif; ?>
      ">

    <div class="ace-container">

      <?php if ( $logo ) : ?>
      <div class="ace-logo">
        <img src="<?php echo esc_url( $logo ); ?>"
            alt="Logo"
            style="
              <?php if ( ! empty( $opts['logo_width'] ) )  echo 'width:' . intval( $opts['logo_width'] ) . 'px;'; ?>
              <?php if ( ! empty( $opts['logo_height'] ) ) echo 'height:' . intval( $opts['logo_height'] ) . 'px;'; ?>
              <?php if ( ( $opts['logo_shape'] ?? 'circle' ) === 'circle' ) {
                echo 'border-radius:50%;';
              } else {
                echo 'border-radius:0;';
              } ?>
            " />
      </div>
    <?php endif; ?>

    <h1 class="ace-title"><?php echo esc_html( $title ); ?></h1>

    <p class="ace-description">
      <?php echo htmlspecialchars_decode( $description ); ?>
    </p>

    <form class="ace-form" onsubmit="return false;">
        <input type="email" placeholder="Enter your email">
        <button type="button">Notify Me</button>
    </form>

    <?php if ( $isPreview ) : ?>
        <p class="ace-preview-note">Preview mode</p>
    <?php endif; ?>
    </div>
  </div>
  




