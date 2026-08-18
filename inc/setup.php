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
