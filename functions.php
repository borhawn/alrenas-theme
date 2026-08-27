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
require_once get_template_directory() . '/inc/site-content.php';
require_once get_template_directory() . '/inc/github-updater.php';
require_once get_template_directory() . '/inc/woocommerce.php';
require_once get_template_directory() . '/inc/related-product.php';
require_once get_template_directory() . '/inc/featured-post.php';
require_once get_template_directory() . '/inc/nav-mega-menu.php';
require_once get_template_directory() . '/inc/disable-comments.php';
require_once get_template_directory() . '/inc/product-attributes.php';
require_once get_template_directory() . '/inc/product-meta.php';
require_once get_template_directory() . '/inc/quotes/statuses.php';
require_once get_template_directory() . '/inc/quotes/intake.php';
require_once get_template_directory() . '/inc/quotes/emails.php';
require_once get_template_directory() . '/inc/quotes/admin-meta-boxes.php';
require_once get_template_directory() . '/inc/quotes/admin-list.php';
require_once get_template_directory() . '/inc/quotes/actions.php';
require_once get_template_directory() . '/inc/quotes/frontend.php';

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
