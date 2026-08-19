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
 * Build the article's rendered content with H2/H3 anchors injected, plus a
 * flat list of those headings for the table of contents. Computed once per
 * post per request and shared between table-of-contents.php (which needs
 * the list) and article/content.php (which needs the anchor-tagged HTML) so
 * both agree on the same anchor slugs.
 *
 * Works against the final rendered HTML (not Gutenberg blocks), so it picks
 * up headings regardless of whether the post was written in the block
 * editor, the classic editor, or has raw HTML pasted in.
 *
 * @param int $post_id Post ID.
 * @return array{content:string,items:array<int,array{level:int,label:string,anchor:string}>}
 */
function alrenas_get_article_toc_data( $post_id ) {
	static $cache = array();

	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}

	$html  = get_the_content( null, false, $post_id );
	$html  = apply_filters( 'the_content', $html );
	$items = array();

	if ( $html && class_exists( 'DOMDocument' ) ) {
		$dom = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML(
			'<?xml encoding="utf-8"?><div id="alrenas-toc-root">' . $html . '</div>',
			LIBXML_NOERROR | LIBXML_NOWARNING
		);
		libxml_clear_errors();

		$xpath    = new DOMXPath( $dom );
		$root     = $xpath->query( '//*[@id="alrenas-toc-root"]' )->item( 0 );
		$headings = $xpath->query( '//h2 | //h3' );
		$slugs    = array();

		foreach ( $headings as $heading ) {
			$label = trim( $heading->textContent );

			if ( '' === $label ) {
				continue;
			}

			$anchor = $heading->getAttribute( 'id' );

			if ( ! $anchor ) {
				$base   = sanitize_title( $label );
				$anchor = $base;
				$i      = 2;

				while ( isset( $slugs[ $anchor ] ) ) {
					$anchor = $base . '-' . $i++;
				}

				$heading->setAttribute( 'id', $anchor );
			}

			$slugs[ $anchor ] = true;

			$items[] = array(
				'level'  => 'h3' === strtolower( $heading->nodeName ) ? 3 : 2,
				'label'  => $label,
				'anchor' => $anchor,
			);
		}

		if ( $root ) {
			$html = '';

			foreach ( iterator_to_array( $root->childNodes ) as $child ) {
				$html .= $dom->saveHTML( $child );
			}
		}
	}

	$cache[ $post_id ] = array(
		'content' => $html,
		'items'   => $items,
	);

	return $cache[ $post_id ];
}

/**
 * The list of H2/H3 headings in a post, for the article table of contents.
 *
 * @param int $post_id Post ID.
 * @return array
 */
function alrenas_get_article_toc_items( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();

	return alrenas_get_article_toc_data( $post_id )['items'];
}

/**
 * A post's rendered content, with id="" attributes injected on H2/H3
 * headings that don't already have one, matching alrenas_get_article_toc_items().
 *
 * @param int $post_id Post ID.
 * @return string
 */
function alrenas_get_article_content_with_anchors( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();

	return alrenas_get_article_toc_data( $post_id )['content'];
}
