<?php
/**
 * Theme integration helpers.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alrenas_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_404() ) {
		$classes[] = 'error-page';
	}

	return $classes;
}
add_filter( 'body_class', 'alrenas_body_classes' );

function alrenas_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'alrenas_pingback_header' );

/**
 * Map WordPress current-menu state to the existing header-link class.
 *
 * @param array    $attributes Link attributes.
 * @param WP_Post  $menu_item Menu item object.
 * @param stdClass $args       Menu arguments.
 * @return array
 */
function alrenas_primary_menu_link_attributes( $attributes, $menu_item, $args ) {
	if ( 'primary' !== $args->theme_location ) {
		return $attributes;
	}

	$current_classes = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor', 'current_page_item' );
	if ( array_intersect( $current_classes, (array) $menu_item->classes ) ) {
		$attributes['class'] = trim( ( $attributes['class'] ?? '' ) . ' is-current' );
	}

	return $attributes;
}
add_filter( 'nav_menu_link_attributes', 'alrenas_primary_menu_link_attributes', 10, 3 );

/**
 * Keep auto-generated excerpts short — blog cards clamp to 2-3 lines with
 * CSS, so a long default 55-word excerpt just wastes markup.
 *
 * @return int
 */
function alrenas_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'alrenas_excerpt_length', 999 );

/**
 * Replace the default "[...]" excerpt trailer.
 *
 * @return string
 */
function alrenas_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'alrenas_excerpt_more' );

/**
 * Estimate a post's reading time.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function alrenas_reading_time( $post_id = 0 ) {
	$content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );

	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Collect anchored core Heading blocks for the visual article TOC.
 *
 * @param array $blocks Parsed blocks.
 * @return array
 */
function alrenas_collect_toc_items( $blocks ) {
	$items = array();

	foreach ( $blocks as $block ) {
		if ( 'core/heading' === $block['blockName'] && ! empty( $block['attrs']['anchor'] ) ) {
			$items[] = array(
				'anchor' => sanitize_title( $block['attrs']['anchor'] ),
				'label'  => wp_strip_all_tags( render_block( $block ) ),
			);
		}

		if ( ! empty( $block['innerBlocks'] ) ) {
			$items = array_merge( $items, alrenas_collect_toc_items( $block['innerBlocks'] ) );
		}
	}

	return $items;
}
