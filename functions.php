<?php
/**
 * Alrenas theme bootstrap.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/woocommerce.php';

/**
 * Register editable theme menu locations.
 */
function alrenas_register_menus() {
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'alrenas' ),
			'footer'  => esc_html__( 'Footer Company Menu', 'alrenas' ),
			'contact' => esc_html__( 'Footer Contact Menu', 'alrenas' ),
			'legal'   => esc_html__( 'Footer Legal Menu', 'alrenas' ),
		)
	);
}
add_action( 'after_setup_theme', 'alrenas_register_menus' );
