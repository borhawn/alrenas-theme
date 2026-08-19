<?php
/**
 * "Site Content" admin page for editable front-end text.
 *
 * Central place for admins to edit copy that lives in template-parts
 * without touching PHP. Stored as a single array option so new fields
 * can be added over time without new options/tables.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_SITE_CONTENT_OPTION', 'alrenas_site_content' );

/**
 * Get a single site content value.
 *
 * @param string $key     Field key.
 * @param string $default Fallback if unset.
 * @return string
 */
function alrenas_get_site_content( $key, $default = '' ) {
	$content = get_option( ALRENAS_SITE_CONTENT_OPTION, array() );

	if ( ! empty( $content[ $key ] ) ) {
		return $content[ $key ];
	}

	return $default;
}

/**
 * Register the admin menu page.
 */
function alrenas_site_content_menu() {
	add_menu_page(
		esc_html__( 'Site Content', 'alrenas' ),
		esc_html__( 'Site Content', 'alrenas' ),
		'edit_theme_options',
		'alrenas-site-content',
		'alrenas_render_site_content_page',
		'dashicons-edit-page',
		61
	);
}
add_action( 'admin_menu', 'alrenas_site_content_menu' );

/**
 * Register settings, sections, and fields.
 */
function alrenas_site_content_settings() {
	register_setting(
		'alrenas_site_content_group',
		ALRENAS_SITE_CONTENT_OPTION,
		'alrenas_sanitize_site_content'
	);

	add_settings_section(
		'alrenas_site_content_footer',
		esc_html__( 'Footer', 'alrenas' ),
		'__return_false',
		'alrenas-site-content'
	);

	add_settings_field(
		'footer_description',
		esc_html__( 'Footer description', 'alrenas' ),
		'alrenas_field_footer_description',
		'alrenas-site-content',
		'alrenas_site_content_footer'
	);

	add_settings_section(
		'alrenas_site_content_home_products',
		esc_html__( 'Home — Products Section', 'alrenas' ),
		'alrenas_section_home_products_intro',
		'alrenas-site-content'
	);

	add_settings_field(
		'home_products_eyebrow',
		esc_html__( 'Subtitle (above heading)', 'alrenas' ),
		'alrenas_field_home_products_eyebrow',
		'alrenas-site-content',
		'alrenas_site_content_home_products'
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		add_settings_field(
			'home_product_' . $i,
			sprintf(
				/* translators: %d: product slot number (1-3). */
				esc_html__( 'Product %d', 'alrenas' ),
				$i
			),
			'alrenas_field_home_product',
			'alrenas-site-content',
			'alrenas_site_content_home_products',
			array( 'index' => $i )
		);
	}
}
add_action( 'admin_init', 'alrenas_site_content_settings' );

/**
 * Intro text for the Home Products settings section.
 */
function alrenas_section_home_products_intro() {
	echo '<p>' . esc_html__( 'These three items appear as the "Rehabilitation systems" section on the homepage. Pick the product each one links to — its permalink and thumbnail are pulled from that product automatically — then edit the badge, kicker, description, and tags shown on the card.', 'alrenas' ) . '</p>';

	if ( ! function_exists( 'wc_get_products' ) ) {
		echo '<p><strong>' . esc_html__( 'WooCommerce is not active, so products cannot be selected yet.', 'alrenas' ) . '</strong></p>';
	}
}

/**
 * Render the eyebrow/subtitle field for the Home Products section.
 */
function alrenas_field_home_products_eyebrow() {
	$value = alrenas_get_site_content( 'home_products_eyebrow', esc_html__( 'Rehabilitation systems', 'alrenas' ) );
	?>
	<input
		type="text"
		name="<?php echo esc_attr( ALRENAS_SITE_CONTENT_OPTION ); ?>[home_products_eyebrow]"
		value="<?php echo esc_attr( $value ); ?>"
		class="regular-text"
	>
	<?php
}

/**
 * Render one product-slot fieldset (product picker + badge/kicker/description/tags).
 *
 * @param array $args Contains 'index' (1-3).
 */
