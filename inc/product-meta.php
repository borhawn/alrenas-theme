<?php
/**
 * Per-product content for the custom single-product template (matching
 * the al-balance-dyn/al-balance-stabilometric/standing-balance-trainer
 * reference pages): hero kicker/badges, proof stats, purpose section,
 * workflow tabs, clinical note band, feature-story section, clinical
 * applications, gallery/details text, procurement questions, FAQ, video,
 * and documentation. Everything here is specific to one product; sitewide
 * copy repeated identically across every product page lives in Site
 * Content instead (see the "Single Product Page" tab).
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_PRODUCT_PROOF_COUNT', 4 );
define( 'ALRENAS_PRODUCT_PURPOSE_COUNT', 4 );
define( 'ALRENAS_PRODUCT_FEATURE_COUNT', 4 );
define( 'ALRENAS_PRODUCT_APPLICATION_COUNT', 4 );
define( 'ALRENAS_PRODUCT_PROCUREMENT_COUNT', 4 );
define( 'ALRENAS_PRODUCT_META_NONCE', 'alrenas_product_meta_nonce' );

/**
 * Get a per-product meta value with a default.
 *
 * @param int    $product_id Product post ID.
 * @param string $key        Meta key without the leading underscore prefix.
 * @param mixed  $default    Fallback.
 * @return mixed
 */
function alrenas_get_product_meta( $product_id, $key, $default = '' ) {
	$value = get_post_meta( $product_id, '_alrenas_' . $key, true );

	return ( '' === $value || array() === $value ) ? $default : $value;
}

/**
 * A product's FAQ question/answer pairs with both fields non-empty,
 * shared by the visible FAQ section and its FAQPage structured data.
 *
 * @param int $product_id Product ID.
 * @return array<int,array{question:string,answer:string}>
 */
function alrenas_get_product_faq_items( $product_id ) {
	$raw   = alrenas_get_product_meta( $product_id, 'faq_items', array() );
	$items = array();

	foreach ( $raw as $item ) {
		if ( ! empty( $item['question'] ) && ! empty( $item['answer'] ) ) {
			$items[] = $item;
		}
	}

	return $items;
}

/**
 * Register every product meta box.
 */
function alrenas_register_product_meta_boxes() {
	$boxes = array(
		'alrenas-product-hero'          => array( __( 'Alrenas: Hero content', 'alrenas' ), 'alrenas_render_hero_meta_box' ),
		'alrenas-product-proof'         => array( __( 'Alrenas: Proof stats (4 items)', 'alrenas' ), 'alrenas_render_proof_meta_box' ),
		'alrenas-product-purpose'       => array( __( 'Alrenas: Purpose section', 'alrenas' ), 'alrenas_render_purpose_meta_box' ),
		'alrenas-product-workflow'      => array( __( 'Alrenas: Clinical workflow', 'alrenas' ), 'alrenas_render_workflow_meta_box' ),
		'alrenas-product-note'          => array( __( 'Alrenas: Clinical note band', 'alrenas' ), 'alrenas_render_note_meta_box' ),
		'alrenas-product-story'         => array( __( 'Alrenas: Feature story section', 'alrenas' ), 'alrenas_render_story_meta_box' ),
		'alrenas-product-software'      => array( __( 'Alrenas: Software showcase', 'alrenas' ), 'alrenas_render_software_meta_box' ),
		'alrenas-product-applications'  => array( __( 'Alrenas: Clinical applications (4 cards)', 'alrenas' ), 'alrenas_render_applications_meta_box' ),
		'alrenas-product-gallery-text'  => array( __( 'Alrenas: Gallery section text', 'alrenas' ), 'alrenas_render_gallery_text_meta_box' ),
		'alrenas-product-details-text'  => array( __( 'Alrenas: Technical information text', 'alrenas' ), 'alrenas_render_details_text_meta_box' ),
		'alrenas-product-procurement'   => array( __( 'Alrenas: Before-you-quote questions', 'alrenas' ), 'alrenas_render_procurement_meta_box' ),
		'alrenas-product-faq'           => array( __( 'Alrenas: FAQ', 'alrenas' ), 'alrenas_render_faq_meta_box' ),
		'alrenas-product-video'         => array( __( 'Alrenas: Video', 'alrenas' ), 'alrenas_render_video_meta_box' ),
		'alrenas-product-document'      => array( __( 'Alrenas: Documentation', 'alrenas' ), 'alrenas_render_document_meta_box' ),
		'alrenas-product-related'       => array( __( 'Alrenas: Related products', 'alrenas' ), 'alrenas_render_related_meta_box' ),
	);

	foreach ( $boxes as $id => $args ) {
		add_meta_box( $id, $args[0], $args[1], 'product', 'normal', 'default' );
	}
}
add_action( 'add_meta_boxes', 'alrenas_register_product_meta_boxes' );

