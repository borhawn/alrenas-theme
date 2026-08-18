<?php
/**
 * Small reusable presentation helpers.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alrenas_posted_on() {
	printf(
		'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

function alrenas_posted_by() {
	printf(
		'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}