function alrenas_field_home_product( $args ) {
	$index    = (int) $args['index'];
	$products = alrenas_get_site_content( 'home_products', array() );
	$slot     = isset( $products[ $index ] ) ? $products[ $index ] : array();

	$product_id  = isset( $slot['product_id'] ) ? (int) $slot['product_id'] : 0;
	$badge       = isset( $slot['badge'] ) ? $slot['badge'] : '';
	$kicker      = isset( $slot['kicker'] ) ? $slot['kicker'] : '';
	$description = isset( $slot['description'] ) ? $slot['description'] : '';
	$tags        = isset( $slot['tags'] ) ? $slot['tags'] : '';

	$name = ALRENAS_SITE_CONTENT_OPTION . '[home_products][' . $index . ']';
	?>
	<fieldset style="max-width:520px;">
		<p>
			<label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Product', 'alrenas' ); ?></label>
			<?php if ( function_exists( 'wc_get_products' ) ) : ?>
				<select name="<?php echo esc_attr( $name ); ?>[product_id]" class="regular-text">
					<option value="0"><?php esc_html_e( '— Select a product —', 'alrenas' ); ?></option>
					<?php
					$all_products = get_posts(
						array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
							'orderby'        => 'title',
							'order'          => 'ASC',
						)
					);
					foreach ( $all_products as $product_post ) :
						?>
						<option value="<?php echo esc_attr( $product_post->ID ); ?>" <?php selected( $product_id, $product_post->ID ); ?>>
							<?php echo esc_html( $product_post->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e( 'Determines the link and thumbnail image used on the card.', 'alrenas' ); ?></p>
			<?php else : ?>
				<input type="hidden" name="<?php echo esc_attr( $name ); ?>[product_id]" value="<?php echo esc_attr( $product_id ); ?>">
				<em><?php esc_html_e( 'Activate WooCommerce to choose a product.', 'alrenas' ); ?></em>
			<?php endif; ?>
		</p>
		<p>
			<label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Badge (on the image)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[badge]" value="<?php echo esc_attr( $badge ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Static balance', 'alrenas' ); ?>">
		</p>
		<p>
			<label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Kicker (above title)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[kicker]" value="<?php echo esc_attr( $kicker ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Assessment + rehabilitation', 'alrenas' ); ?>">
		</p>
		<p>
			<label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Description', 'alrenas' ); ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>[description]" rows="2" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
		<p>
			<label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Tags (comma-separated)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[tags]" value="<?php echo esc_attr( $tags ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'e.g. Fall-risk assessment, Balance training, Biofeedback', 'alrenas' ); ?>">
		</p>
	</fieldset>
	<hr>
	<?php
}

/**
 * Sanitize all fields on save.
 *
 * @param array $input Raw posted values.
 * @return array
 */
function alrenas_sanitize_site_content( $input ) {
	$output = get_option( ALRENAS_SITE_CONTENT_OPTION, array() );

	$output['footer_description'] = isset( $input['footer_description'] )
		? sanitize_textarea_field( wp_unslash( $input['footer_description'] ) )
		: '';

	$output['home_products_eyebrow'] = isset( $input['home_products_eyebrow'] )
		? sanitize_text_field( wp_unslash( $input['home_products_eyebrow'] ) )
		: '';

	$output['home_products'] = array();

	if ( ! empty( $input['home_products'] ) && is_array( $input['home_products'] ) ) {
		foreach ( $input['home_products'] as $index => $slot ) {
			$index = (int) $index;

			if ( $index < 1 || $index > 3 ) {
				continue;
			}

			$output['home_products'][ $index ] = array(
				'product_id'  => isset( $slot['product_id'] ) ? absint( $slot['product_id'] ) : 0,
				'badge'       => isset( $slot['badge'] ) ? sanitize_text_field( wp_unslash( $slot['badge'] ) ) : '',
				'kicker'      => isset( $slot['kicker'] ) ? sanitize_text_field( wp_unslash( $slot['kicker'] ) ) : '',
				'description' => isset( $slot['description'] ) ? sanitize_textarea_field( wp_unslash( $slot['description'] ) ) : '',
				'tags'        => isset( $slot['tags'] ) ? sanitize_text_field( wp_unslash( $slot['tags'] ) ) : '',
			);
		}
	}

	return $output;
}

/**
 * Render the footer description field.
 */
function alrenas_field_footer_description() {
	$value = alrenas_get_site_content( 'footer_description', get_bloginfo( 'description' ) );
	?>
	<textarea
		name="<?php echo esc_attr( ALRENAS_SITE_CONTENT_OPTION ); ?>[footer_description]"
		rows="3"
		class="large-text"
	><?php echo esc_textarea( $value ); ?></textarea>
	<p class="description">
		<?php esc_html_e( 'The short description shown beneath the footer logo on every page.', 'alrenas' ); ?>
	</p>
	<?php
}

/**
 * Render the admin page.
 */
function alrenas_render_site_content_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Site Content', 'alrenas' ); ?></h1>
		<p><?php esc_html_e( 'Edit text shown on the front end of the site, organized by page or section.', 'alrenas' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'alrenas_site_content_group' );
			do_settings_sections( 'alrenas-site-content' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
