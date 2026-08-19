<?php
/**
 * "Related System" product picker for blog posts.
 *
 * Lets an editor pick which WooCommerce product a post is about/features.
 * Drives the "Related system" card in template-parts/article/content.php —
 * that card only renders when a product has actually been selected.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_RELATED_PRODUCT_META_KEY', '_alrenas_related_product_id' );

/**
 * Register the meta box on the post editor screen.
 */
function alrenas_register_related_product_meta_box() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	add_meta_box(
		'alrenas-related-product',
		esc_html__( 'Related System', 'alrenas' ),
		'alrenas_render_related_product_meta_box',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'alrenas_register_related_product_meta_box' );

/**
 * Render the product picker.
 *
 * @param WP_Post $post Current post.
 */
function alrenas_render_related_product_meta_box( $post ) {
	wp_nonce_field( 'alrenas_save_related_product', 'alrenas_related_product_nonce' );

	$selected = (int) get_post_meta( $post->ID, ALRENAS_RELATED_PRODUCT_META_KEY, true );

	$products = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<p>
		<label for="alrenas-related-product-select"><?php esc_html_e( 'Which product is this post about, or features?', 'alrenas' ); ?></label>
	</p>
	<select name="alrenas_related_product_id" id="alrenas-related-product-select" style="width:100%;">
		<option value="0"><?php esc_html_e( '— None —', 'alrenas' ); ?></option>
		<?php foreach ( $products as $product_post ) : ?>
			<option value="<?php echo esc_attr( $product_post->ID ); ?>" <?php selected( $selected, $product_post->ID ); ?>>
				<?php echo esc_html( $product_post->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description">
		<?php esc_html_e( 'Shown as "Related system" on the article. Leave as None to hide that card.', 'alrenas' ); ?>
	</p>
	<?php
}

/**
 * Save the selected product on post save.
 *
 * @param int $post_id Post ID.
 */
function alrenas_save_related_product_meta( $post_id ) {
	if ( ! isset( $_POST['alrenas_related_product_nonce'] )
		|| ! wp_verify_nonce( wp_unslash( $_POST['alrenas_related_product_nonce'] ), 'alrenas_save_related_product' )
	) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'post' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$product_id = isset( $_POST['alrenas_related_product_id'] ) ? absint( $_POST['alrenas_related_product_id'] ) : 0;

	if ( $product_id ) {
		update_post_meta( $post_id, ALRENAS_RELATED_PRODUCT_META_KEY, $product_id );
	} else {
		delete_post_meta( $post_id, ALRENAS_RELATED_PRODUCT_META_KEY );
	}
}
add_action( 'save_post', 'alrenas_save_related_product_meta' );

/**
 * Get the product selected for a post's "Related system" card.
 *
 * @param int $post_id Post ID.
 * @return WC_Product|null
 */
function alrenas_get_related_product( $post_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}

	$product_id = (int) get_post_meta( $post_id, ALRENAS_RELATED_PRODUCT_META_KEY, true );

	if ( ! $product_id || 'publish' !== get_post_status( $product_id ) ) {
		return null;
	}

	$product = wc_get_product( $product_id );

	return $product instanceof WC_Product ? $product : null;
}
