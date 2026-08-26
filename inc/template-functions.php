<?php
/**
 * Theme integration helpers.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a logo <img> tag without width/height HTML attributes.
 *
 * wp_get_attachment_image() normally adds width/height attributes read from
 * attachment metadata. For SVGs, WordPress core often has no recorded
 * dimensions and emits width="0" height="0" instead — some browsers derive
 * a 0/0 intrinsic aspect-ratio from that, which can make the image render
 * at 0x0 even though CSS sets an explicit height, because "width: auto"
 * has no valid ratio to compute from. Sizing is fully CSS-driven here
 * (.brand-image), so the attributes are just omitted entirely.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $alt           Alt text.
 * @param string $class         CSS class.
 * @return string
 */
function alrenas_logo_image_html( $attachment_id, $alt, $class = 'brand-image' ) {
	$src = wp_get_attachment_image_url( $attachment_id, 'full' );

	if ( ! $src ) {
		return '';
	}

	return sprintf(
		'<img src="%s" class="%s" alt="%s" decoding="async">',
		esc_url( $src ),
		esc_attr( $class ),
		esc_attr( $alt )
	);
}

/**
 * Render an attachment as whichever tag its type needs: <video> for video
 * files (autoplaying, muted, looped — treated as an ambient clip, not a
 * player), <img> for everything else (photos, webp, svg).
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $class         CSS class for the rendered tag.
 * @return string
 */
function alrenas_render_media_html( $attachment_id, $class = '' ) {
	$attachment_id = (int) $attachment_id;

	if ( ! $attachment_id ) {
		return '';
	}

	$mime = get_post_mime_type( $attachment_id );

	if ( $mime && 0 === strpos( $mime, 'video/' ) ) {
		$src = wp_get_attachment_url( $attachment_id );

		if ( ! $src ) {
			return '';
		}

		return sprintf(
			'<video class="%s" src="%s" autoplay muted loop playsinline preload="metadata"></video>',
			esc_attr( $class ),
			esc_url( $src )
		);
	}

	$image = wp_get_attachment_image( $attachment_id, 'large', false, array( 'class' => $class ) );

	return $image ? $image : '';
}

/**
 * URL of the page using the "Contact" page template, resolved by template
 * rather than a hardcoded slug so it keeps working if the page is ever
 * renamed/moved. Falls back to /contact/ if no such page is found.
 *
 * @return string
 */
function alrenas_get_contact_page_url() {
	static $url = null;

	if ( null !== $url ) {
		return $url;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- small site, cached via static.
			'meta_value'     => 'page-contact.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'fields'         => 'ids',
		)
	);

	$url = $pages ? get_permalink( $pages[0] ) : home_url( '/contact/' );

	return $url;
}

/**
 * Read the items in the 'contact' nav menu location, typed by URL scheme
 * (tel: / mailto: / anything else = address), for places that need to
 * display phone/email/address individually rather than as a plain link
 * list -- e.g. the contact page's labeled detail cards.
 *
 * @return array<int,array{type:string,label:string,value:string,url:string}>
 */
function alrenas_get_contact_menu_entries() {
	static $entries = null;

	if ( null !== $entries ) {
		return $entries;
	}

	$entries  = array();
	$location = get_nav_menu_locations();

	if ( empty( $location['contact'] ) ) {
		return $entries;
	}

	$menu = wp_get_nav_menu_object( $location['contact'] );

	if ( ! $menu ) {
		return $entries;
	}

	$items = wp_get_nav_menu_items( $menu->term_id );

	if ( ! $items ) {
		return $entries;
	}

	foreach ( $items as $item ) {
		if ( 0 === strpos( $item->url, 'tel:' ) ) {
			$type  = 'tel';
			$label = __( 'Phone', 'alrenas' );
		} elseif ( 0 === strpos( $item->url, 'mailto:' ) ) {
			$type  = 'mailto';
			$label = __( 'Email', 'alrenas' );
		} else {
			$type  = 'address';
			$label = __( 'Address', 'alrenas' );
		}

		$entries[] = array(
			'type'  => $type,
			'label' => $label,
			'value' => $item->title,
			'url'   => $item->url,
		);
	}

	return $entries;
}

/**
 * First contact-menu entry of a given type ('tel', 'mailto', 'address').
 *
 * @param string $type Entry type.
 * @return array{type:string,label:string,value:string,url:string}|null
 */
function alrenas_get_contact_menu_entry( $type ) {
	foreach ( alrenas_get_contact_menu_entries() as $entry ) {
		if ( $entry['type'] === $type ) {
			return $entry;
		}
	}

	return null;
}

/**
 * Convert any common YouTube URL form (watch?v=, youtu.be/, embed/,
 * shorts/) into a privacy-enhanced embed URL, or '' if it isn't
 * recognizable as a YouTube URL.
 *
 * @param string $url Raw URL as entered by an admin.
 * @return string
 */
function alrenas_get_youtube_embed_url( $url ) {
	$url = trim( (string) $url );

	if ( ! $url ) {
		return '';
	}

	$video_id = '';

	if ( preg_match( '~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $matches ) ) {
		$video_id = $matches[1];
	} elseif ( preg_match( '~[?&]v=([A-Za-z0-9_-]{6,})~', $url, $matches ) ) {
		$video_id = $matches[1];
	} elseif ( preg_match( '~youtube\.com/(?:embed|shorts)/([A-Za-z0-9_-]{6,})~', $url, $matches ) ) {
		$video_id = $matches[1];
	}

	if ( ! $video_id ) {
		return '';
	}

	return 'https://www.youtube-nocookie.com/embed/' . $video_id;
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
