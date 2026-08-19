<?php
/**
 * "Site Content" admin page for editable front-end text.
 *
 * Central place for admins to edit copy that lives in template-parts
 * without touching PHP. Stored as a single array option so new fields can
 * be added over time without new options/tables. Organized into tabs (one
 * per page/section) so it stays legible as more sections are added.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_SITE_CONTENT_OPTION', 'alrenas_site_content' );
define( 'ALRENAS_SITE_CONTENT_GROUP', 'alrenas_site_content_group' );
define( 'ALRENAS_HERO_SLIDE_COUNT', 5 );

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
 * The tabs shown on the Site Content page, in display order.
 *
 * Each tab is its own Settings API "page" slug, so do_settings_sections()
 * can render them separately while all fields still save through the same
 * option/group.
 *
 * @return array<string,string> slug => label
 */
function alrenas_site_content_tabs() {
	return array(
		'home-hero'     => esc_html__( 'Home — Hero', 'alrenas' ),
		'home-products' => esc_html__( 'Home — Products', 'alrenas' ),
		'footer'        => esc_html__( 'Footer', 'alrenas' ),
	);
}

/**
 * Register the admin menu page.
 */
function alrenas_site_content_menu() {
	$hook = add_menu_page(
		esc_html__( 'Site Content', 'alrenas' ),
		esc_html__( 'Site Content', 'alrenas' ),
		'edit_theme_options',
		'alrenas-site-content',
		'alrenas_render_site_content_page',
		'dashicons-edit-page',
		61
	);

	add_action(
		'admin_enqueue_scripts',
		function ( $current_hook ) use ( $hook ) {
			if ( $current_hook === $hook ) {
				alrenas_site_content_inline_assets();
			}
		}
	);
}
add_action( 'admin_menu', 'alrenas_site_content_menu' );

/**
 * Media picker + tab-switching JS/CSS for the Site Content page, inlined
 * (rather than a separate enqueued file) since it's a small amount of
 * behavior used only on this one admin screen.
 */
function alrenas_site_content_inline_assets() {
	wp_enqueue_media();
	add_action( 'admin_print_footer_scripts', 'alrenas_site_content_inline_footer' );
}

/**
 * Print the tab/media-picker markup + JS in the admin footer.
 */
