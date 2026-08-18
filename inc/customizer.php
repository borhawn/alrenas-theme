<?php
/**
 * Theme Customizer settings.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register editable header CTA settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function alrenas_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'alrenas_header_options',
		array(
			'title'    => esc_html__( 'Header Options', 'alrenas' ),
			'priority' => 35,
		)
	);

	$wp_customize->add_setting(
		'alrenas_header_cta_label',
		array(
			'default'           => __( 'Request a Demo', 'alrenas' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'alrenas_header_cta_label',
		array(
			'label'   => esc_html__( 'Button label', 'alrenas' ),
			'section' => 'alrenas_header_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'alrenas_header_cta_url',
		array(
			'default'           => home_url( '/' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'alrenas_header_cta_url',
		array(
			'label'       => esc_html__( 'Button URL', 'alrenas' ),
			'description' => esc_html__( 'Enter the page or section URL used by the header button.', 'alrenas' ),
			'section'     => 'alrenas_header_options',
			'type'        => 'url',
		)
	);

	$wp_customize->add_section(
		'alrenas_home_options',
		array(
			'title'    => esc_html__( 'Homepage Options', 'alrenas' ),
			'priority' => 37,
		)
	);

	$wp_customize->add_setting(
		'alrenas_about_page_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'alrenas_about_page_id',
		array(
			'label'   => esc_html__( 'About page', 'alrenas' ),
			'section' => 'alrenas_home_options',
			'type'    => 'dropdown-pages',
		)
	);
}
add_action( 'customize_register', 'alrenas_customize_register' );
