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
define( 'ALRENAS_CARE_STEP_COUNT', 3 );
define( 'ALRENAS_DISCIPLINE_COUNT', 4 );
define( 'ALRENAS_STORY_POINT_COUNT', 3 );
define( 'ALRENAS_PRODUCTS_SYSTEM_COUNT', 3 );
define( 'ALRENAS_PRODUCTS_SELECTOR_COUNT', 3 );
define( 'ALRENAS_PRODUCTS_QUOTE_STEP_COUNT', 3 );
define( 'ALRENAS_CONTACT_OPTION_COUNT', 4 );
define( 'ALRENAS_ABOUT_MISSION_POINT_COUNT', 4 );
define( 'ALRENAS_ABOUT_VALUE_COUNT', 3 );
define( 'ALRENAS_ABOUT_STORY_POINT_COUNT', 3 );
define( 'ALRENAS_ABOUT_QUALITY_BADGE_COUNT', 4 );
define( 'ALRENAS_WHY_ITEM_COUNT', 4 );

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
		'home-hero'       => esc_html__( 'Home — Hero', 'alrenas' ),
		'home-products'   => esc_html__( 'Home — Products', 'alrenas' ),
		'home-process'    => esc_html__( 'Home — Process', 'alrenas' ),
		'home-discipline' => esc_html__( 'Home — Disciplines', 'alrenas' ),
		'home-story'      => esc_html__( 'Home — Clinical Story', 'alrenas' ),
		'home-care-strip' => esc_html__( 'Home — Care Strip', 'alrenas' ),
		'home-why'        => esc_html__( 'Home — Why Alrenas', 'alrenas' ),
		'products-page'   => esc_html__( 'Products Page', 'alrenas' ),
		'contact-page'    => esc_html__( 'Contact Page', 'alrenas' ),
		'about-page'      => esc_html__( 'About Page', 'alrenas' ),
		'site-cta'        => esc_html__( 'Site CTA', 'alrenas' ),
		'single-product'  => esc_html__( 'Single Product Page', 'alrenas' ),
		'footer'          => esc_html__( 'Footer', 'alrenas' ),
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
						if ( attachment.sizes && attachment.sizes.medium ) {
							preview.src = attachment.sizes.medium.url;
						} else if ( attachment.type === 'image' ) {
							preview.src = attachment.url;
						} else {
							preview.src = attachment.icon || attachment.url;
						}
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
	add_settings_field( 'home_products_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-products', 'alrenas_site_content_home_products', array( 'key' => 'home_products_heading', 'placeholder' => esc_html__( 'Purpose-built devices for balance, mobility and recovery.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'home_products_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-home-products', 'alrenas_site_content_home_products', array( 'key' => 'home_products_lead', 'placeholder' => esc_html__( 'Choose a system based on the patient group, treatment goals and level of support required.', 'alrenas' ) ) );

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

	// --- Home — Process ----------------------------------------------------
	add_settings_section( 'alrenas_process_text', esc_html__( 'Section text', 'alrenas' ), '__return_false', 'alrenas-content-home-process' );

	add_settings_field( 'process_eyebrow', esc_html__( 'Eyebrow (small label)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-process', 'alrenas_process_text', array( 'key' => 'process_eyebrow', 'placeholder' => esc_html__( 'Designed around rehabilitation', 'alrenas' ) ) );
	add_settings_field( 'process_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-process', 'alrenas_process_text', array( 'key' => 'process_heading', 'placeholder' => esc_html__( 'From assessment to progress, one clearer path to recovery.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'process_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-home-process', 'alrenas_process_text', array( 'key' => 'process_lead', 'placeholder' => esc_html__( 'Alrenas systems help healthcare professionals assess balance objectively, guide patients through targeted rehabilitation exercises and follow progress over time.', 'alrenas' ) ) );
	add_settings_field( 'process_link_label', esc_html__( 'Link label', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-process', 'alrenas_process_text', array( 'key' => 'process_link_label', 'placeholder' => esc_html__( 'See the systems', 'alrenas' ) ) );
	add_settings_field( 'process_link_url', esc_html__( 'Link URL', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-process', 'alrenas_process_text', array( 'key' => 'process_link_url', 'placeholder' => '#products' ) );

	add_settings_section( 'alrenas_process_steps', esc_html__( 'Boxes', 'alrenas' ), 'alrenas_section_process_steps_intro', 'alrenas-content-home-process' );

	for ( $i = 1; $i <= ALRENAS_CARE_STEP_COUNT; $i++ ) {
		add_settings_field(
			'care_step_' . $i,
			sprintf(
				/* translators: %d: box slot number. */
				esc_html__( 'Box %d', 'alrenas' ),
				$i
			),
			'alrenas_field_care_step',
			'alrenas-content-home-process',
			'alrenas_process_steps',
			array( 'index' => $i )
		);
	}

	// --- Home — Disciplines ------------------------------------------------
	add_settings_section( 'alrenas_discipline_text', esc_html__( 'Section text', 'alrenas' ), '__return_false', 'alrenas-content-home-discipline' );

	add_settings_field( 'discipline_eyebrow', esc_html__( 'Eyebrow (small label)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-discipline', 'alrenas_discipline_text', array( 'key' => 'discipline_eyebrow', 'placeholder' => esc_html__( 'Across rehabilitation specialties', 'alrenas' ) ) );
	add_settings_field( 'discipline_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-discipline', 'alrenas_discipline_text', array( 'key' => 'discipline_heading', 'placeholder' => esc_html__( 'Built for different patients. One goal: better movement.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'discipline_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-home-discipline', 'alrenas_discipline_text', array( 'key' => 'discipline_lead', 'placeholder' => esc_html__( 'Every specialty below draws on the same systems, configured around the assessments, exercises and progress tracking that patient group relies on most.', 'alrenas' ) ) );

	add_settings_section( 'alrenas_discipline_tabs', esc_html__( 'Tabs', 'alrenas' ), 'alrenas_section_discipline_tabs_intro', 'alrenas-content-home-discipline' );

	for ( $i = 1; $i <= ALRENAS_DISCIPLINE_COUNT; $i++ ) {
		add_settings_field(
			'discipline_tab_' . $i,
			sprintf(
				/* translators: %d: tab slot number. */
				esc_html__( 'Tab %d', 'alrenas' ),
				$i
			),
			'alrenas_field_discipline_tab',
			'alrenas-content-home-discipline',
			'alrenas_discipline_tabs',
			array( 'index' => $i )
		);
	}

	// --- Home — Clinical Story -----------------------------------------
	add_settings_section( 'alrenas_story_text', esc_html__( 'Section text', 'alrenas' ), '__return_false', 'alrenas-content-home-story' );

	add_settings_field( 'story_eyebrow', esc_html__( 'Eyebrow (small label)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-story', 'alrenas_story_text', array( 'key' => 'story_eyebrow', 'placeholder' => esc_html__( 'For real clinical environments', 'alrenas' ) ) );
	add_settings_field( 'story_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-story', 'alrenas_story_text', array( 'key' => 'story_heading', 'placeholder' => esc_html__( 'Technology should support therapy, not get in the way of it.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'story_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-home-story', 'alrenas_story_text', array( 'key' => 'story_lead', 'placeholder' => esc_html__( 'Every interaction is built around the practical needs of rehabilitation: patient safety, adjustable support, clear feedback and repeatable clinical assessment.', 'alrenas' ) ) );

	add_settings_section( 'alrenas_story_images', esc_html__( 'Images', 'alrenas' ), '__return_false', 'alrenas-content-home-story' );

	add_settings_field( 'story_main_image', esc_html__( 'Main image', 'alrenas' ), 'alrenas_field_story_image', 'alrenas-content-home-story', 'alrenas_story_images', array( 'key' => 'story_main_image_id', 'title' => esc_html__( 'Select main image', 'alrenas' ) ) );
	add_settings_field( 'story_small_image', esc_html__( 'Small overlapping image', 'alrenas' ), 'alrenas_field_story_image', 'alrenas-content-home-story', 'alrenas_story_images', array( 'key' => 'story_small_image_id', 'title' => esc_html__( 'Select small image', 'alrenas' ) ) );

	add_settings_section( 'alrenas_story_points', esc_html__( 'Points', 'alrenas' ), '__return_false', 'alrenas-content-home-story' );

	for ( $i = 1; $i <= ALRENAS_STORY_POINT_COUNT; $i++ ) {
		add_settings_field(
			'story_point_' . $i,
			sprintf(
				/* translators: %d: point slot number. */
				esc_html__( 'Point %d', 'alrenas' ),
				$i
			),
			'alrenas_field_story_point',
			'alrenas-content-home-story',
			'alrenas_story_points',
			array( 'index' => $i )
		);
	}

	// --- Home — Care Strip -----------------------------------------------
	add_settings_section( 'alrenas_site_content_care_strip', esc_html__( 'Care strip', 'alrenas' ), '__return_false', 'alrenas-content-home-care-strip' );

	add_settings_field( 'care_strip_text', esc_html__( 'Intro text', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-care-strip', 'alrenas_site_content_care_strip', array( 'key' => 'care_strip_text', 'placeholder' => esc_html__( 'Supporting rehabilitation across the continuum of care', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'care_strip_tags', esc_html__( 'Tags (comma-separated)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-care-strip', 'alrenas_site_content_care_strip', array( 'key' => 'care_strip_tags', 'placeholder' => esc_html__( 'Balance rehabilitation, Fall-risk assessment, Postural control, Mobility training, Muscle strengthening', 'alrenas' ), 'wide' => true ) );

	// --- Home -- Why Alrenas ------------------------------------------------
	add_settings_section( 'alrenas_why_text', esc_html__( 'Section text', 'alrenas' ), '__return_false', 'alrenas-content-home-why' );

	add_settings_field( 'why_eyebrow', esc_html__( 'Eyebrow (small label)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-why', 'alrenas_why_text', array( 'key' => 'why_eyebrow', 'placeholder' => esc_html__( 'Why Alrenas', 'alrenas' ) ) );
	add_settings_field( 'why_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-home-why', 'alrenas_why_text', array( 'key' => 'why_heading', 'placeholder' => esc_html__( 'A rehabilitation technology partner for clinical professionals.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'why_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-home-why', 'alrenas_why_text', array( 'key' => 'why_lead', 'placeholder' => esc_html__( 'Alrenas develops balance assessment and rehabilitation systems with a focus on practical clinical use, patient-specific progression and measurable information.', 'alrenas' ) ) );

	add_settings_section( 'alrenas_why_items', esc_html__( 'Reasons list', 'alrenas' ), '__return_false', 'alrenas-content-home-why' );

	for ( $i = 1; $i <= ALRENAS_WHY_ITEM_COUNT; $i++ ) {
		add_settings_field(
			'why_item_' . $i,
			sprintf( /* translators: %d: reason slot number. */ esc_html__( 'Reason %d', 'alrenas' ), $i ),
			'alrenas_field_repeater_item',
			'alrenas-content-home-why',
			'alrenas_why_items',
			array(
				'index'      => $i,
				'option_key' => 'why_items',
				'fields'     => array(
					'title'       => array( 'label' => esc_html__( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. Safety-Focused Rehabilitation', 'alrenas' ) ),
					'description' => array( 'label' => esc_html__( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => esc_html__( 'e.g. Supportive structures, adjustable configurations and controlled progression help professionals create appropriate environments for supervised balance rehabilitation.', 'alrenas' ) ),
				),
			)
		);
	}

	// --- Products Page -----------------------------------------------------
	add_settings_section( 'alrenas_products_hero', esc_html__( 'Hero', 'alrenas' ), '__return_false', 'alrenas-content-products-page' );

	add_settings_field( 'products_hero_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_eyebrow', 'placeholder' => esc_html__( 'Rehabilitation systems', 'alrenas' ) ) );
	add_settings_field( 'products_hero_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_heading', 'placeholder' => esc_html__( 'Technology shaped around the way recovery happens.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'products_hero_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_lead', 'placeholder' => esc_html__( 'Clinical systems for balance assessment, guided physiotherapy and supported mobility — selected according to the patient, treatment goal and level of assistance required.', 'alrenas' ) ) );
	add_settings_field( 'products_hero_primary_label', esc_html__( 'Primary button label (links to the systems section)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_primary_label', 'placeholder' => esc_html__( 'Explore Systems', 'alrenas' ) ) );
	add_settings_field( 'products_hero_secondary_label', esc_html__( 'Secondary button label (links to Contact)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_secondary_label', 'placeholder' => esc_html__( 'Get Product Guidance', 'alrenas' ) ) );
	add_settings_field( 'products_hero_main_product_id', esc_html__( 'Large hero image (product)', 'alrenas' ), 'alrenas_field_products_hero_image', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_main_product_id' ) );
	add_settings_field( 'products_hero_mini_product_id', esc_html__( 'Small overlapping hero image (product)', 'alrenas' ), 'alrenas_field_products_hero_image', 'alrenas-content-products-page', 'alrenas_products_hero', array( 'key' => 'products_hero_mini_product_id' ) );

	add_settings_section( 'alrenas_products_intro', esc_html__( 'Introduction', 'alrenas' ), '__return_false', 'alrenas-content-products-page' );

	add_settings_field( 'products_intro_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_intro', array( 'key' => 'products_intro_eyebrow', 'placeholder' => esc_html__( 'Not a catalogue of commodities', 'alrenas' ) ) );
	add_settings_field( 'products_intro_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_intro', array( 'key' => 'products_intro_heading', 'placeholder' => esc_html__( 'Choose by clinical need, not by a price tag.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'products_intro_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-products-page', 'alrenas_products_intro', array( 'key' => 'products_intro_lead', 'placeholder' => esc_html__( 'Alrenas systems are configured for professional rehabilitation environments. Instead of fixed online pricing, we discuss your facility, patient population, training requirements and support needs before preparing a quotation.', 'alrenas' ) ) );
	add_settings_field( 'products_intro_link_label', esc_html__( 'Link label (links to Contact)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_intro', array( 'key' => 'products_intro_link_label', 'placeholder' => esc_html__( 'Discuss your requirements', 'alrenas' ) ) );

	add_settings_section( 'alrenas_products_systems', esc_html__( 'Systems section', 'alrenas' ), 'alrenas_section_products_systems_intro', 'alrenas-content-products-page' );

	add_settings_field( 'products_systems_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_systems', array( 'key' => 'products_systems_eyebrow', 'placeholder' => esc_html__( 'Rehabilitation pathways', 'alrenas' ) ) );
	add_settings_field( 'products_systems_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_systems', array( 'key' => 'products_systems_heading', 'placeholder' => esc_html__( 'From objective assessment to supported standing.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'products_systems_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-products-page', 'alrenas_products_systems', array( 'key' => 'products_systems_lead', 'placeholder' => esc_html__( 'Each system answers a different clinical need while sharing a focus on safe progression, feedback and measurable rehabilitation.', 'alrenas' ) ) );

	for ( $i = 1; $i <= ALRENAS_PRODUCTS_SYSTEM_COUNT; $i++ ) {
		add_settings_field(
			'product_capabilities_' . $i,
			sprintf(
				/* translators: %d: system slot number (matches Home — Products slot 1-3). */
				esc_html__( 'System %d capabilities', 'alrenas' ),
				$i
			),
			'alrenas_field_product_capabilities',
			'alrenas-content-products-page',
			'alrenas_products_systems',
			array( 'index' => $i )
		);
	}

	add_settings_section( 'alrenas_products_selector', esc_html__( 'Selector section', 'alrenas' ), '__return_false', 'alrenas-content-products-page' );

	add_settings_field( 'products_selector_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_selector', array( 'key' => 'products_selector_eyebrow', 'placeholder' => esc_html__( 'A simple starting point', 'alrenas' ) ) );
	add_settings_field( 'products_selector_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_selector', array( 'key' => 'products_selector_heading', 'placeholder' => esc_html__( 'Which rehabilitation need are you addressing?', 'alrenas' ), 'wide' => true ) );

	for ( $i = 1; $i <= ALRENAS_PRODUCTS_SELECTOR_COUNT; $i++ ) {
		add_settings_field(
			'selector_item_' . $i,
			sprintf(
				/* translators: %d: selector item slot number. */
				esc_html__( 'Item %d', 'alrenas' ),
				$i
			),
			'alrenas_field_selector_item',
			'alrenas-content-products-page',
			'alrenas_products_selector',
			array( 'index' => $i )
		);
	}

	add_settings_section( 'alrenas_products_quote', esc_html__( 'Quotation process section', 'alrenas' ), '__return_false', 'alrenas-content-products-page' );

	add_settings_field( 'products_quote_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_quote', array( 'key' => 'products_quote_eyebrow', 'placeholder' => esc_html__( 'How quotations work', 'alrenas' ) ) );
	add_settings_field( 'products_quote_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-products-page', 'alrenas_products_quote', array( 'key' => 'products_quote_heading', 'placeholder' => esc_html__( 'A configuration based on your clinical environment.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'products_quote_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-products-page', 'alrenas_products_quote', array( 'key' => 'products_quote_lead', 'placeholder' => esc_html__( 'Medical equipment is rarely a one-size-fits-all purchase. We use a short consultation to understand what you actually need before pricing the system.', 'alrenas' ) ) );

	for ( $i = 1; $i <= ALRENAS_PRODUCTS_QUOTE_STEP_COUNT; $i++ ) {
		add_settings_field(
			'quote_step_' . $i,
			sprintf(
				/* translators: %d: quote step slot number. */
				esc_html__( 'Step %d', 'alrenas' ),
				$i
			),
			'alrenas_field_quote_step',
			'alrenas-content-products-page',
			'alrenas_products_quote',
			array( 'index' => $i )
		);
	}

	// --- Contact Page --------------------------------------------------
	add_settings_section( 'alrenas_contact_hero', esc_html__( 'Hero', 'alrenas' ), '__return_false', 'alrenas-content-contact-page' );

	add_settings_field( 'contact_hero_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-contact-page', 'alrenas_contact_hero', array( 'key' => 'contact_hero_eyebrow', 'placeholder' => esc_html__( 'Contact Alrenas', 'alrenas' ) ) );
	add_settings_field( 'contact_hero_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-contact-page', 'alrenas_contact_hero', array( 'key' => 'contact_hero_heading', 'placeholder' => esc_html__( 'Start with the rehabilitation need.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'contact_hero_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-contact-page', 'alrenas_contact_hero', array( 'key' => 'contact_hero_lead', 'placeholder' => esc_html__( 'Whether you are evaluating a device for a hospital, physiotherapy clinic, rehabilitation center or research project, tell us what you need to achieve. We\'ll help you take the next step.', 'alrenas' ) ) );
	add_settings_field( 'contact_hero_primary_label', esc_html__( 'Primary button label (links to the form below)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-contact-page', 'alrenas_contact_hero', array( 'key' => 'contact_hero_primary_label', 'placeholder' => esc_html__( 'Send an Inquiry', 'alrenas' ) ) );

	add_settings_section( 'alrenas_contact_options', esc_html__( 'Inquiry options', 'alrenas' ), 'alrenas_section_contact_options_intro', 'alrenas-content-contact-page' );

	for ( $i = 1; $i <= ALRENAS_CONTACT_OPTION_COUNT; $i++ ) {
		add_settings_field(
			'contact_option_' . $i,
			sprintf( /* translators: %d: option slot number. */ esc_html__( 'Option %d', 'alrenas' ), $i ),
			'alrenas_field_repeater_item',
			'alrenas-content-contact-page',
			'alrenas_contact_options',
			array(
				'index'      => $i,
				'option_key' => 'contact_options_items',
				'fields'     => array(
					'title'       => array( 'label' => esc_html__( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. Product guidance', 'alrenas' ) ),
					'description' => array( 'label' => esc_html__( 'Description', 'alrenas' ), 'type' => 'text', 'wide' => true, 'placeholder' => esc_html__( 'e.g. Help choosing the right rehabilitation system.', 'alrenas' ) ),
				),
			)
		);
	}

	add_settings_section( 'alrenas_contact_details', esc_html__( 'Details panel', 'alrenas' ), '__return_false', 'alrenas-content-contact-page' );

	add_settings_field( 'contact_details_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-contact-page', 'alrenas_contact_details', array( 'key' => 'contact_details_eyebrow', 'placeholder' => esc_html__( 'Talk to our team', 'alrenas' ) ) );
	add_settings_field( 'contact_details_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-contact-page', 'alrenas_contact_details', array( 'key' => 'contact_details_heading', 'placeholder' => esc_html__( 'We\'ll route your request to the right person.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'contact_details_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-contact-page', 'alrenas_contact_details', array( 'key' => 'contact_details_lead', 'placeholder' => esc_html__( 'Share the clinical or practical context rather than trying to fit your question into a generic sales form.', 'alrenas' ) ) );
	add_settings_field( 'contact_map_note', esc_html__( 'Note (below the contact details)', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-contact-page', 'alrenas_contact_details', array( 'key' => 'contact_map_note', 'placeholder' => esc_html__( 'For urgent technical assistance, calling the team directly is the fastest route.', 'alrenas' ) ) );

	add_settings_section( 'alrenas_contact_form_head', esc_html__( 'Form card', 'alrenas' ), '__return_false', 'alrenas-content-contact-page' );

	add_settings_field( 'contact_form_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-contact-page', 'alrenas_contact_form_head', array( 'key' => 'contact_form_heading', 'placeholder' => esc_html__( 'Tell us how we can help.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'contact_form_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-contact-page', 'alrenas_contact_form_head', array( 'key' => 'contact_form_lead', 'placeholder' => esc_html__( 'For quotations and demos, include the facility type, product of interest and intended clinical use where possible.', 'alrenas' ) ) );

	// --- About Page ------------------------------------------------------
	add_settings_section( 'alrenas_about_hero', esc_html__( 'Hero', 'alrenas' ), '__return_false', 'alrenas-content-about-page' );

	add_settings_field( 'about_hero_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_hero', array( 'key' => 'about_hero_eyebrow', 'placeholder' => esc_html__( 'About Alrenas', 'alrenas' ) ) );
	add_settings_field( 'about_hero_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_hero', array( 'key' => 'about_hero_heading', 'placeholder' => esc_html__( 'Rehabilitation technology with recovery at the center.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'about_hero_lead', esc_html__( 'Lead paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_hero', array( 'key' => 'about_hero_lead', 'placeholder' => esc_html__( 'We develop rehabilitation systems to help healthcare professionals assess movement, guide therapy and support patients toward greater stability, mobility and independence.', 'alrenas' ) ) );
	add_settings_field( 'about_hero_primary_label', esc_html__( 'Primary button label (links to Products)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_hero', array( 'key' => 'about_hero_primary_label', 'placeholder' => esc_html__( 'Explore Our Systems', 'alrenas' ) ) );
	add_settings_field( 'about_hero_secondary_label', esc_html__( 'Secondary button label (links to Contact)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_hero', array( 'key' => 'about_hero_secondary_label', 'placeholder' => esc_html__( 'Meet Our Team', 'alrenas' ) ) );
	add_settings_field( 'about_hero_small_image', esc_html__( 'Small overlapping image', 'alrenas' ), 'alrenas_field_story_image', 'alrenas-content-about-page', 'alrenas_about_hero', array( 'key' => 'about_hero_small_image_id', 'title' => esc_html__( 'Select small image', 'alrenas' ) ) );

	add_settings_section( 'alrenas_about_mission', esc_html__( 'Mission', 'alrenas' ), '__return_false', 'alrenas-content-about-page' );

	add_settings_field( 'about_mission_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_mission', array( 'key' => 'about_mission_eyebrow', 'placeholder' => esc_html__( 'Redefining recovery', 'alrenas' ) ) );
	add_settings_field( 'about_mission_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_mission', array( 'key' => 'about_mission_heading', 'placeholder' => esc_html__( 'Designed to support the people inside the rehabilitation process.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'about_mission_paragraph_1', esc_html__( 'Paragraph 1', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_mission', array( 'key' => 'about_mission_paragraph_1', 'placeholder' => esc_html__( 'Alrenas specializes in rehabilitation equipment and physiotherapy devices for healthcare professionals, patients and rehabilitation organizations. Our goal is straightforward: create practical systems that make assessment clearer, therapy more adaptable and patient progress easier to follow.', 'alrenas' ) ) );
	add_settings_field( 'about_mission_paragraph_2', esc_html__( 'Paragraph 2', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_mission', array( 'key' => 'about_mission_paragraph_2', 'placeholder' => esc_html__( 'Technology matters, but it is never the point on its own. A rehabilitation device has to fit clinical workflows, feel safe to the patient and give the therapist useful information.', 'alrenas' ) ) );

	for ( $i = 1; $i <= ALRENAS_ABOUT_MISSION_POINT_COUNT; $i++ ) {
		add_settings_field(
			'about_mission_point_' . $i,
			sprintf( /* translators: %d: mission point slot number. */ esc_html__( 'Point %d', 'alrenas' ), $i ),
			'alrenas_field_repeater_item',
			'alrenas-content-about-page',
			'alrenas_about_mission',
			array(
				'index'      => $i,
				'option_key' => 'about_mission_points',
				'fields'     => array(
					'title'       => array( 'label' => esc_html__( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. For clinicians', 'alrenas' ) ),
					'description' => array( 'label' => esc_html__( 'Description', 'alrenas' ), 'type' => 'text', 'wide' => true, 'placeholder' => esc_html__( 'e.g. Objective assessment, adaptable training and clearer progress information.', 'alrenas' ) ),
				),
			)
		);
	}

	add_settings_section( 'alrenas_about_values', esc_html__( 'Values', 'alrenas' ), '__return_false', 'alrenas-content-about-page' );

	add_settings_field( 'about_values_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_values', array( 'key' => 'about_values_eyebrow', 'placeholder' => esc_html__( 'What guides our work', 'alrenas' ) ) );
	add_settings_field( 'about_values_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_values', array( 'key' => 'about_values_heading', 'placeholder' => esc_html__( 'Clinical value before technical spectacle.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'about_values_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_values', array( 'key' => 'about_values_lead', 'placeholder' => esc_html__( 'The best rehabilitation technology should quietly make care better. These principles shape how we think about products, software and support.', 'alrenas' ) ) );

	for ( $i = 1; $i <= ALRENAS_ABOUT_VALUE_COUNT; $i++ ) {
		add_settings_field(
			'about_value_' . $i,
			sprintf( /* translators: %d: value card slot number. */ esc_html__( 'Value %d', 'alrenas' ), $i ),
			'alrenas_field_repeater_item',
			'alrenas-content-about-page',
			'alrenas_about_values',
			array(
				'index'      => $i,
				'option_key' => 'about_value_cards',
				'fields'     => array(
					'title'       => array( 'label' => esc_html__( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. Safety and confidence', 'alrenas' ) ),
					'description' => array( 'label' => esc_html__( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => esc_html__( 'e.g. Patients may be working at the edge of their current ability...', 'alrenas' ) ),
				),
			)
		);
	}

	add_settings_section( 'alrenas_about_story', esc_html__( 'Story', 'alrenas' ), '__return_false', 'alrenas-content-about-page' );

	add_settings_field( 'about_story_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_story', array( 'key' => 'about_story_eyebrow', 'placeholder' => esc_html__( 'Your partner in rehabilitation', 'alrenas' ) ) );
	add_settings_field( 'about_story_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_story', array( 'key' => 'about_story_heading', 'placeholder' => esc_html__( 'Built for the full care environment, not just the device itself.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'about_story_paragraph_1', esc_html__( 'Paragraph 1', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_story', array( 'key' => 'about_story_paragraph_1', 'placeholder' => esc_html__( 'Our team brings together product development, technical knowledge and clinical perspectives to create solutions for physiotherapy, neurological and orthopedic rehabilitation, geriatrics and movement training.', 'alrenas' ) ) );
	add_settings_field( 'about_story_paragraph_2', esc_html__( 'Paragraph 2', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_story', array( 'key' => 'about_story_paragraph_2', 'placeholder' => esc_html__( 'We also support healthcare professionals with product guidance, training and ongoing communication so the system can be integrated into real clinical practice.', 'alrenas' ) ) );
	add_settings_field( 'about_story_image', esc_html__( 'Image', 'alrenas' ), 'alrenas_field_story_image', 'alrenas-content-about-page', 'alrenas_about_story', array( 'key' => 'about_story_image_id', 'title' => esc_html__( 'Select story image', 'alrenas' ) ) );

	for ( $i = 1; $i <= ALRENAS_ABOUT_STORY_POINT_COUNT; $i++ ) {
		add_settings_field(
			'about_story_point_' . $i,
			sprintf( /* translators: %d: story checklist item slot number. */ esc_html__( 'Checklist item %d', 'alrenas' ), $i ),
			'alrenas_field_repeater_item',
			'alrenas-content-about-page',
			'alrenas_about_story',
			array(
				'index'      => $i,
				'option_key' => 'about_story_points',
				'fields'     => array(
					'title'       => array( 'label' => esc_html__( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. Professional support', 'alrenas' ) ),
					'description' => array( 'label' => esc_html__( 'Description', 'alrenas' ), 'type' => 'text', 'wide' => true, 'placeholder' => esc_html__( 'e.g. Product guidance and training for healthcare teams.', 'alrenas' ) ),
				),
			)
		);
	}

	add_settings_section( 'alrenas_about_quality', esc_html__( 'Quality band', 'alrenas' ), '__return_false', 'alrenas-content-about-page' );

	add_settings_field( 'about_quality_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_quality', array( 'key' => 'about_quality_eyebrow', 'placeholder' => esc_html__( 'Quality and responsibility', 'alrenas' ) ) );
	add_settings_field( 'about_quality_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-about-page', 'alrenas_about_quality', array( 'key' => 'about_quality_heading', 'placeholder' => esc_html__( 'Professional equipment has to earn clinical trust.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'about_quality_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-about-page', 'alrenas_about_quality', array( 'key' => 'about_quality_lead', 'placeholder' => esc_html__( 'Alrenas emphasizes safety, reliability, patient comfort, usability and compliance with relevant international requirements. Product-specific certifications and specifications are available on each system page and in technical documentation.', 'alrenas' ) ) );

	for ( $i = 1; $i <= ALRENAS_ABOUT_QUALITY_BADGE_COUNT; $i++ ) {
		add_settings_field(
			'about_quality_badge_' . $i,
			sprintf( /* translators: %d: quality badge slot number. */ esc_html__( 'Badge %d', 'alrenas' ), $i ),
			'alrenas_field_repeater_item',
			'alrenas-content-about-page',
			'alrenas_about_quality',
			array(
				'index'      => $i,
				'option_key' => 'about_quality_badges',
				'fields'     => array(
					'value' => array( 'label' => esc_html__( 'Value (large text)', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. CE', 'alrenas' ) ),
					'label' => array( 'label' => esc_html__( 'Label', 'alrenas' ), 'type' => 'text', 'placeholder' => esc_html__( 'e.g. Product conformity', 'alrenas' ) ),
				),
			)
		);
	}

	// --- Site CTA (shared component: home, blog index, single post, ---
	// --- products page, about page) -------------------------------------
	add_settings_section( 'alrenas_site_cta', esc_html__( 'Site CTA', 'alrenas' ), 'alrenas_section_site_cta_intro', 'alrenas-content-site-cta' );

	add_settings_field( 'site_cta_eyebrow', esc_html__( 'Eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_eyebrow', 'placeholder' => esc_html__( 'Talk to our rehabilitation team', 'alrenas' ) ) );
	add_settings_field( 'site_cta_heading', esc_html__( 'Heading', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_heading', 'placeholder' => esc_html__( 'Find the right system for your patients and clinical workflow.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'site_cta_lead', esc_html__( 'Paragraph', 'alrenas' ), 'alrenas_field_textarea', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_lead', 'placeholder' => esc_html__( 'Tell us about your patient groups, treatment goals and facility. We can help you evaluate the appropriate Alrenas system and prepare a tailored quotation.', 'alrenas' ) ) );
	add_settings_field( 'site_cta_primary_label', esc_html__( 'Primary button label', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_primary_label', 'placeholder' => esc_html__( 'Request a Demo', 'alrenas' ) ) );
	add_settings_field( 'site_cta_primary_url', esc_html__( 'Primary button URL (defaults to the Contact page\'s form section)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_primary_url', 'placeholder' => '/contact/#inquiry' ) );
	add_settings_field( 'site_cta_secondary_label', esc_html__( 'Secondary button label', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_secondary_label', 'placeholder' => esc_html__( 'Contact Us', 'alrenas' ) ) );
	add_settings_field( 'site_cta_secondary_url', esc_html__( 'Secondary button URL (defaults to the Contact page)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-site-cta', 'alrenas_site_cta', array( 'key' => 'site_cta_secondary_url', 'placeholder' => '/contact/' ) );

	// --- Single Product Page (text identical across every product) -------
	// Nearly everything on a product page turned out to be genuinely
	// product-specific once real content was drafted for all 3 systems, so
	// only true boilerplate stays here; the rest lives in each product's
	// own "Alrenas:" meta boxes (see inc/product-meta.php).
	add_settings_section( 'alrenas_sp_general', esc_html__( 'Shared text', 'alrenas' ), 'alrenas_section_sp_general_intro', 'alrenas-content-single-product' );

	add_settings_field( 'sp_primary_label', esc_html__( 'Hero primary button label (scrolls to the inquiry form)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_primary_label', 'placeholder' => esc_html__( 'Get a Quote', 'alrenas' ) ) );
	add_settings_field( 'sp_quote_note', esc_html__( 'Hero note under the buttons', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_quote_note', 'placeholder' => esc_html__( 'Quoted according to your facility, intended use, configuration and support requirements — not sold as a fixed-price ecommerce product.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'sp_gallery_eyebrow', esc_html__( 'Gallery section eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_gallery_eyebrow', 'placeholder' => esc_html__( 'Product gallery', 'alrenas' ) ) );
	add_settings_field( 'sp_details_eyebrow', esc_html__( 'Technical information section eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_details_eyebrow', 'placeholder' => esc_html__( 'Technical information', 'alrenas' ) ) );
	add_settings_field( 'sp_certifications', esc_html__( 'Default certification chips (comma-separated, used unless a product sets its own)', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_certifications', 'placeholder' => esc_html__( 'CE, UKCA, 2-year warranty', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'sp_details_note', esc_html__( 'Note shown if a product has no attributes yet', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_details_note', 'placeholder' => esc_html__( 'Detailed specifications for this system are coming soon.', 'alrenas' ), 'wide' => true ) );
	add_settings_field( 'sp_procurement_eyebrow', esc_html__( 'Procurement section eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_procurement_eyebrow', 'placeholder' => esc_html__( 'Before requesting a quote', 'alrenas' ) ) );
	add_settings_field( 'sp_faq_eyebrow', esc_html__( 'FAQ section eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_faq_eyebrow', 'placeholder' => esc_html__( 'Frequently asked questions', 'alrenas' ) ) );
	add_settings_field( 'sp_documentation_download_label', esc_html__( 'Documentation button label (file uploaded) -- used in both the hero and the documentation section', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_documentation_download_label', 'placeholder' => esc_html__( 'Download Documentation', 'alrenas' ) ) );
	add_settings_field( 'sp_documentation_request_label', esc_html__( 'Documentation button label (no file uploaded yet) -- used in both the hero and the documentation section', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_documentation_request_label', 'placeholder' => esc_html__( 'Request Documentation', 'alrenas' ) ) );
	add_settings_field( 'sp_related_eyebrow', esc_html__( 'Related-products section eyebrow', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_related_eyebrow', 'placeholder' => esc_html__( 'Related rehabilitation systems', 'alrenas' ) ) );
	add_settings_field( 'sp_related_link_label', esc_html__( 'Related-products link label', 'alrenas' ), 'alrenas_field_text', 'alrenas-content-single-product', 'alrenas_sp_general', array( 'key' => 'sp_related_link_label', 'placeholder' => esc_html__( 'Compare all systems', 'alrenas' ) ) );

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
 * Intro text for the Home Disciplines settings section.
 */
function alrenas_section_discipline_tabs_intro() {
	echo '<p>' . esc_html__( 'Each tab has its own button label plus the kicker, title, and description shown in its panel.', 'alrenas' ) . '</p>';
}

/**
 * Render one discipline-tab fieldset (label + kicker + title + description).
 *
 * @param array $args Contains 'index' (1-ALRENAS_DISCIPLINE_COUNT).
 */
function alrenas_field_discipline_tab( $args ) {
	$index = (int) $args['index'];
	$tabs  = alrenas_get_site_content( 'discipline_tabs', array() );
	$tab   = isset( $tabs[ $index ] ) ? $tabs[ $index ] : array();

	$label       = isset( $tab['label'] ) ? $tab['label'] : '';
	$kicker      = isset( $tab['kicker'] ) ? $tab['kicker'] : '';
	$title       = isset( $tab['title'] ) ? $tab['title'] : '';
	$description = isset( $tab['description'] ) ? $tab['description'] : '';

	$name = ALRENAS_SITE_CONTENT_OPTION . '[discipline_tabs][' . $index . ']';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Tab label', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[label]" value="<?php echo esc_attr( $label ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Neurological', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Panel kicker (small label above title)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[kicker]" value="<?php echo esc_attr( $kicker ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Neurological rehabilitation', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Panel title', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'e.g. Support balance, motor control and confidence through structured training.', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Panel description', 'alrenas' ); ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>[description]" rows="2" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
	</fieldset>
	<hr>
	<?php
}

/**
 * Render a single image-picker field for the clinical story section.
 *
 * @param array $args Contains 'key' (site-content key) and 'title' (media modal title).
 */
function alrenas_field_story_image( $args ) {
	$image_id  = (int) alrenas_get_site_content( $args['key'], 0 );
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
	?>
	<p class="alrenas-image-field">
		<img class="alrenas-image-preview" src="<?php echo esc_url( $image_url ); ?>" <?php echo $image_url ? '' : 'hidden'; ?>>
		<input type="hidden" name="<?php echo esc_attr( ALRENAS_SITE_CONTENT_OPTION ); ?>[<?php echo esc_attr( $args['key'] ); ?>]" value="<?php echo esc_attr( $image_id ); ?>">
		<button type="button" class="button alrenas-media-picker" data-title="<?php echo esc_attr( $args['title'] ); ?>"><?php esc_html_e( 'Select image', 'alrenas' ); ?></button>
		<button type="button" class="button alrenas-media-remove" <?php echo $image_url ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'alrenas' ); ?></button>
	</p>
	<?php
}

/**
 * Render one clinical-story point fieldset (title + text).
 *
 * @param array $args Contains 'index' (1-ALRENAS_STORY_POINT_COUNT).
 */
function alrenas_field_story_point( $args ) {
	$index  = (int) $args['index'];
	$points = alrenas_get_site_content( 'story_points', array() );
	$point  = isset( $points[ $index ] ) ? $points[ $index ] : array();

	$title = isset( $point['title'] ) ? $point['title'] : '';
	$text  = isset( $point['text'] ) ? $point['text'] : '';

	$name = ALRENAS_SITE_CONTENT_OPTION . '[story_points][' . $index . ']';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Title', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Patient-centered', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Text', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[text]" value="<?php echo esc_attr( $text ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'e.g. Adapt treatment to different abilities and rehabilitation stages.', 'alrenas' ); ?>">
			<span class="description"><?php esc_html_e( 'Supports <strong> and <em> tags to make part of the sentence bold or italic.', 'alrenas' ); ?></span>
		</p>
	</fieldset>
	<hr>
	<?php
}

/**
 * Render a product-select field for a Products-page hero image slot.
 *
 * @param array $args Contains 'key' (site-content key).
 */
function alrenas_field_products_hero_image( $args ) {
	$selected = (int) alrenas_get_site_content( $args['key'], 0 );
	?>
	<p>
		<?php alrenas_render_product_select( ALRENAS_SITE_CONTENT_OPTION . '[' . $args['key'] . ']', $selected ); ?>
		<span class="description"><?php esc_html_e( 'Uses that product\'s image. Leave unset to fall back to the most recent products.', 'alrenas' ); ?></span>
	</p>
	<?php
}

/**
 * Intro text for the Products-page Systems settings section.
 */
function alrenas_section_products_systems_intro() {
	echo '<p>' . esc_html__( 'The 3 system cards reuse the same product, badge, kicker, description, and tags configured under Home — Products. Capabilities (the 3 short highlights on each card) are specific to this page, so they\'re configured here, matched by slot number.', 'alrenas' ) . '</p>';
}

/**
 * Render one system's capabilities field (comma-separated, matches the
 * Home — Products slot with the same index).
 *
 * @param array $args Contains 'index' (1-ALRENAS_PRODUCTS_SYSTEM_COUNT).
 */
function alrenas_field_product_capabilities( $args ) {
	$index         = (int) $args['index'];
	$capabilities  = alrenas_get_site_content( 'product_capabilities', array() );
	$value         = isset( $capabilities[ $index ] ) ? $capabilities[ $index ] : '';
	$name          = ALRENAS_SITE_CONTENT_OPTION . '[product_capabilities][' . $index . ']';
	?>
	<p>
		<input type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'e.g. Objective postural assessment, Fall-risk evaluation, Guided balance training', 'alrenas' ); ?>">
	</p>
	<?php
}

/**
 * Render one selector-grid item fieldset (title + description + chip label).
 *
 * @param array $args Contains 'index' (1-ALRENAS_PRODUCTS_SELECTOR_COUNT).
 */
function alrenas_field_selector_item( $args ) {
	$index = (int) $args['index'];
	$items = alrenas_get_site_content( 'products_selector_items', array() );
	$item  = isset( $items[ $index ] ) ? $items[ $index ] : array();

	$title       = isset( $item['title'] ) ? $item['title'] : '';
	$description = isset( $item['description'] ) ? $item['description'] : '';
	$chip        = isset( $item['chip'] ) ? $item['chip'] : '';

	$name = ALRENAS_SITE_CONTENT_OPTION . '[products_selector_items][' . $index . ']';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Title', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Measure static balance', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Description', 'alrenas' ); ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>[description]" rows="2" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Chip label', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[chip]" value="<?php echo esc_attr( $chip ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Stabilometric', 'alrenas' ); ?>">
		</p>
	</fieldset>
	<hr>
	<?php
}

/**
 * Render one quotation-process step fieldset (title + description).
 *
 * @param array $args Contains 'index' (1-ALRENAS_PRODUCTS_QUOTE_STEP_COUNT).
 */
function alrenas_field_quote_step( $args ) {
	$index = (int) $args['index'];
	$steps = alrenas_get_site_content( 'products_quote_steps', array() );
	$step  = isset( $steps[ $index ] ) ? $steps[ $index ] : array();

	$title       = isset( $step['title'] ) ? $step['title'] : '';
	$description = isset( $step['description'] ) ? $step['description'] : '';

	$name = ALRENAS_SITE_CONTENT_OPTION . '[products_quote_steps][' . $index . ']';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Title', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Tell us about your facility', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Description', 'alrenas' ); ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>[description]" rows="2" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
	</fieldset>
	<hr>
	<?php
}

/**
 * Generic repeater-item fieldset renderer, used for the several simple
 * "N items, each with the same 2-3 text/textarea sub-fields" repeaters on
 * the Contact and About pages, so each doesn't need its own near-identical
 * render function.
 *
 * @param array $args {
 *     @type int    $index      1-based slot number.
 *     @type string $option_key Site-content array key.
 *     @type array  $fields     Map of sub-field key => array{label, type: 'text'|'textarea', placeholder?, wide?}.
 * }
 */
function alrenas_field_repeater_item( $args ) {
	$index = (int) $args['index'];
	$items = alrenas_get_site_content( $args['option_key'], array() );
	$item  = isset( $items[ $index ] ) ? $items[ $index ] : array();
	$name  = ALRENAS_SITE_CONTENT_OPTION . '[' . $args['option_key'] . '][' . $index . ']';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<?php foreach ( $args['fields'] as $key => $field ) : ?>
			<?php $value = isset( $item[ $key ] ) ? $item[ $key ] : ''; ?>
			<p>
				<label class="alrenas-field-label"><?php echo esc_html( $field['label'] ); ?></label>
				<?php if ( 'textarea' === $field['type'] ) : ?>
					<textarea name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $key ); ?>]" rows="2" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
				<?php else : ?>
					<input type="text" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="<?php echo empty( $field['wide'] ) ? 'regular-text' : 'large-text'; ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ?? '' ); ?>">
				<?php endif; ?>
			</p>
		<?php endforeach; ?>
	</fieldset>
	<hr>
	<?php
}

/**
 * Sanitize a 1-indexed repeater array posted from alrenas_field_repeater_item
 * fieldsets, dropping any index outside the expected range.
 *
 * @param mixed $raw    Raw posted value for the array key.
 * @param int   $count  Highest valid 1-based index.
 * @param array $fields Map of sub-field key => 'text'|'textarea'.
 * @return array
 */
function alrenas_sanitize_repeater_field( $raw, $count, $fields ) {
	$output = array();

	if ( empty( $raw ) || ! is_array( $raw ) ) {
		return $output;
	}

	foreach ( $raw as $index => $row ) {
		$index = (int) $index;

		if ( $index < 1 || $index > $count ) {
			continue;
		}

		$clean = array();

		foreach ( $fields as $key => $type ) {
			$value         = isset( $row[ $key ] ) ? wp_unslash( $row[ $key ] ) : '';
			$clean[ $key ] = 'textarea' === $type ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		}

		$output[ $index ] = $clean;
	}

	return $output;
}

/**
 * Intro text for the Single-Product-Page shared-text section.
 */
function alrenas_section_sp_general_intro() {
	echo '<p>' . esc_html__( 'Everything on a product page turned out to be genuinely product-specific once real content was drafted for each system, so this tab only holds true sitewide boilerplate: button labels and section eyebrows repeated identically across every product. Titles, images, description, tags, stats, workflow tabs, FAQ, video, and documentation are all configured per product -- on that product\'s own edit screen.', 'alrenas' ) . '</p>';
}

/**
 * Intro text for the shared Site CTA settings section.
 */
function alrenas_section_site_cta_intro() {
	echo '<p>' . esc_html__( 'This banner is a shared component reused across several pages (homepage, blog index, single posts, the Products page, and the About page) -- editing it here updates every one of them at once.', 'alrenas' ) . '</p>';
}

/**
 * Intro text for the Contact-page inquiry-options settings section.
 */
function alrenas_section_contact_options_intro() {
	echo '<p>' . esc_html__( 'The 4 selectable buttons shown above the contact form. Each title also fills the intent badge on the form card when that option is selected.', 'alrenas' ) . '</p>';
}

/**
 * Best-effort admin preview thumbnail for any attachment type (image, SVG,
 * or video) -- falls back to WordPress's generic file-type icon so video
 * attachments still show something in the picker preview.
 *
 * @param int $media_id Attachment ID.
 * @return string
 */
function alrenas_admin_media_preview_url( $media_id ) {
	$image_url = wp_get_attachment_image_url( $media_id, 'medium' );

	if ( $image_url ) {
		return $image_url;
	}

	$icon_url = wp_mime_type_icon( $media_id );

	return $icon_url ? $icon_url : '';
}

/**
 * Intro text for the Home Process settings section.
 */
function alrenas_section_process_steps_intro() {
	echo '<p>' . esc_html__( 'Title and description for each box, plus an optional image, SVG, or .webm video shown at the bottom of the box. Media fills the full width with no side padding, and every box is the same height.', 'alrenas' ) . '</p>';
}

/**
 * Render one process-step fieldset (title + description + media).
 *
 * @param array $args Contains 'index' (1-ALRENAS_CARE_STEP_COUNT).
 */
function alrenas_field_care_step( $args ) {
	$index = (int) $args['index'];
	$steps = alrenas_get_site_content( 'care_steps', array() );
	$step  = isset( $steps[ $index ] ) ? $steps[ $index ] : array();

	$kicker      = isset( $step['kicker'] ) ? $step['kicker'] : '';
	$title       = isset( $step['title'] ) ? $step['title'] : '';
	$description = isset( $step['description'] ) ? $step['description'] : '';
	$media_id    = isset( $step['media_id'] ) ? (int) $step['media_id'] : 0;

	$name      = ALRENAS_SITE_CONTENT_OPTION . '[care_steps][' . $index . ']';
	$media_url = $media_id ? alrenas_admin_media_preview_url( $media_id ) : '';
	?>
	<fieldset class="alrenas-slide-fieldset">
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Kicker (above title)', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[kicker]" value="<?php echo esc_attr( $kicker ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Step 01', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Title', 'alrenas' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. Assess', 'alrenas' ); ?>">
		</p>
		<p>
			<label class="alrenas-field-label"><?php esc_html_e( 'Description', 'alrenas' ); ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>[description]" rows="2" class="large-text"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
		<p class="alrenas-image-field">
			<span class="alrenas-field-label"><?php esc_html_e( 'Media (image, SVG, or .webm video)', 'alrenas' ); ?></span>
			<img class="alrenas-image-preview" src="<?php echo esc_url( $media_url ); ?>" <?php echo $media_url ? '' : 'hidden'; ?>>
			<input type="hidden" name="<?php echo esc_attr( $name ); ?>[media_id]" value="<?php echo esc_attr( $media_id ); ?>">
			<button type="button" class="button alrenas-media-picker" data-title="<?php esc_attr_e( 'Select box media', 'alrenas' ); ?>"><?php esc_html_e( 'Select media', 'alrenas' ); ?></button>
			<button type="button" class="button alrenas-media-remove" <?php echo $media_url ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'alrenas' ); ?></button>
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
		'home_products_heading',
		'care_strip_text',
		'care_strip_tags',
		'why_eyebrow',
		'why_heading',
		'process_eyebrow',
		'process_heading',
		'process_link_label',
		'process_link_url',
		'discipline_eyebrow',
		'discipline_heading',
		'story_eyebrow',
		'story_heading',
		'products_hero_eyebrow',
		'products_hero_heading',
		'products_hero_primary_label',
		'products_hero_secondary_label',
		'products_intro_eyebrow',
		'products_intro_heading',
		'products_intro_link_label',
		'products_systems_eyebrow',
		'products_systems_heading',
		'products_selector_eyebrow',
		'products_selector_heading',
		'products_quote_eyebrow',
		'products_quote_heading',
		'contact_hero_eyebrow',
		'contact_hero_heading',
		'contact_hero_primary_label',
		'contact_details_eyebrow',
		'contact_details_heading',
		'contact_form_heading',
		'about_hero_eyebrow',
		'about_hero_heading',
		'about_hero_primary_label',
		'about_hero_secondary_label',
		'about_mission_eyebrow',
		'about_mission_heading',
		'about_values_eyebrow',
		'about_values_heading',
		'about_story_eyebrow',
		'about_story_heading',
		'about_quality_eyebrow',
		'about_quality_heading',
		'site_cta_eyebrow',
		'site_cta_heading',
		'site_cta_primary_label',
		'site_cta_primary_url',
		'site_cta_secondary_label',
		'site_cta_secondary_url',
		'sp_primary_label',
		'sp_quote_note',
		'sp_gallery_eyebrow',
		'sp_details_eyebrow',
		'sp_certifications',
		'sp_details_note',
		'sp_procurement_eyebrow',
		'sp_faq_eyebrow',
		'sp_documentation_download_label',
		'sp_documentation_request_label',
		'sp_related_eyebrow',
		'sp_related_link_label',
	);

	$url_keys = array( 'hero_primary_url', 'hero_secondary_url', 'process_link_url', 'site_cta_primary_url', 'site_cta_secondary_url' );

	foreach ( $text_keys as $key ) {
		if ( ! isset( $input[ $key ] ) ) {
			$output[ $key ] = '';
			continue;
		}

		$value = wp_unslash( $input[ $key ] );

		$output[ $key ] = in_array( $key, $url_keys, true )
			? esc_url_raw( $value )
			: sanitize_text_field( $value );
	}

	$output['hero_lead'] = isset( $input['hero_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['hero_lead'] ) )
		: '';

	$output['process_lead'] = isset( $input['process_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['process_lead'] ) )
		: '';

	$output['story_lead'] = isset( $input['story_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['story_lead'] ) )
		: '';

	$output['home_products_lead'] = isset( $input['home_products_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['home_products_lead'] ) )
		: '';

	$output['discipline_lead'] = isset( $input['discipline_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['discipline_lead'] ) )
		: '';

	$output['why_lead'] = isset( $input['why_lead'] )
		? sanitize_textarea_field( wp_unslash( $input['why_lead'] ) )
		: '';

	$output['why_items'] = alrenas_sanitize_repeater_field( $input['why_items'] ?? null, ALRENAS_WHY_ITEM_COUNT, array( 'title' => 'text', 'description' => 'textarea' ) );

	$output['story_main_image_id']  = isset( $input['story_main_image_id'] ) ? absint( $input['story_main_image_id'] ) : 0;
	$output['story_small_image_id'] = isset( $input['story_small_image_id'] ) ? absint( $input['story_small_image_id'] ) : 0;

	$output['products_hero_lead']   = isset( $input['products_hero_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['products_hero_lead'] ) ) : '';
	$output['products_intro_lead']  = isset( $input['products_intro_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['products_intro_lead'] ) ) : '';
	$output['products_systems_lead'] = isset( $input['products_systems_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['products_systems_lead'] ) ) : '';
	$output['products_quote_lead']  = isset( $input['products_quote_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['products_quote_lead'] ) ) : '';

	$output['products_hero_main_product_id'] = isset( $input['products_hero_main_product_id'] ) ? absint( $input['products_hero_main_product_id'] ) : 0;
	$output['products_hero_mini_product_id'] = isset( $input['products_hero_mini_product_id'] ) ? absint( $input['products_hero_mini_product_id'] ) : 0;

	$output['product_capabilities'] = array();

	if ( ! empty( $input['product_capabilities'] ) && is_array( $input['product_capabilities'] ) ) {
		foreach ( $input['product_capabilities'] as $index => $value ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_PRODUCTS_SYSTEM_COUNT ) {
				continue;
			}

			$output['product_capabilities'][ $index ] = sanitize_text_field( wp_unslash( $value ) );
		}
	}

	$output['products_selector_items'] = array();

	if ( ! empty( $input['products_selector_items'] ) && is_array( $input['products_selector_items'] ) ) {
		foreach ( $input['products_selector_items'] as $index => $item ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_PRODUCTS_SELECTOR_COUNT ) {
				continue;
			}

			$output['products_selector_items'][ $index ] = array(
				'title'       => isset( $item['title'] ) ? sanitize_text_field( wp_unslash( $item['title'] ) ) : '',
				'description' => isset( $item['description'] ) ? sanitize_textarea_field( wp_unslash( $item['description'] ) ) : '',
				'chip'        => isset( $item['chip'] ) ? sanitize_text_field( wp_unslash( $item['chip'] ) ) : '',
			);
		}
	}

	$output['products_quote_steps'] = array();

	if ( ! empty( $input['products_quote_steps'] ) && is_array( $input['products_quote_steps'] ) ) {
		foreach ( $input['products_quote_steps'] as $index => $step ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_PRODUCTS_QUOTE_STEP_COUNT ) {
				continue;
			}

			$output['products_quote_steps'][ $index ] = array(
				'title'       => isset( $step['title'] ) ? sanitize_text_field( wp_unslash( $step['title'] ) ) : '',
				'description' => isset( $step['description'] ) ? sanitize_textarea_field( wp_unslash( $step['description'] ) ) : '',
			);
		}
	}

	// --- Contact / About pages ------------------------------------------
	$output['contact_hero_lead']    = isset( $input['contact_hero_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['contact_hero_lead'] ) ) : '';
	$output['contact_details_lead'] = isset( $input['contact_details_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['contact_details_lead'] ) ) : '';
	$output['contact_map_note']     = isset( $input['contact_map_note'] ) ? sanitize_textarea_field( wp_unslash( $input['contact_map_note'] ) ) : '';
	$output['contact_form_lead']    = isset( $input['contact_form_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['contact_form_lead'] ) ) : '';

	$output['about_hero_lead']         = isset( $input['about_hero_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['about_hero_lead'] ) ) : '';
	$output['about_mission_paragraph_1'] = isset( $input['about_mission_paragraph_1'] ) ? sanitize_textarea_field( wp_unslash( $input['about_mission_paragraph_1'] ) ) : '';
	$output['about_mission_paragraph_2'] = isset( $input['about_mission_paragraph_2'] ) ? sanitize_textarea_field( wp_unslash( $input['about_mission_paragraph_2'] ) ) : '';
	$output['about_values_lead']       = isset( $input['about_values_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['about_values_lead'] ) ) : '';
	$output['about_story_paragraph_1'] = isset( $input['about_story_paragraph_1'] ) ? sanitize_textarea_field( wp_unslash( $input['about_story_paragraph_1'] ) ) : '';
	$output['about_story_paragraph_2'] = isset( $input['about_story_paragraph_2'] ) ? sanitize_textarea_field( wp_unslash( $input['about_story_paragraph_2'] ) ) : '';
	$output['about_quality_lead']      = isset( $input['about_quality_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['about_quality_lead'] ) ) : '';

	$output['site_cta_lead'] = isset( $input['site_cta_lead'] ) ? sanitize_textarea_field( wp_unslash( $input['site_cta_lead'] ) ) : '';

	$output['about_hero_small_image_id'] = isset( $input['about_hero_small_image_id'] ) ? absint( $input['about_hero_small_image_id'] ) : 0;
	$output['about_story_image_id']      = isset( $input['about_story_image_id'] ) ? absint( $input['about_story_image_id'] ) : 0;

	$output['contact_options_items'] = alrenas_sanitize_repeater_field( $input['contact_options_items'] ?? null, ALRENAS_CONTACT_OPTION_COUNT, array( 'title' => 'text', 'description' => 'text' ) );
	$output['about_mission_points']  = alrenas_sanitize_repeater_field( $input['about_mission_points'] ?? null, ALRENAS_ABOUT_MISSION_POINT_COUNT, array( 'title' => 'text', 'description' => 'text' ) );
	$output['about_value_cards']     = alrenas_sanitize_repeater_field( $input['about_value_cards'] ?? null, ALRENAS_ABOUT_VALUE_COUNT, array( 'title' => 'text', 'description' => 'textarea' ) );
	$output['about_story_points']    = alrenas_sanitize_repeater_field( $input['about_story_points'] ?? null, ALRENAS_ABOUT_STORY_POINT_COUNT, array( 'title' => 'text', 'description' => 'text' ) );
	$output['about_quality_badges']  = alrenas_sanitize_repeater_field( $input['about_quality_badges'] ?? null, ALRENAS_ABOUT_QUALITY_BADGE_COUNT, array( 'value' => 'text', 'label' => 'text' ) );

	$output['story_points'] = array();

	if ( ! empty( $input['story_points'] ) && is_array( $input['story_points'] ) ) {
		foreach ( $input['story_points'] as $index => $point ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_STORY_POINT_COUNT ) {
				continue;
			}

			// wp_kses() (not sanitize_text_field(), which strips all tags) so
			// the admin can wrap part of the sentence in <strong>/<em> for
			// emphasis -- the template outputs this with wp_kses() too.
			$output['story_points'][ $index ] = array(
				'title' => isset( $point['title'] ) ? sanitize_text_field( wp_unslash( $point['title'] ) ) : '',
				'text'  => isset( $point['text'] ) ? wp_kses( wp_unslash( $point['text'] ), array( 'strong' => array(), 'b' => array(), 'em' => array(), 'i' => array() ) ) : '',
			);
		}
	}

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

	$output['care_steps'] = array();

	if ( ! empty( $input['care_steps'] ) && is_array( $input['care_steps'] ) ) {
		foreach ( $input['care_steps'] as $index => $step ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_CARE_STEP_COUNT ) {
				continue;
			}

			$output['care_steps'][ $index ] = array(
				'kicker'      => isset( $step['kicker'] ) ? sanitize_text_field( wp_unslash( $step['kicker'] ) ) : '',
				'title'       => isset( $step['title'] ) ? sanitize_text_field( wp_unslash( $step['title'] ) ) : '',
				'description' => isset( $step['description'] ) ? sanitize_textarea_field( wp_unslash( $step['description'] ) ) : '',
				'media_id'    => isset( $step['media_id'] ) ? absint( $step['media_id'] ) : 0,
			);
		}
	}

	$output['discipline_tabs'] = array();

	if ( ! empty( $input['discipline_tabs'] ) && is_array( $input['discipline_tabs'] ) ) {
		foreach ( $input['discipline_tabs'] as $index => $tab ) {
			$index = (int) $index;

			if ( $index < 1 || $index > ALRENAS_DISCIPLINE_COUNT ) {
				continue;
			}

			$output['discipline_tabs'][ $index ] = array(
				'label'       => isset( $tab['label'] ) ? sanitize_text_field( wp_unslash( $tab['label'] ) ) : '',
				'kicker'      => isset( $tab['kicker'] ) ? sanitize_text_field( wp_unslash( $tab['kicker'] ) ) : '',
				'title'       => isset( $tab['title'] ) ? sanitize_text_field( wp_unslash( $tab['title'] ) ) : '',
				'description' => isset( $tab['description'] ) ? sanitize_textarea_field( wp_unslash( $tab['description'] ) ) : '',
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