function alrenas_site_content_inline_footer() {
	?>
	<style>
		.alrenas-content-tabs { margin: 20px 0 0; }
		.alrenas-content-tabs .nav-tab { cursor: pointer; }
		.alrenas-content-tab-panel { display: none; background: #fff; padding: 20px 20px 4px; border: 1px solid #c3c4c7; border-top: none; }
		.alrenas-content-tab-panel.is-active { display: block; }
		.alrenas-slide-fieldset { max-width: 620px; padding-bottom: 8px; }
		.alrenas-slide-fieldset .alrenas-field-label { display: block; font-weight: 600; margin-bottom: 4px; }
		.alrenas-slide-fieldset p { margin-bottom: 14px; }
		.alrenas-image-preview { display: block; width: 140px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #dcdcde; background: #f0f0f1; margin-bottom: 8px; }
		.alrenas-image-preview[hidden] { display: none; }
	</style>
	<script>
	( function () {
		document.addEventListener( 'DOMContentLoaded', function () {
			var tabs = document.querySelectorAll( '.alrenas-content-tabs .nav-tab' );
			var panels = document.querySelectorAll( '.alrenas-content-tab-panel' );

			function activate( slug ) {
				tabs.forEach( function ( tab ) {
					tab.classList.toggle( 'nav-tab-active', tab.dataset.tab === slug );
				} );
				panels.forEach( function ( panel ) {
					panel.classList.toggle( 'is-active', panel.dataset.tab === slug );
				} );

				if ( history.replaceState ) {
					history.replaceState( null, '', '#' + slug );
				}
			}

			tabs.forEach( function ( tab ) {
				tab.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					activate( tab.dataset.tab );
				} );
			} );

			var initial = window.location.hash ? window.location.hash.replace( '#', '' ) : '';
			if ( ! initial || ! document.querySelector( '.alrenas-content-tab-panel[data-tab="' + initial + '"]' ) ) {
				initial = tabs.length ? tabs[0].dataset.tab : '';
			}
			if ( initial ) {
				activate( initial );
			}

			document.querySelectorAll( '.alrenas-media-picker' ).forEach( function ( button ) {
				button.addEventListener( 'click', function ( e ) {
					e.preventDefault();

					var wrap    = button.closest( '.alrenas-image-field' );
					var input   = wrap.querySelector( 'input[type="hidden"]' );
					var preview = wrap.querySelector( '.alrenas-image-preview' );
					var remove  = wrap.querySelector( '.alrenas-media-remove' );

					var frame = wp.media( {
						title: button.dataset.title || 'Select image',
						button: { text: 'Use this image' },
						multiple: false,
					} );

					frame.on( 'select', function () {
						var attachment = frame.state().get( 'selection' ).first().toJSON();
						input.value = attachment.id;
						preview.src = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
						preview.hidden = false;
						if ( remove ) {
							remove.hidden = false;
						}
					} );

					frame.open();
				} );
			} );

			document.querySelectorAll( '.alrenas-media-remove' ).forEach( function ( button ) {
				button.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					var wrap    = button.closest( '.alrenas-image-field' );
					var input   = wrap.querySelector( 'input[type="hidden"]' );
					var preview = wrap.querySelector( '.alrenas-image-preview' );
					input.value = '';
					preview.hidden = true;
					button.hidden = true;
				} );
			} );
		} );
	} )();
	</script>
	<?php
}

/**
 * Register settings, sections, and fields.
 */