/**
 * Load the media picker + dynamic-repeater JS only on the product editor.
 */
function alrenas_product_meta_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();
	add_action( 'admin_print_footer_scripts', 'alrenas_product_meta_inline_footer' );
}
add_action( 'admin_enqueue_scripts', 'alrenas_product_meta_admin_assets' );

/**
 * Inline admin CSS/JS: media pickers (image + any file) and every
 * add/remove-able repeater (workflow tabs, FAQ items).
 */
function alrenas_product_meta_inline_footer() {
	?>
	<style>
		.alrenas-pm-field { max-width: 640px; margin-bottom: 16px; }
		.alrenas-pm-field label { display: block; font-weight: 600; margin-bottom: 4px; }
		.alrenas-pm-field input[type="text"], .alrenas-pm-field input[type="url"], .alrenas-pm-field textarea { width: 100%; }
		.alrenas-pm-row { display: flex; gap: 20px; flex-wrap: wrap; }
		.alrenas-pm-row .alrenas-pm-field { flex: 1 1 260px; }
		.alrenas-pm-repeater-item { margin: 0 0 18px; padding: 14px 16px; border: 1px solid #dcdcde; border-radius: 6px; background: #fbfbfc; }
		.alrenas-pm-repeater-item.is-dragging { opacity: .4; }
		.alrenas-pm-repeater-item h4 { margin: 0 0 10px; display: flex; align-items: center; gap: 8px; }
		.alrenas-pm-repeater-item h4 .alrenas-pm-repeater-title { flex: 1; }
		.alrenas-pm-repeater-handle { cursor: grab; padding: 2px 6px; color: #787c82; font-size: 16px; line-height: 1; user-select: none; }
		.alrenas-pm-repeater-handle:active { cursor: grabbing; }
		.alrenas-pm-media-preview { display: block; width: 120px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #dcdcde; background: #f0f0f1; margin-bottom: 8px; }
		.alrenas-pm-media-preview[hidden] { display: none; }
		.alrenas-pm-file-name { display: inline-block; margin-right: 10px; font-style: italic; }
	</style>
	<script>
	( function () {
		function initMediaPicker( wrap ) {
			var button  = wrap.querySelector( '.alrenas-pm-media-pick' );
			var remove  = wrap.querySelector( '.alrenas-pm-media-remove' );
			var input   = wrap.querySelector( 'input[type="hidden"]' );
			var preview = wrap.querySelector( '.alrenas-pm-media-preview' );
			var fname   = wrap.querySelector( '.alrenas-pm-file-name' );

			if ( button && ! button.dataset.wired ) {
				button.dataset.wired = '1';
				button.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					var frame = wp.media( { title: button.dataset.title || 'Select file', button: { text: 'Use this file' }, multiple: false } );
					frame.on( 'select', function () {
						var attachment = frame.state().get( 'selection' ).first().toJSON();
						input.value = attachment.id;
						if ( preview ) {
							if ( attachment.sizes && attachment.sizes.medium ) {
								preview.src = attachment.sizes.medium.url;
							} else if ( attachment.type === 'image' ) {
								preview.src = attachment.url;
							} else {
								preview.src = attachment.icon || attachment.url;
							}
							preview.hidden = false;
						}
						if ( fname ) {
							fname.textContent = attachment.filename || '';
							fname.hidden = false;
						}
						if ( remove ) remove.hidden = false;
					} );
					frame.open();
				} );
			}

			if ( remove && ! remove.dataset.wired ) {
				remove.dataset.wired = '1';
				remove.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					input.value = '';
					if ( preview ) preview.hidden = true;
					if ( fname ) fname.hidden = true;
					remove.hidden = true;
				} );
			}
		}

		document.querySelectorAll( '.alrenas-pm-media-field' ).forEach( initMediaPicker );

		document.querySelectorAll( '.alrenas-pm-dynamic-repeater' ).forEach( function ( repeaterRoot ) {
			var rows        = repeaterRoot.querySelector( '.alrenas-pm-repeater-rows' );
			var addBtn      = repeaterRoot.querySelector( '.alrenas-pm-repeater-add' );
			var template    = repeaterRoot.querySelector( 'template' );
			var counter     = rows.querySelectorAll( '.alrenas-pm-repeater-item' ).length;
			var draggingRow = null;

			// Reordering is purely a client-side drag of the row elements --
			// the save routine (alrenas_sanitize_pm_dynamic_repeater()) already
			// re-keys everything 1..N in the order fields are submitted,
			// ignoring whatever [index] each field's name carries. So moving
			// the DOM nodes here is the entire feature; nothing server-side
			// needs to change and no data can be lost by reordering.
			function getRowAfterPointer( y ) {
				var candidates = [].slice.call( rows.querySelectorAll( '.alrenas-pm-repeater-item:not(.is-dragging)' ) );
				var result = { offset: -Infinity, element: null };
				candidates.forEach( function ( candidate ) {
					var box = candidate.getBoundingClientRect();
					var offset = y - box.top - box.height / 2;
					if ( offset < 0 && offset > result.offset ) {
						result = { offset: offset, element: candidate };
					}
				} );
				return result.element;
			}

			rows.addEventListener( 'dragover', function ( e ) {
				if ( ! draggingRow ) return;
				e.preventDefault();
				var afterRow = getRowAfterPointer( e.clientY );
				if ( null === afterRow ) {
					rows.appendChild( draggingRow );
				} else {
					rows.insertBefore( draggingRow, afterRow );
				}
			} );

			function wireRow( row ) {
				var removeBtn = row.querySelector( '.alrenas-pm-repeater-remove' );
				if ( removeBtn ) {
					removeBtn.addEventListener( 'click', function () {
						row.remove();
					} );
				}
				row.querySelectorAll( '.alrenas-pm-media-field' ).forEach( initMediaPicker );

				var handle = row.querySelector( '.alrenas-pm-repeater-handle' );
				if ( handle ) {
					handle.addEventListener( 'dragstart', function ( e ) {
						draggingRow = row;
						row.classList.add( 'is-dragging' );
						e.dataTransfer.effectAllowed = 'move';
						e.dataTransfer.setData( 'text/plain', '' );
					} );
					handle.addEventListener( 'dragend', function () {
						row.classList.remove( 'is-dragging' );
						draggingRow = null;
					} );
				}
			}

			rows.querySelectorAll( '.alrenas-pm-repeater-item' ).forEach( wireRow );

			addBtn.addEventListener( 'click', function () {
				counter += 1;
				var html = template.innerHTML.replace( /__INDEX__/g, counter );
				var wrapper = document.createElement( 'div' );
				wrapper.innerHTML = html.trim();
				var row = wrapper.firstElementChild;
				rows.appendChild( row );
				wireRow( row );
			} );
		} );
	} )();
	</script>
	<?php
}

