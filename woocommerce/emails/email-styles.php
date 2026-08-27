<?php
/**
 * Email styles -- theme override.
 *
 * WooCommerce captures this file's raw output as a CSS string and inlines
 * it into every order email via Emogrifier (see WC_Email::style_inline()),
 * so this deliberately outputs bare CSS rules, not a <style> block. Colors
 * still come from WooCommerce -> Settings -> Emails so the admin keeps a
 * familiar way to adjust them; only the fonts, spacing and a couple of
 * theme-specific classes (used by our own quote email templates) are
 * hardcoded here to match the site.
 *
 * Deliberately smaller than WooCommerce's own emails/email-styles.php --
 * that file also styles a handful of newer, feature-flagged email-editor
 * layouts this theme doesn't use, which would be more version-coupled
 * risk than benefit to replicate here.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bg          = get_option( 'woocommerce_email_background_color', '#F7FAFB' );
$body_bg     = get_option( 'woocommerce_email_body_background_color', '#FFFFFF' );
$base        = get_option( 'woocommerce_email_base_color', '#0F5F9D' );
$text        = get_option( 'woocommerce_email_text_color', '#173241' );
$footer_text = get_option( 'woocommerce_email_footer_text_color', '#5E7480' );

$heading_font = "'Manrope', 'Segoe UI', Helvetica, Arial, sans-serif";
$body_font    = "'Source Sans 3', 'Segoe UI', Helvetica, Arial, sans-serif";
$line_color   = '#D9E5EA';
$canvas       = '#F3F9FC';
?>
body {
	background-color: <?php echo esc_attr( $bg ); ?>;
	padding: 0;
	text-align: center;
	font-family: <?php echo esc_attr( $body_font ); ?>;
}

#outer_wrapper {
	background-color: <?php echo esc_attr( $bg ); ?>;
}

#wrapper {
	margin: 0 auto;
	padding: 48px 0;
	width: 100%;
	max-width: 600px;
}

#template_container {
	background-color: <?php echo esc_attr( $body_bg ); ?>;
	border-radius: 22px;
	overflow: hidden;
	box-shadow: 0 20px 60px rgba(27,78,101,.10);
}

#template_header_image {
	padding: 32px 32px 0;
}

.email-logo-text {
	font-family: <?php echo esc_attr( $heading_font ); ?>;
	font-size: 20px;
	font-weight: 700;
	color: <?php echo esc_attr( $text ); ?>;
}

#template_header {
	background-color: <?php echo esc_attr( $base ); ?>;
}

#header_wrapper {
	padding: 28px 32px;
}

#template_header h1,
#header_wrapper h1 {
	margin: 0;
	color: #ffffff;
	font-family: <?php echo esc_attr( $heading_font ); ?>;
	font-size: 22px;
	font-weight: 700;
}

#body_content {
	background-color: <?php echo esc_attr( $body_bg ); ?>;
}

#body_content_inner_cell,
#body_content_inner {
	font-family: <?php echo esc_attr( $body_font ); ?>;
	font-size: 15px;
	line-height: 1.6;
	color: <?php echo esc_attr( $text ); ?>;
	text-align: left;
}

#body_content_inner h2 {
	font-family: <?php echo esc_attr( $heading_font ); ?>;
	font-size: 18px;
	font-weight: 700;
	color: <?php echo esc_attr( $text ); ?>;
}

#body_content_inner a,
#body_content_inner a.link {
	color: <?php echo esc_attr( $base ); ?>;
}

.td {
	padding: 12px 8px;
	border: 1px solid <?php echo esc_attr( $line_color ); ?>;
	font-family: <?php echo esc_attr( $body_font ); ?>;
	font-size: 14px;
	color: <?php echo esc_attr( $text ); ?>;
}

.text-align-left {
	text-align: left;
}

.text-align-right {
	text-align: right;
}

.order-totals-last th,
.order-totals-last td {
	font-weight: 700;
}

.email-button {
	display: inline-block;
	margin: 8px 0 4px;
	padding: 14px 28px;
	border-radius: 13px;
	background-color: <?php echo esc_attr( $base ); ?>;
	color: #ffffff !important;
	font-family: <?php echo esc_attr( $heading_font ); ?>;
	font-size: 14px;
	font-weight: 700;
	text-decoration: none;
}

.email-secondary-button {
	display: inline-block;
	margin: 8px 0 4px 10px;
	padding: 14px 28px;
	border-radius: 13px;
	border: 1px solid <?php echo esc_attr( $line_color ); ?>;
	background-color: <?php echo esc_attr( $canvas ); ?>;
	color: <?php echo esc_attr( $base ); ?> !important;
	font-family: <?php echo esc_attr( $heading_font ); ?>;
	font-size: 14px;
	font-weight: 700;
	text-decoration: none;
}

.email-note-box {
	margin: 24px 0;
	padding: 16px 18px;
	border-radius: 13px;
	background-color: <?php echo esc_attr( $canvas ); ?>;
	color: <?php echo esc_attr( $text ); ?>;
	font-size: 14px;
}

#template_footer {
	background-color: <?php echo esc_attr( $bg ); ?>;
}

#credit {
	font-family: <?php echo esc_attr( $body_font ); ?>;
	font-size: 12px;
	color: <?php echo esc_attr( $footer_text ); ?>;
}
