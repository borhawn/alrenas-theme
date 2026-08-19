<?php
/**
 * Theme setup and content-width registration.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alrenas_setup() {
	load_theme_textdomain( 'alrenas', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

}
add_action( 'after_setup_theme', 'alrenas_setup' );

/**
 * Allow SVG uploads (e.g. for the site logo), restricted to administrators
 * since SVGs can carry embedded scripts if not sanitized.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function alrenas_allow_svg_upload( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'alrenas_allow_svg_upload' );

/**
 * Core's filetype/ext check doesn't recognize SVG by default, which can
 * cause wp_get_attachment_image() to skip rendering the image tag.
 *
 * @param array  $data     Filetype data.
 * @param string $file     Full path to the file.
 * @param string $filename Filename.
 * @param array  $mimes    Allowed mime types.
 * @return array
 */
function alrenas_fix_svg_filetype( $data, $file, $filename, $mimes ) {
	if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
		return $data;
	}

	$filetype = wp_check_filetype( $filename, $mimes );

	if ( 'svg' === $filetype['ext'] ) {
		$data['ext']             = 'svg';
		$data['type']            = 'image/svg+xml';
		$data['proper_filename'] = $filename;
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'alrenas_fix_svg_filetype', 10, 4 );

/**
 * SVGs have no raster dimensions in attachment metadata, so give the media
 * library grid a sane preview size instead of a 0x0 thumbnail.
 */
function alrenas_svg_media_grid_fix() {
	echo '<style>.media-icon img[src$=".svg"], .attachment-preview img[src$=".svg"] { width: 100%; height: auto; }</style>';
}
add_action( 'admin_head', 'alrenas_svg_media_grid_fix' );

function alrenas_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'alrenas_content_width', 1240 );
}
add_action( 'after_setup_theme', 'alrenas_content_width', 0 );

/**
 * Register plugin-friendly content areas.
 */
function alrenas_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Homepage Contact Form', 'alrenas' ),
			'id'            => 'home-contact-form',
			'description'   => esc_html__( 'Add a contact-form plugin block or shortcode widget for the homepage contact section.', 'alrenas' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Contact Page Form', 'alrenas' ),
			'id'            => 'contact-page-form',
			'description'   => esc_html__( 'Add the contact-form plugin block or shortcode widget used on the Contact page.', 'alrenas' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Product Inquiry Form', 'alrenas' ),
			'id'            => 'product-inquiry-form',
			'description'   => esc_html__( 'Add a contact-form plugin block or shortcode widget for inquiries shown below WooCommerce products.', 'alrenas' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'alrenas_widgets_init' );