/**
 * Generic single-line text meta field.
 */
function alrenas_pm_text( $name, $label, $value, $placeholder = '' ) {
	printf(
		'<p class="alrenas-pm-field"><label>%1$s</label><input type="text" name="%2$s" value="%3$s" placeholder="%4$s"></p>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_attr( $value ),
		esc_attr( $placeholder )
	);
}

/**
 * Generic textarea meta field.
 */
function alrenas_pm_textarea( $name, $label, $value, $placeholder = '' ) {
	printf(
		'<p class="alrenas-pm-field"><label>%1$s</label><textarea name="%2$s" rows="3" placeholder="%4$s">%3$s</textarea></p>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_textarea( $value ),
		esc_attr( $placeholder )
	);
}

/**
 * Generic media-picker field (image or any file type).
 */
function alrenas_pm_media( $name, $label, $attachment_id, $picker_title ) {
	$attachment_id = (int) $attachment_id;
	$preview_url   = '';

	if ( $attachment_id ) {
		$preview_url = wp_get_attachment_image_url( $attachment_id, 'medium' );
		if ( ! $preview_url ) {
			$preview_url = wp_mime_type_icon( $attachment_id );
		}
	}

	$filename = $attachment_id ? basename( get_attached_file( $attachment_id ) ) : '';
	?>
	<p class="alrenas-pm-field alrenas-pm-media-field">
		<label><?php echo esc_html( $label ); ?></label>
		<img class="alrenas-pm-media-preview" src="<?php echo esc_url( $preview_url ); ?>" <?php echo $preview_url ? '' : 'hidden'; ?>>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $attachment_id ); ?>">
		<br>
		<span class="alrenas-pm-file-name" <?php echo $filename ? '' : 'hidden'; ?>><?php echo esc_html( $filename ); ?></span>
		<button type="button" class="button alrenas-pm-media-pick" data-title="<?php echo esc_attr( $picker_title ); ?>"><?php esc_html_e( 'Select file', 'alrenas' ); ?></button>
		<button type="button" class="button alrenas-pm-media-remove" <?php echo $attachment_id ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'alrenas' ); ?></button>
	</p>
	<?php
}

/**
 * Render a fixed-count repeater (no add/remove) of simple text/textarea fields.
 *
 * @param string $meta_key Meta key (without leading underscore or product prefix).
 * @param int    $count    Fixed number of slots.
 * @param array  $fields   Map of sub-field key => array{label, type, placeholder}.
 * @param array  $saved    Saved 1-indexed array.
 * @param string $item_label Label used for "Item %d" headers.
 */
