<?php
/**
 * Frontend asset registration and enqueueing.
 *
 * Loading graph:
 * - All pages: Google Fonts -> page stylesheet -> WordPress integration CSS.
 * - Front page: styles.css + script.js.
 * - WooCommerce product: styles.css -> product.css -> WordPress integration CSS;
 *   script.js. WooCommerce owns its product-gallery scripts.
 * - Other frontend views: theme.css + theme.js.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a cache-busting asset version.
 *
 * Local assets use their modification time during development. The declared
 * theme version is retained as a safe fallback if the file cannot be read.
 *
 * @param string $relative_path Theme-relative asset path.
 * @return string
 */
function alrenas_asset_version( $relative_path ) {
	$file = get_theme_file_path( $relative_path );

	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

/**
 * Register every stylesheet and script referenced by the static template.
 */
function alrenas_register_assets() {
	wp_register_style(
		'alrenas-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap',
		array(),
		null
	);

	wp_register_style(
		'alrenas-base-style',
		get_theme_file_uri( 'assets/css/styles.css' ),
		array( 'alrenas-fonts' ),
		alrenas_asset_version( 'assets/css/styles.css' )
	);

	wp_register_style(
		'alrenas-inner-style',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array( 'alrenas-fonts' ),
		alrenas_asset_version( 'assets/css/theme.css' )
	);

	wp_register_style(
		'alrenas-product-style',
		get_theme_file_uri( 'assets/css/product.css' ),
		array( 'alrenas-base-style' ),
		alrenas_asset_version( 'assets/css/product.css' )
	);

	wp_register_style(
		'alrenas-blog-card',
		get_theme_file_uri( 'assets/css/blog-card.css' ),
		array( 'alrenas-base-style' ),
		alrenas_asset_version( 'assets/css/blog-card.css' )
	);

	wp_register_style(
		'alrenas-fluentforms',
		get_theme_file_uri( 'assets/css/fluentforms.css' ),
		array(),
		alrenas_asset_version( 'assets/css/fluentforms.css' )
	);

	wp_register_script(
		'alrenas-shared-script',
		get_theme_file_uri( 'assets/js/script.js' ),
		array(),
		alrenas_asset_version( 'assets/js/script.js' ),
		true
	);

	wp_register_script(
		'alrenas-inner-script',
		get_theme_file_uri( 'assets/js/theme.js' ),
		array(),
		alrenas_asset_version( 'assets/js/theme.js' ),
		true
	);

	wp_register_script(
		'alrenas-product-script',
		get_theme_file_uri( 'assets/js/product.js' ),
		array( 'alrenas-shared-script' ),
		alrenas_asset_version( 'assets/js/product.js' ),
		true
	);

	wp_register_script(
		'alrenas-hero-slider',
		get_theme_file_uri( 'assets/js/hero-slider.js' ),
		array(),
		alrenas_asset_version( 'assets/js/hero-slider.js' ),
		true
	);

	wp_register_style(
		'alrenas-quote-modal',
		get_theme_file_uri( 'assets/css/quote-modal.css' ),
		array( 'alrenas-product-style' ),
		alrenas_asset_version( 'assets/css/quote-modal.css' )
	);

	wp_register_script(
		'alrenas-quote-modal',
		get_theme_file_uri( 'assets/js/quote-modal.js' ),
		array( 'alrenas-shared-script' ),
		alrenas_asset_version( 'assets/js/quote-modal.js' ),
		true
	);

	wp_register_style(
		'alrenas-quote-view',
		get_theme_file_uri( 'assets/css/quote-view.css' ),
		array( 'alrenas-inner-style' ),
		alrenas_asset_version( 'assets/css/quote-view.css' )
	);
}

/**
 * Load only the original bundle required by the current frontend view.
 */
function alrenas_enqueue_assets() {
	if ( is_admin() ) {
		return;
	}

	alrenas_register_assets();

	$is_product    = is_singular( 'product' );
	$is_quote_view = function_exists( 'alrenas_is_quote_view_request' ) && alrenas_is_quote_view_request();

	if ( $is_product ) {
		wp_enqueue_style( 'alrenas-product-style' );
		wp_enqueue_style( 'alrenas-quote-modal' );
		wp_enqueue_script( 'alrenas-shared-script' );
		wp_enqueue_script( 'alrenas-product-script' );
		wp_enqueue_script( 'alrenas-quote-modal' );
		$integration_dependencies = array( 'alrenas-product-style' );
	} elseif ( $is_quote_view ) {
		// The guest quote-view page renders at a virtual, unregistered
		// URL intercepted on template_redirect (see inc/quotes/actions.php),
		// so it needs its own branch rather than falling through to
		// whichever real page/archive type that URL would otherwise 404 as.
		wp_enqueue_style( 'alrenas-inner-style' );
		wp_enqueue_style( 'alrenas-quote-view' );
		wp_enqueue_script( 'alrenas-inner-script' );
		$integration_dependencies = array( 'alrenas-inner-style' );
	} elseif ( is_front_page() ) {
		wp_enqueue_style( 'alrenas-base-style' );
		wp_enqueue_style( 'alrenas-blog-card' );
		wp_enqueue_script( 'alrenas-shared-script' );
		wp_enqueue_script( 'alrenas-hero-slider' );
		$integration_dependencies = array( 'alrenas-blog-card' );
	} else {
		wp_enqueue_style( 'alrenas-inner-style' );
		wp_enqueue_script( 'alrenas-inner-script' );
		$integration_dependencies = array( 'alrenas-inner-style' );
	}

	wp_enqueue_style(
		'alrenas-wordpress',
		get_theme_file_uri( 'assets/css/wordpress.css' ),
		$integration_dependencies,
		alrenas_asset_version( 'assets/css/wordpress.css' )
	);

	$is_woocommerce_frontend = class_exists( 'WooCommerce' ) && (
		is_front_page()
		|| ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
		|| ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_checkout' ) && is_checkout() )
		|| ( function_exists( 'is_account_page' ) && is_account_page() )
	);

	if ( $is_woocommerce_frontend ) {
		wp_enqueue_style(
			'alrenas-woocommerce',
			get_theme_file_uri( 'assets/css/woocommerce.css' ),
			array( 'alrenas-wordpress' ),
			alrenas_asset_version( 'assets/css/woocommerce.css' )
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'alrenas-fluentforms' );
}
add_action( 'wp_enqueue_scripts', 'alrenas_enqueue_assets' );

/**
 * Restore the font preconnect hints used by the static template.
 *
 * @param array  $urls          Resource-hint URLs.
 * @param string $relation_type Hint relation type.
 * @return array
 */
function alrenas_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}
add_filter( 'wp_resource_hints', 'alrenas_resource_hints', 10, 2 );
