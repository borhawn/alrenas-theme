<?php
/**
 * "Featured" post flag.
 *
 * Featured posts sort first (by publish date among themselves) in the
 * homepage's latest-posts section and on the main blog index, ahead of
 * everything else, which still sorts by date as before.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_FEATURED_POST_META_KEY', '_alrenas_featured' );

/**
 * Register the meta box on the post editor screen.
 */
function alrenas_register_featured_post_meta_box() {
	add_meta_box(
		'alrenas-featured-post',
		esc_html__( 'Featured', 'alrenas' ),
		'alrenas_render_featured_post_meta_box',
		'post',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'alrenas_register_featured_post_meta_box' );

/**
 * Render the featured checkbox.
 *
 * @param WP_Post $post Current post.
 */
function alrenas_render_featured_post_meta_box( $post ) {
	wp_nonce_field( 'alrenas_save_featured_post', 'alrenas_featured_post_nonce' );

	$checked = (bool) get_post_meta( $post->ID, ALRENAS_FEATURED_POST_META_KEY, true );
	?>
	<label>
		<input type="checkbox" name="alrenas_featured_post" value="1" <?php checked( $checked ); ?>>
		<?php esc_html_e( 'Feature this post', 'alrenas' ); ?>
	</label>
	<p class="description">
		<?php esc_html_e( 'Featured posts appear first on the homepage and at the top of the blog archive.', 'alrenas' ); ?>
	</p>
	<?php
}

/**
 * Save the featured flag on post save.
 *
 * @param int $post_id Post ID.
 */
function alrenas_save_featured_post_meta( $post_id ) {
	if ( ! isset( $_POST['alrenas_featured_post_nonce'] )
		|| ! wp_verify_nonce( wp_unslash( $_POST['alrenas_featured_post_nonce'] ), 'alrenas_save_featured_post' )
	) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'post' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! empty( $_POST['alrenas_featured_post'] ) ) {
		update_post_meta( $post_id, ALRENAS_FEATURED_POST_META_KEY, '1' );
	} else {
		delete_post_meta( $post_id, ALRENAS_FEATURED_POST_META_KEY );
	}
}
add_action( 'save_post', 'alrenas_save_featured_post_meta' );

/**
 * Query args that sort featured posts first, then by date — for use by any
 * post-listing WP_Query. Merge into an existing args array.
 *
 * @return array
 */
function alrenas_featured_first_query_args() {
	return array(
		// A named meta_query clause (rather than the top-level meta_key +
		// orderby=meta_value_num shortcut) is what lets WordPress use a
		// LEFT JOIN here: every post matches either the EXISTS or the
		// NOT EXISTS branch, so nothing gets excluded -- ordering by the
		// clause name just ranks the EXISTS matches first.
		'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'relation'        => 'OR',
			'featured_clause' => array(
				'key'     => ALRENAS_FEATURED_POST_META_KEY,
				'compare' => 'EXISTS',
			),
			array(
				'key'     => ALRENAS_FEATURED_POST_META_KEY,
				'compare' => 'NOT EXISTS',
			),
		),
		'orderby'    => array(
			'featured_clause' => 'DESC',
			'date'            => 'DESC',
		),
	);
}

/**
 * Apply featured-first ordering to the main blog index query.
 *
 * @param WP_Query $query Main query.
 */
function alrenas_order_blog_index_featured_first( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
		return;
	}

	foreach ( alrenas_featured_first_query_args() as $key => $value ) {
		$query->set( $key, $value );
	}
}
add_action( 'pre_get_posts', 'alrenas_order_blog_index_featured_first' );