function alrenas_pm_fixed_repeater( $meta_key, $count, $fields, $saved, $item_label ) {
	for ( $i = 1; $i <= $count; $i++ ) {
		$item = isset( $saved[ $i ] ) ? $saved[ $i ] : array();
		?>
		<div class="alrenas-pm-repeater-item">
			<h4><?php printf( /* translators: 1: item label, 2: slot number. */ esc_html__( '%1$s %2$d', 'alrenas' ), esc_html( $item_label ), $i ); ?></h4>
			<?php foreach ( $fields as $key => $field ) : ?>
				<?php $value = isset( $item[ $key ] ) ? $item[ $key ] : ''; ?>
				<?php if ( 'textarea' === $field['type'] ) : ?>
					<?php alrenas_pm_textarea( "_alrenas_{$meta_key}[{$i}][{$key}]", $field['label'], $value, $field['placeholder'] ?? '' ); ?>
				<?php else : ?>
					<?php alrenas_pm_text( "_alrenas_{$meta_key}[{$i}][{$key}]", $field['label'], $value, $field['placeholder'] ?? '' ); ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

/**
 * Render an add/remove-able repeater's markup (rows container, add
 * button, and the JS clone template) for a given field shape.
 *
 * @param string $meta_key Meta key.
 * @param array  $fields   Map of sub-field key => array{label, type, placeholder}. A field of type 'media' renders a media picker.
 * @param array  $saved    Saved list (numeric or 1-indexed, either works -- re-keyed on save).
 * @param string $add_label Label for the "add" button.
 */
function alrenas_pm_dynamic_repeater( $meta_key, $fields, $saved, $add_label ) {
	if ( ! $saved ) {
		$saved = array( array() );
	}
	?>
	<div class="alrenas-pm-dynamic-repeater" data-repeater="<?php echo esc_attr( $meta_key ); ?>">
		<div class="alrenas-pm-repeater-rows">
			<?php foreach ( array_values( $saved ) as $i => $row ) : ?>
				<?php echo alrenas_pm_dynamic_repeater_row( $meta_key, $i, $row, $fields ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping helper. ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-primary alrenas-pm-repeater-add"><?php echo esc_html( $add_label ); ?></button>
		<template><?php echo alrenas_pm_dynamic_repeater_row( $meta_key, '__INDEX__', array(), $fields ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></template>
	</div>
	<?php
}

/**
 * Render one dynamic-repeater row's markup.
 *
 * @param string     $meta_key Meta key.
 * @param int|string $index    Row index, or '__INDEX__' for the template row.
 * @param array      $row      Saved row data.
 * @param array      $fields   Field definitions, see alrenas_pm_dynamic_repeater().
 * @return string
 */
function alrenas_pm_dynamic_repeater_row( $meta_key, $index, $row, $fields ) {
	$name = "_alrenas_{$meta_key}[{$index}]";
	ob_start();
	?>
	<div class="alrenas-pm-repeater-item">
		<h4>
			<span class="alrenas-pm-repeater-handle" draggable="true" title="<?php esc_attr_e( 'Drag to reorder', 'alrenas' ); ?>" aria-hidden="true">⠿</span>
			<span class="alrenas-pm-repeater-title"><?php esc_html_e( 'Item', 'alrenas' ); ?></span>
			<button type="button" class="button-link-delete alrenas-pm-repeater-remove"><?php esc_html_e( 'Remove', 'alrenas' ); ?></button>
		</h4>
		<?php foreach ( $fields as $key => $field ) : ?>
			<?php $value = isset( $row[ $key ] ) ? $row[ $key ] : ''; ?>
			<?php if ( 'media' === $field['type'] ) : ?>
				<?php alrenas_pm_media( "{$name}[{$key}]", $field['label'], $value ?: 0, $field['placeholder'] ?? '' ); ?>
			<?php elseif ( 'textarea' === $field['type'] ) : ?>
				<?php alrenas_pm_textarea( "{$name}[{$key}]", $field['label'], $value, $field['placeholder'] ?? '' ); ?>
			<?php else : ?>
				<?php alrenas_pm_text( "{$name}[{$key}]", $field['label'], $value, $field['placeholder'] ?? '' ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

/* -------------------------------------------------------------------- */
/* Meta box renderers                                                    */
/* -------------------------------------------------------------------- */

function alrenas_render_hero_meta_box( $post ) {
	wp_nonce_field( ALRENAS_PRODUCT_META_NONCE, ALRENAS_PRODUCT_META_NONCE );
	?>
	<p class="description"><?php esc_html_e( 'The product title, lead paragraph, gallery images, and clinical tags come from this product\'s Title, Short description, Product image/gallery, and Tags -- edit those in the usual WooCommerce fields above.', 'alrenas' ); ?></p>
	<?php alrenas_pm_text( '_alrenas_hero_kicker', __( 'Eyebrow (small label above the title)', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'hero_kicker' ), __( 'e.g. Dynamic balance assessment + rehabilitation', 'alrenas' ) ); ?>
	<div class="alrenas-pm-row">
		<?php alrenas_pm_text( '_alrenas_hero_badge_title', __( 'Photo badge title', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'hero_badge_title' ), __( 'e.g. Progressive dynamic training', 'alrenas' ) ); ?>
		<?php alrenas_pm_text( '_alrenas_hero_badge_subtitle', __( 'Photo badge subtitle', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'hero_badge_subtitle' ), __( 'e.g. Adjust challenge as recovery advances', 'alrenas' ) ); ?>
	</div>
	<div class="alrenas-pm-row">
		<?php alrenas_pm_text( '_alrenas_hero_stat_value', __( 'Highlight stat value (optional)', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'hero_stat_value' ), __( 'e.g. 20°', 'alrenas' ) ); ?>
		<?php alrenas_pm_text( '_alrenas_hero_stat_label', __( 'Highlight stat label', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'hero_stat_label' ), __( 'e.g. controlled platform movement', 'alrenas' ) ); ?>
	</div>
	<?php
}

function alrenas_render_proof_meta_box( $post ) {
	echo '<p class="description">' . esc_html__( 'The 4 headline stats shown in a strip right under the hero.', 'alrenas' ) . '</p>';
	alrenas_pm_fixed_repeater(
		'proof_stats',
		ALRENAS_PRODUCT_PROOF_COUNT,
		array(
			'value' => array( 'label' => __( 'Stat value', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. 20 levels', 'alrenas' ) ),
			'label' => array( 'label' => __( 'Stat description', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. dynamic platform challenge in 1° adjustments', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'proof_stats', array() ),
		__( 'Stat', 'alrenas' )
	);
}

function alrenas_render_purpose_meta_box( $post ) {
	echo '<p class="description">' . esc_html__( 'The main narrative paragraph reuses this product\'s Description field (the main content editor above). This box is just the heading and the 4-step breakdown.', 'alrenas' ) . '</p>';
	alrenas_pm_text( '_alrenas_purpose_eyebrow', __( 'Eyebrow', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'purpose_eyebrow' ), __( 'e.g. Why choose dynamic balance training', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_purpose_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'purpose_heading' ), __( 'e.g. When static control is not enough, add challenge without losing clinical control.', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( '4-step breakdown:', 'alrenas' ) . '</p>';
	alrenas_pm_fixed_repeater(
		'purpose_items',
		ALRENAS_PRODUCT_PURPOSE_COUNT,
		array(
			'step'        => array( 'label' => __( 'Step label', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Baseline', 'alrenas' ) ),
			'title'       => array( 'label' => __( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Assess before increasing difficulty', 'alrenas' ) ),
			'description' => array( 'label' => __( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. Use static and dynamic assessments to understand the patient\'s current balance...', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'purpose_items', array() ),
		__( 'Step', 'alrenas' )
	);
}

function alrenas_render_workflow_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_workflow_eyebrow', __( 'Eyebrow', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'workflow_eyebrow' ), __( 'e.g. Clinical workflow', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_workflow_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'workflow_heading' ), __( 'e.g. Evaluate. Progress. Engage. Reassess.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_workflow_lead', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'workflow_lead' ), __( 'e.g. Al Balance Dyn is designed around a complete balance-rehabilitation cycle...', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( 'Add as many tabs as this product needs. Each tab shows either highlight pills or a single boxed callout below its description -- fill in whichever fits, leave the other blank.', 'alrenas' ) . '</p>';
	alrenas_pm_dynamic_repeater(
		'workflow_tabs',
		array(
			'label'         => array( 'label' => __( 'Tab label', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Assess', 'alrenas' ) ),
			'kicker'        => array( 'label' => __( 'Panel kicker', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Objective assessment', 'alrenas' ) ),
			'heading'       => array( 'label' => __( 'Panel heading', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Understand balance and postural control before planning the challenge.', 'alrenas' ) ),
			'description'   => array( 'label' => __( 'Panel description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. The testing module provides static and dynamic insight...', 'alrenas' ) ),
			'image_id'      => array( 'label' => __( 'Panel image', 'alrenas' ), 'type' => 'media', 'placeholder' => __( 'Select panel image', 'alrenas' ) ),
			'highlights'    => array( 'label' => __( 'Highlight pills (comma-separated, optional)', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Maze, Plane, Tennis, Cognitive Memory', 'alrenas' ) ),
			'callout_title' => array( 'label' => __( 'Boxed callout title (optional)', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Automatic calibration', 'alrenas' ) ),
			'callout_text'  => array( 'label' => __( 'Boxed callout text', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. The system includes automatic platform and sensor calibration...', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'workflow_tabs', array() ),
		__( '+ Add tab', 'alrenas' )
	);
}

function alrenas_render_note_meta_box( $post ) {
	echo '<p class="description">' . esc_html__( 'A short highlighted callout band. One list item per line.', 'alrenas' ) . '</p>';
	alrenas_pm_text( '_alrenas_note_eyebrow', __( 'Eyebrow', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'note_eyebrow' ), __( 'e.g. Controlled progression', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_note_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'note_heading' ), __( 'e.g. Two independent tools for grading dynamic difficulty.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_note_paragraph', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'note_paragraph' ), __( 'e.g. Platform motion and pneumatic resistance do different jobs...', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_note_list', __( 'List items (one per line)', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'note_list' ), "Platform tilt up to 20° in all directions.\n20 dynamic levels with 1° adjustment.\n50 levels of pneumatic resistance." );
}

function alrenas_render_story_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_story_eyebrow', __( 'Eyebrow', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'story_eyebrow' ), __( 'e.g. Assessment-driven personalization', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_story_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'story_heading' ), __( 'e.g. Use the Limits of Stability result to help tailor the next stage.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_story_paragraph', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'story_paragraph' ), __( 'e.g. The current product page states that the software can use...', 'alrenas' ) );
	alrenas_pm_media( '_alrenas_story_image_id', __( 'Image', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'story_image_id', 0 ), __( 'Select feature image', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( 'Feature checklist (4 items):', 'alrenas' ) . '</p>';
	alrenas_pm_fixed_repeater(
		'feature_checks',
		ALRENAS_PRODUCT_FEATURE_COUNT,
		array(
			'title'       => array( 'label' => __( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Assessment and training in one interface', 'alrenas' ) ),
			'description' => array( 'label' => __( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. Move from testing into weight-bearing, weight-shift and control exercises.', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'feature_checks', array() ),
		__( 'Feature', 'alrenas' )
	);
}

/**
 * Software-showcase meta box: section intro + an add/remove-able list of
 * screens (title, description, 16:9 screenshot). Rendered as tabs on the
 * front end when there's more than one; a single screen just shows on its
 * own with no tab list.
 */
function alrenas_render_software_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_software_eyebrow', __( 'Eyebrow', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'software_eyebrow' ), __( 'e.g. Software', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_software_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'software_heading' ), __( 'e.g. See what the software can do.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_software_lead', __( 'Paragraph (optional)', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'software_lead' ), __( 'e.g. A closer look at the screens clinicians and patients use every day.', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( 'Add as many software screens as this product has -- one is fine, so is ten. Each needs a title, description, and a 16:9 screenshot.', 'alrenas' ) . '</p>';
	alrenas_pm_dynamic_repeater(
		'software_items',
		array(
			'title'       => array( 'label' => __( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Assessment dashboard', 'alrenas' ) ),
			'description' => array( 'label' => __( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. Review balance scores, patient history and session comparisons at a glance.', 'alrenas' ) ),
			'image_id'    => array( 'label' => __( 'Screenshot (16:9 recommended)', 'alrenas' ), 'type' => 'media', 'placeholder' => __( 'Select screenshot', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'software_items', array() ),
		__( '+ Add software screen', 'alrenas' )
	);
}

function alrenas_render_applications_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_applications_eyebrow', __( 'Eyebrow', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'applications_eyebrow' ), __( 'e.g. Where dynamic balance fits', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_applications_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'applications_heading' ), __( 'e.g. For rehabilitation programs that need progression beyond a stable surface.', 'alrenas' ) );
	alrenas_pm_fixed_repeater(
		'applications_items',
		ALRENAS_PRODUCT_APPLICATION_COUNT,
		array(
			'title'       => array( 'label' => __( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Orthopedic rehabilitation', 'alrenas' ) ),
			'description' => array( 'label' => __( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. Progress balance, weight transfer and proprioceptive control...', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'applications_items', array() ),
		__( 'Card', 'alrenas' )
	);
}

function alrenas_render_gallery_text_meta_box( $post ) {
	echo '<p class="description">' . esc_html__( 'Gallery images themselves use the standard WooCommerce Product image + Product gallery fields.', 'alrenas' ) . '</p>';
	alrenas_pm_text( '_alrenas_gallery_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'gallery_heading' ), __( 'e.g. See the system in detail.', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_gallery_lead', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'gallery_lead' ), __( 'e.g. Product, platform, clinical use and software views.', 'alrenas' ) );
}

function alrenas_render_details_text_meta_box( $post ) {
	echo '<p class="description">' . esc_html__( 'The accordion sections below this text are generated automatically from Product data > Attributes -- nothing to configure there.', 'alrenas' ) . '</p>';
	alrenas_pm_text( '_alrenas_details_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'details_heading' ), __( 'e.g. Key specifications for clinical and procurement review.', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_details_lead', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'details_lead' ), __( 'e.g. These values follow the current Alrenas product page.', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_certifications', __( 'Certification chips (comma-separated, optional)', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'certifications' ), __( 'e.g. CE, UK CE, 2-year warranty', 'alrenas' ) );
}

function alrenas_render_procurement_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_procurement_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'procurement_heading' ), __( 'e.g. Describe how you expect to use dynamic balance in your program.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_procurement_lead', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'procurement_lead' ), __( 'e.g. This helps Alrenas understand the clinical and support requirements behind the purchase.', 'alrenas' ) );
	alrenas_pm_fixed_repeater(
		'procurement_items',
		ALRENAS_PRODUCT_PROCUREMENT_COUNT,
		array(
			'title'       => array( 'label' => __( 'Title', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. Patient groups', 'alrenas' ) ),
			'description' => array( 'label' => __( 'Description', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. Neurological, orthopedic, geriatric, sports or mixed rehabilitation.', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'procurement_items', array() ),
		__( 'Question', 'alrenas' )
	);
}

function alrenas_render_faq_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_faq_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'faq_heading' ), __( 'e.g. Quick answers for clinical and procurement evaluation.', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( 'Add as many questions as needed. These also generate the page\'s FAQ structured data automatically.', 'alrenas' ) . '</p>';
	alrenas_pm_dynamic_repeater(
		'faq_items',
		array(
			'question' => array( 'label' => __( 'Question', 'alrenas' ), 'type' => 'text', 'placeholder' => __( 'e.g. What makes this different from a static balance platform?', 'alrenas' ) ),
			'answer'   => array( 'label' => __( 'Answer', 'alrenas' ), 'type' => 'textarea', 'placeholder' => __( 'e.g. It supports both static and dynamic assessment...', 'alrenas' ) ),
		),
		alrenas_get_product_meta( $post->ID, 'faq_items', array() ),
		__( '+ Add question', 'alrenas' )
	);
}

function alrenas_render_video_meta_box( $post ) {
	alrenas_pm_text( '_alrenas_video_url', __( 'YouTube video URL', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'video_url' ), 'https://www.youtube.com/watch?v=...' );
	echo '<p class="description">' . esc_html__( 'Leave blank to hide the video section on this product.', 'alrenas' ) . '</p>';
}

function alrenas_render_document_meta_box( $post ) {
	alrenas_pm_media( '_alrenas_document_id', __( 'Product document (PDF, etc.)', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'document_id', 0 ), __( 'Select product document', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_documentation_kicker', __( 'Kicker', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'documentation_kicker' ), __( 'e.g. Product documentation', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_documentation_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'documentation_heading' ), __( 'e.g. Need the complete product information?', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_documentation_lead', __( 'Paragraph', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'documentation_lead' ), __( 'e.g. Download the current product document for technical review...', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( 'If no document is uploaded but this section has a heading, it still shows with a "Request Documentation" button linking to the inquiry form instead of a download link.', 'alrenas' ) . '</p>';
}

function alrenas_render_related_meta_box( $post ) {
	echo '<p class="description">' . esc_html__( 'The other 2 products shown here are pulled automatically from Site Content > Home — Products (same product, badge/kicker, and description already configured there). This box is just the section heading.', 'alrenas' ) . '</p>';
	alrenas_pm_text( '_alrenas_related_heading', __( 'Heading', 'alrenas' ), alrenas_get_product_meta( $post->ID, 'related_heading' ), __( 'e.g. Need a static platform or more supported standing?', 'alrenas' ) );
}

/* -------------------------------------------------------------------- */
/* Save                                                                   */
/* -------------------------------------------------------------------- */

/**
 * Save every field registered above.
 *
 * @param int $post_id Product ID.
 */
function alrenas_save_product_meta( $post_id ) {
	if ( ! isset( $_POST[ ALRENAS_PRODUCT_META_NONCE ] )
		|| ! wp_verify_nonce( wp_unslash( $_POST[ ALRENAS_PRODUCT_META_NONCE ] ), ALRENAS_PRODUCT_META_NONCE )
	) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'product' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array(
		'_alrenas_hero_kicker', '_alrenas_hero_badge_title', '_alrenas_hero_badge_subtitle', '_alrenas_hero_stat_value', '_alrenas_hero_stat_label',
		'_alrenas_purpose_eyebrow', '_alrenas_purpose_heading',
		'_alrenas_workflow_eyebrow', '_alrenas_workflow_heading',
		'_alrenas_note_eyebrow', '_alrenas_note_heading',
		'_alrenas_story_eyebrow', '_alrenas_story_heading',
		'_alrenas_software_eyebrow', '_alrenas_software_heading',
		'_alrenas_applications_eyebrow', '_alrenas_applications_heading',
		'_alrenas_gallery_heading', '_alrenas_gallery_lead',
		'_alrenas_details_heading', '_alrenas_details_lead', '_alrenas_certifications',
		'_alrenas_procurement_heading',
		'_alrenas_faq_heading',
		'_alrenas_video_url',
		'_alrenas_documentation_kicker', '_alrenas_documentation_heading',
		'_alrenas_related_heading',
	);

	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	$textarea_fields = array( '_alrenas_workflow_lead', '_alrenas_note_paragraph', '_alrenas_note_list', '_alrenas_story_paragraph', '_alrenas_software_lead', '_alrenas_procurement_lead', '_alrenas_documentation_lead' );

	foreach ( $textarea_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	$media_fields = array( '_alrenas_story_image_id', '_alrenas_document_id' );

	foreach ( $media_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, absint( $_POST[ $key ] ) );
		}
	}

	update_post_meta( $post_id, '_alrenas_proof_stats', alrenas_sanitize_pm_repeater( $_POST['_alrenas_proof_stats'] ?? null, ALRENAS_PRODUCT_PROOF_COUNT, array( 'value' => 'text', 'label' => 'text' ) ) );
	update_post_meta( $post_id, '_alrenas_purpose_items', alrenas_sanitize_pm_repeater( $_POST['_alrenas_purpose_items'] ?? null, ALRENAS_PRODUCT_PURPOSE_COUNT, array( 'step' => 'text', 'title' => 'text', 'description' => 'textarea' ) ) );
	update_post_meta( $post_id, '_alrenas_feature_checks', alrenas_sanitize_pm_repeater( $_POST['_alrenas_feature_checks'] ?? null, ALRENAS_PRODUCT_FEATURE_COUNT, array( 'title' => 'text', 'description' => 'textarea' ) ) );
	update_post_meta( $post_id, '_alrenas_applications_items', alrenas_sanitize_pm_repeater( $_POST['_alrenas_applications_items'] ?? null, ALRENAS_PRODUCT_APPLICATION_COUNT, array( 'title' => 'text', 'description' => 'textarea' ) ) );
	update_post_meta( $post_id, '_alrenas_procurement_items', alrenas_sanitize_pm_repeater( $_POST['_alrenas_procurement_items'] ?? null, ALRENAS_PRODUCT_PROCUREMENT_COUNT, array( 'title' => 'text', 'description' => 'textarea' ) ) );

	update_post_meta( $post_id, '_alrenas_workflow_tabs', alrenas_sanitize_pm_dynamic_repeater(
		$_POST['_alrenas_workflow_tabs'] ?? null,
		array( 'label' => 'required', 'kicker' => 'text', 'heading' => 'text', 'description' => 'textarea', 'image_id' => 'int', 'highlights' => 'text', 'callout_title' => 'text', 'callout_text' => 'text' )
	) );

	update_post_meta( $post_id, '_alrenas_faq_items', alrenas_sanitize_pm_dynamic_repeater(
		$_POST['_alrenas_faq_items'] ?? null,
		array( 'question' => 'required', 'answer' => 'textarea' )
	) );

	update_post_meta( $post_id, '_alrenas_software_items', alrenas_sanitize_pm_dynamic_repeater(
		$_POST['_alrenas_software_items'] ?? null,
		array( 'title' => 'required', 'description' => 'textarea', 'image_id' => 'int' )
	) );
}
add_action( 'save_post', 'alrenas_save_product_meta' );

/**
 * Sanitize a fixed-count 1-indexed repeater posted from
 * alrenas_pm_fixed_repeater().
 *
 * @param mixed $raw    Raw posted value.
 * @param int   $count  Highest valid 1-based index.
 * @param array $fields Map of sub-field key => 'text'|'textarea'.
 * @return array
 */
function alrenas_sanitize_pm_repeater( $raw, $count, $fields ) {
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
 * Sanitize an add/remove-able repeater posted from
 * alrenas_pm_dynamic_repeater(). Drops any row missing its 'required'
 * field (covers the leftover template row and rows left entirely blank),
 * and re-keys 1..N in submission order so front-end lookups stay
 * predictable regardless of which client-side indices were used.
 *
 * @param mixed $raw    Raw posted value.
 * @param array $fields Map of sub-field key => 'required'|'text'|'textarea'|'int'.
 * @return array
 */
function alrenas_sanitize_pm_dynamic_repeater( $raw, $fields ) {
	$rows = array();

	if ( empty( $raw ) || ! is_array( $raw ) ) {
		return $rows;
	}

	foreach ( $raw as $row ) {
		$required_key = array_search( 'required', $fields, true );

		if ( false !== $required_key ) {
			$required_value = isset( $row[ $required_key ] ) ? trim( wp_unslash( $row[ $required_key ] ) ) : '';

			if ( '' === $required_value ) {
				continue;
			}
		}

		$clean = array();

		foreach ( $fields as $key => $type ) {
			$value = isset( $row[ $key ] ) ? wp_unslash( $row[ $key ] ) : '';

			switch ( $type ) {
				case 'textarea':
					$clean[ $key ] = sanitize_textarea_field( $value );
					break;
				case 'int':
					$clean[ $key ] = absint( $value );
					break;
				default:
					$clean[ $key ] = sanitize_text_field( $value );
			}
		}

		$rows[] = $clean;
	}

	$reindexed = array();
	foreach ( array_values( $rows ) as $i => $row ) {
		$reindexed[ $i + 1 ] = $row;
	}

	return $reindexed;
}