function alrenas_site_content_settings() {
	register_setting(
		ALRENAS_SITE_CONTENT_GROUP,
		ALRENAS_SITE_CONTENT_OPTION,
		'alrenas_sanitize_site_content'
	);

	// --- Home — Hero -------------------------------------------------
	add_settings_section( 'alrenas_hero_text', esc_html__( 'Hero text', 'alrenas' ), '__return_false', 'alrenas-content-home-hero' );

	$hero_text_fields = array(
		'hero_eyebrow'            => array(
			'label'       => esc_html__( 'Eyebrow (small label above headline)', 'alrenas' ),
			'placeholder' => esc_html__( 'Rehabilitation technology for clinical care', 'alrenas' ),
		),
		'hero_headline'           => array(
			'label'       => esc_html__( 'Headline', 'alrenas' ),
			'placeholder' => esc_html__( 'Helping patients move with more', 'alrenas' ),
		),
		'hero_headline_highlight' => array(
			'label'       => esc_html__( 'Headline highlight (shown in accent color, after the headline)', 'alrenas' ),
			'placeholder' => esc_html__( 'confidence.', 'alrenas' ),
		),
	);

	foreach ( $hero_text_fields as $key => $field ) {
		add_settings_field( $key, $field['label'], 'alrenas_field_text', 'alrenas-content-home-hero', 'alrenas_hero_text', array( 'key' => $key, 'placeholder' => $field['placeholder'] ) );
	}

	add_settings_field( 'hero_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-home-hero', 'alrenas_hero_text', array( 'key' => 'hero_lead', 'placeholder' => esc_html__( 'Advanced rehabilitation systems for balance assessment, physiotherapy and guided training — designed to support safer, measurable recovery.', 'alrenas' ) ) );

	add_settings_section( 'alrenas_hero_buttons', esc_html__( 'Hero buttons', 'alrenas' ), '__return_false', 'alrenas-content-home-hero' );

	add_settings_field( 'hero_primary_label', esc_html__( 'Primary button label', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-hero', 'alrenas_hero_buttons', array( 'key' => 'hero_primary_label', 'placeholder' => esc_html__( 'Explore Rehabilitation Devices', 'alrenas' ) ) );
	add_settings_field( 'hero_primary_url', esc_html__( 'Primary button URL', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-hero', 'alrenas_hero_buttons', array( 'key' => 'hero_primary_url', 'placeholder' => '#products' ) );
	add_settings_field( 'hero_secondary_label', esc_html__( 'Secondary button label', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-hero', 'alrenas_hero_buttons', array( 'key' => 'hero_secondary_label', 'placeholder' => esc_html__( 'Talk to Our Team', 'alrenas' ) ) );
	add_settings_field( 'hero_secondary_url', esc_html__( 'Secondary button URL', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-hero', 'alrenas_hero_buttons', array( 'key' => 'hero_secondary_url', 'placeholder' => '#contact' ) );

	add_settings_section( 'alrenas_hero_tags', esc_html__( 'Hero tags', 'alrenas' ), '__return_false', 'alrenas-content-home-hero' );
	add_settings_field( 'hero_tags', esc_html__( 'Clinical fields (comma-separated)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-hero', 'alrenas_hero_tags', array( 'key' => 'hero_tags', 'placeholder' => esc_html__( 'Physiotherapy, Neurological Rehab, Orthopedic Rehab, Geriatrics', 'alrenas' ), 'wide' => true ) );

	add_settings_section( 'alrenas_hero_slides', esc_html__( 'Hero image slides', 'alrenas' ), 'alrenas_section_hero_slides_intro', 'alrenas-content-home-hero' );

	for ( $i = 1; $i <= ALRENAS_HERO_SLIDE_COUNT; $i++ ) {
		add_settings_field(
			'hero_slide_' . $i,
			sprintf(
				/* translators: %d: slide slot number. */
				esc_html__( 'Slide %d', 'alrenas' ),
				$i
			),
			'alrenas_field_hero_slide',
			'alrenas-content-home-hero',
			'alrenas_hero_slides',
			array( 'index' => $i )
		);
	}

	// --- Home — Products -----------------------------------------------
	add_settings_section( 'alrenas_site_content_home_products', esc_html__( 'Products section', 'alrenas' ), 'alrenas_section_home_products_intro', 'alrenas-content-home-products' );

	add_settings_field( 'home_products_eyebrow', esc_html__( 'Subtitle (above heading)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-products', 'alrenas_site_content_home_products', array( 'key' => 'home_products_eyebrow', 'placeholder' => esc_html__( 'Rehabilitation systems', 'alrenas' ) ) );

	for ( $i = 1; $i <= 3; $i++ ) {
		add_settings_field(
			'home_product_' . $i,
			sprintf(
				/* translators: %d: product slot number (1-3). */
				esc_html__( 'Product %d', 'alrenas' ),
				$i
			),
			'alrenas_field_home_product',
			'alrenas-content-home-products',
			'alrenas_site_content_home_products',
			array( 'index' => $i )
		);
	}

	// --- Footer ----------------------------------------------------------
	add_settings_section( 'alrenas_site_content_footer', esc_html__( 'Footer', 'alrenas' ), '__return_false', 'alrenas-content-footer' );
	add_settings_field( 'footer_description', esc_html__( 'Footer description', 'alrenas' ), 'alrenas_field_footer_description', 'alrenas-content-footer', 'alrenas_site_content_footer' );
}
add_action( 'admin_init', 'alrenas_site_content_settings' );

/**
 * Generic single-line text field.
 *
 * @param array $args Contains 'key', 'placeholder', optionally 'wide'.
 */
function alrenas_field_text( $args ) {
	$value = alrenas_get_site_content( $args['key'], '' );
	?>
	<input
		type="text"
		name="<?php echo esc_attr( ALRENAS_SITE_CONTENT_OPTION ); ?>[<?php echo esc_attr( $args['key'] ); ?>]"
		value="<?php echo esc_attr( $value ); ?>"
		placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
		class="<?php echo ! empty( $args['wide'] ) ? 'large-text' : 'regular-text'; ?>"
	>
	<?php
}

/**
 * Generic textarea field.
 *
 * @param array $args Contains 'key', 'placeholder'.
 */
function alrenas_field_textarea( $args ) {
	$value = alrenas_get_site_content( $args['key'], '' );
	?>
	<textarea
		name="<?php echo esc_attr( ALRENAS_SITE_CONTENT_OPTION ); ?>[<?php echo esc_attr( $args['key'] ); ?>]"
		rows="3"
		class="large-text"
		placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
	><?php echo esc_textarea( $value ); ?></textarea>
	<?php
}

/**
 * Intro text for the hero slides section.
 */
function alrenas_section_hero_slides_intro() {
	echo '<p>' . esc_html__( 'The left/right image on the homepage hero. Fill in at least one slide. With two or more filled in, they automatically rotate with a smooth crossfade every few seconds.', 'alrenas' ) . '</p>';

	if ( ! function_exists( 'wc_get_products' ) ) {
		echo '<p><strong>' . esc_html__( 'WooCommerce is not active, so a related product cannot be selected yet.', 'alrenas' ) . '</strong></p>';
	}
}

/**
 * Render a product <select>, used by both the hero slides and the products section.
 *
 * @param string $name     Field name attribute.
 * @param int    $selected Currently selected product ID.
 */
function alrenas_render_product_select( $name, $selected ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		printf( '<input type="hidden" name="%s" value="%d">', esc_attr( $name ), (int) $selected );
		return;
	}
	?>
	<select name="<?php echo esc_attr( $name ); ?>" class="regular-text">
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
			<option value="<?php echo esc_attr( $product_post->ID ); ?>" <?php selected( $selected, $product_post->ID ); ?>>
				<?php echo esc_html( $product_post->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}

/**
 * Render one hero slide fieldset (image + caption + related product).
 *
 * @param array $args Contains 'index' (1-ALRENAS_HERO_SLIDE_COUNT).
 */
function alrenas_field_hero_slide( $args ) {
	$index  = (int) $args['index'];
	$slides = alrenas_get_site_content( 'hero_slides', array() );
	$slide  = isset( $slides[ $index ] ) ? $slides[ $index ] : array();

	$image_id        = isset( $slide['image_id'] ) ? (int) $slide['image_id'] : 0;
	$caption_title    = isset( $slide['caption_title'] ) ? $slide['caption_title'] : '';
	$caption_subtext  = isset( $slide['caption_subtext'] ) ? $slide['caption_subtext'] : '';
	$product_id       = isset( $slide['product_id'] ) ? (int) $slide['product_id'] : 0;

	$name       = ALRENAS_SITE_CONTENT_OPTION . '[hero_slides][' . $index . ']';
	$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<p class="alrenas-image-field">
			<span class="alrenas-field-label"><?php esc_html_e( 'Image', 'alrenas' ); ?></span>
			<img class="alrenas-image-preview" src="<?php echo esc_url( $image_url ); ?>" <?php echo $image_url ? '' : 'hidden'; ?>>
			<input type="hidden" name="<?php echo esc_attr( $name ); ?>[image_id]" value="<?php echo esc_attr( $image_id ); ?>">
			<button type="button" class="button alrenas-media-picker" data-title="<?php esc_attr_e( 'Select hero image', 'alrenas' ); ?>"><?php esc_html_e( 'Select image', 'alrenas' ); ?></button>
			<button type="button" class="button alrenas-media-remove" <?php echo $image_url ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'alrenas' ); ?></button>
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Caption title', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[caption_title]" value="<?php echo esc_attr( $caption_title ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Guided rehabilitation', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Caption subtext', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[caption_subtext]" value="<?php echo esc_attr( $caption_subtext ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'e.g. Assessment, training and real-time feedback', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Related product (shown in the floating card)', 'alrenas' ); ?></label>
			<?php alrenas_render_product_select( $name . '[product_id]', $product_id ); ?>
		</p>
	</fieldset>
	<hr>
	<?php
}

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
	<fieldset class="alrenas-slide-fieldset">
		<p>
			<span class="alrenas-field-label"><?php esc_html_e( 'Product', 'alrenas' ); ?></span>
			<?php alrenas_render_product_select( $name . '[product_id]', $product_id ); ?>
			<p class="description"><?php esc_html_e( 'Determines the link and thumbnail image used on the card.', 'alrenas' ); ?></p>
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Badge (on the image)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[badge]" value="<?php echo esc_attr( $badge ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Static balance', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Kicker (above title)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[kicker]" value="<?php echo esc_attr( $kicker ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Assessment + rehabilitation', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Description', 'alrenas' ); ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>[description]" rows="2" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Tags (comma-separated)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[tags]" value="<?php echo esc_attr( $tags ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'e.g. Fall-risk assessment, Balance training, Biofeedback', 'alrenas' ); ?>">
		</p>
	</fieldset>
	<hr>
	<?php
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

	$text_keys = array(
		'hero_eyebrow',
		'hero_headline',
		'hero_headline_highlight',
		'hero_primary_label',
		'hero_primary_url',
		'hero_secondary_label',
		'hero_secondary_url',
		'hero_tags',
		'home_products_eyebrow',
	);

	foreach ( $text_keys as $key ) {
		if ( ! isset( $input[ $key ] ) ) {
			$output[ $key ] = '';
			continue;
		}

		$value = wp_unslash( $input[ $key ] );

		$output[ $key ] = ( 'hero_primary_url' === $key || 'hero_secondary_url' === $key )
			? esc_url_raw( $value )
			: sanitize_text_field( $value );
	}

	$output['hero_lead'] = isset( $input['hero_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['hero_lead'] ) )
		: '';

	$output['hero_slides'] = array();

	if ( ! empty( $input['hero_slides'] ) && is_array( $input['hero_slides'] ) ) {
		foreach ( $input['hero_slides'] as $index => $slide ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_HERO_SLIDE_COUNT ) {
				continue;
			}

			$output['hero_slides'][ $index ] = array(
				'image_id'        => isset( $slide['image_id'] ) ? absint( $slide['image_id'] ) : 0,
				'caption_title'   => isset( $slide['caption_title'] ) ? sanitize_text_field( wp_unslash( $slide['caption_title'] ) ) : '',
				'caption_subtext' => isset( $slide['caption_subtext'] ) ? sanitize_text_field( wp_unslash( $slide['caption_subtext'] ) ) : '',
				'product_id'      => isset( $slide['product_id'] ) ? absint( $slide['product_id'] ) : 0,
			);
		}
	}

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
 * Render the admin page.
 */
function alrenas_render_site_content_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$tabs = alrenas_site_content_tabs();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Site Content', 'alrenas' ); ?></h1>
		<p><?php esc_html_e( 'Edit text shown on the front end of the site, organized by page or section.', 'alrenas' ); ?></p>

		<h2 class="nav-tab-wrapper alrenas-content-tabs">
			<?php foreach ( $tabs as $slug => $label ) : ?>
				<a href="#<?php echo esc_attr( $slug ); ?>" class="nav-tab" data-tab="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</h2>

		<form action="options.php" method="post">
			<?php settings_fields( ALRENAS_SITE_CONTENT_GROUP ); ?>

			<?php foreach ( $tabs as $slug => $label ) : ?>
				<div class="alrenas-content-tab-panel" data-tab="<?php echo esc_attr( $slug ); ?>">
					<?php do_settings_sections( 'alrenas-content-' . $slug ); ?>
				</div>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
