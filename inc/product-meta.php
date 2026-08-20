<?php
/**
 * Per-product content for the custom single-product template: hero
 * kicker/stat, care strip, workflow intro + tabs, dynamic-care section,
 * YouTube video, and a downloadable document. Everything here is specific
 * to one product; sitewide copy repeated across every product page lives
 * in Site Content instead (see the "Single Product Page" tab).
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_PRODUCT_CARE_STRIP_COUNT', 4 );
define( 'ALRENAS_PRODUCT_FEATURE_COUNT', 3 );
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
 * Register every product meta box.
 */
function alrenas_register_product_meta_boxes() {
	add_meta_box( 'alrenas-product-hero', esc_html__( 'Alrenas: Hero content', 'alrenas' ), 'alrenas_render_hero_meta_box', 'product', 'normal', 'default' );
	add_meta_box( 'alrenas-product-care-strip', esc_html__( 'Alrenas: Care strip (4 items)', 'alrenas' ), 'alrenas_render_care_strip_meta_box', 'product', 'normal', 'default' );
	add_meta_box( 'alrenas-product-workflow-intro', esc_html__( 'Alrenas: Workflow section intro', 'alrenas' ), 'alrenas_render_workflow_intro_meta_box', 'product', 'normal', 'default' );
	add_meta_box( 'alrenas-product-workflow-tabs', esc_html__( 'Alrenas: Workflow tabs', 'alrenas' ), 'alrenas_render_workflow_tabs_meta_box', 'product', 'normal', 'default' );
	add_meta_box( 'alrenas-product-dynamic-care', esc_html__( 'Alrenas: Feature spotlight section', 'alrenas' ), 'alrenas_render_dynamic_care_meta_box', 'product', 'normal', 'default' );
	add_meta_box( 'alrenas-product-video', esc_html__( 'Alrenas: Video', 'alrenas' ), 'alrenas_render_video_meta_box', 'product', 'normal', 'default' );
	add_meta_box( 'alrenas-product-document', esc_html__( 'Alrenas: Documentation', 'alrenas' ), 'alrenas_render_document_meta_box', 'product', 'normal', 'default' );
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
 * Inline admin CSS/JS: media pickers (image + any file) and the
 * add/remove-able workflow-tabs repeater.
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
		.alrenas-pm-repeater-item h4 { margin: 0 0 10px; display: flex; align-items: center; justify-content: space-between; }
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

		var repeaterRoot = document.getElementById( 'alrenas-workflow-tabs-repeater' );
		if ( repeaterRoot ) {
			var rows     = repeaterRoot.querySelector( '.alrenas-pm-repeater-rows' );
			var addBtn   = repeaterRoot.querySelector( '.alrenas-pm-repeater-add' );
			var template = repeaterRoot.querySelector( 'template' );
			var counter  = rows.querySelectorAll( '.alrenas-pm-repeater-item' ).length;

			function wireRow( row ) {
				var removeBtn = row.querySelector( '.alrenas-pm-repeater-remove' );
				if ( removeBtn ) {
					removeBtn.addEventListener( 'click', function () {
						row.remove();
					} );
				}
				row.querySelectorAll( '.alrenas-pm-media-field' ).forEach( initMediaPicker );
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
		}
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
 * Hero meta box: kicker + optional headline stat badge.
 */
function alrenas_render_hero_meta_box( $post ) {
	wp_nonce_field( ALRENAS_PRODUCT_META_NONCE, ALRENAS_PRODUCT_META_NONCE );
	$kicker     = alrenas_get_product_meta( $post->ID, 'hero_kicker' );
	$stat_value = alrenas_get_product_meta( $post->ID, 'hero_stat_value' );
	$stat_label = alrenas_get_product_meta( $post->ID, 'hero_stat_label' );
	?>
	<p class="description"><?php esc_html_e( 'The product title, lead paragraph, and clinical tags come from this product\'s Short description and Tags -- edit those in the usual WooCommerce fields above.', 'alrenas' ); ?></p>
	<?php alrenas_pm_text( '_alrenas_hero_kicker', __( 'Eyebrow (small label above the title)', 'alrenas' ), $kicker, __( 'e.g. Dynamic balance rehabilitation', 'alrenas' ) ); ?>
	<div class="alrenas-pm-row">
		<?php alrenas_pm_text( '_alrenas_hero_stat_value', __( 'Highlight stat value (optional)', 'alrenas' ), $stat_value, __( 'e.g. 20°', 'alrenas' ) ); ?>
		<?php alrenas_pm_text( '_alrenas_hero_stat_label', __( 'Highlight stat label', 'alrenas' ), $stat_label, __( 'e.g. controlled platform movement', 'alrenas' ) ); ?>
	</div>
	<?php
}

/**
 * Care-strip meta box: fixed 4 items (label + short description).
 */
function alrenas_render_care_strip_meta_box( $post ) {
	$items = alrenas_get_product_meta( $post->ID, 'care_strip', array() );
	for ( $i = 1; $i <= ALRENAS_PRODUCT_CARE_STRIP_COUNT; $i++ ) {
		$item = isset( $items[ $i ] ) ? $items[ $i ] : array();
		?>
		<div class="alrenas-pm-repeater-item">
			<h4><?php printf( /* translators: %d: item slot number. */ esc_html__( 'Item %d', 'alrenas' ), $i ); ?></h4>
			<div class="alrenas-pm-row">
				<?php alrenas_pm_text( '_alrenas_care_strip[' . $i . '][label]', __( 'Label', 'alrenas' ), $item['label'] ?? '', __( 'e.g. Assess', 'alrenas' ) ); ?>
				<?php alrenas_pm_text( '_alrenas_care_strip[' . $i . '][description]', __( 'Description', 'alrenas' ), $item['description'] ?? '', __( 'e.g. Static & dynamic balance', 'alrenas' ) ); ?>
			</div>
		</div>
		<?php
	}
}

/**
 * Workflow-section intro meta box (eyebrow + heading + lead).
 */
function alrenas_render_workflow_intro_meta_box( $post ) {
	$eyebrow = alrenas_get_product_meta( $post->ID, 'workflow_eyebrow' );
	$heading = alrenas_get_product_meta( $post->ID, 'workflow_heading' );
	$lead    = alrenas_get_product_meta( $post->ID, 'workflow_lead' );
	alrenas_pm_text( '_alrenas_workflow_eyebrow', __( 'Eyebrow', 'alrenas' ), $eyebrow, __( 'e.g. Clinical workflow', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_workflow_heading', __( 'Heading', 'alrenas' ), $heading, __( 'e.g. Evaluate. Rehabilitate. Keep patients engaged. Follow progress.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_workflow_lead', __( 'Paragraph', 'alrenas' ), $lead, __( 'e.g. Each part of the system supports a different stage of the rehabilitation process...', 'alrenas' ) );
}

/**
 * Workflow-tabs meta box: dynamic, add/remove-able. Each tab: label,
 * kicker, heading, description, image, optional highlight pills
 * (comma-separated), optional boxed callout (title + text).
 */
function alrenas_render_workflow_tabs_meta_box( $post ) {
	$tabs = alrenas_get_product_meta( $post->ID, 'workflow_tabs', array() );

	if ( ! $tabs ) {
		$tabs = array( array() ); // Start with one empty row.
	}
	?>
	<p class="description"><?php esc_html_e( 'Add as many tabs as this product needs. Each tab shows either highlight pills or a single boxed callout below its description -- fill in whichever fits, leave the other blank.', 'alrenas' ); ?></p>
	<div id="alrenas-workflow-tabs-repeater">
		<div class="alrenas-pm-repeater-rows">
			<?php foreach ( array_values( $tabs ) as $i => $tab ) : ?>
				<?php echo alrenas_render_workflow_tab_row( $i, $tab ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping helper. ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-primary alrenas-pm-repeater-add"><?php esc_html_e( '+ Add tab', 'alrenas' ); ?></button>
		<template><?php echo alrenas_render_workflow_tab_row( '__INDEX__', array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></template>
	</div>
	<?php
}

/**
 * Render one workflow-tab row's markup (used for existing rows and the
 * JS clone template alike).
 *
 * @param int|string $index Row index (numeric, or the literal '__INDEX__' placeholder for the template).
 * @param array      $tab   Saved row data.
 * @return string
 */
function alrenas_render_workflow_tab_row( $index, $tab ) {
	$name = '_alrenas_workflow_tabs[' . $index . ']';
	ob_start();
	?>
	<div class="alrenas-pm-repeater-item">
		<h4><?php esc_html_e( 'Tab', 'alrenas' ); ?> <button type="button" class="button-link-delete alrenas-pm-repeater-remove"><?php esc_html_e( 'Remove', 'alrenas' ); ?></button></h4>
		<div class="alrenas-pm-row">
			<?php alrenas_pm_text( $name . '[label]', __( 'Tab label', 'alrenas' ), $tab['label'] ?? '', __( 'e.g. Assess', 'alrenas' ) ); ?>
			<?php alrenas_pm_text( $name . '[kicker]', __( 'Panel kicker', 'alrenas' ), $tab['kicker'] ?? '', __( 'e.g. Objective assessment', 'alrenas' ) ); ?>
		</div>
		<?php alrenas_pm_text( $name . '[heading]', __( 'Panel heading', 'alrenas' ), $tab['heading'] ?? '', __( 'e.g. Understand balance and postural control before planning treatment.', 'alrenas' ) ); ?>
		<?php alrenas_pm_textarea( $name . '[description]', __( 'Panel description', 'alrenas' ), $tab['description'] ?? '', __( 'e.g. Use static and dynamic evaluations to create a clearer picture...', 'alrenas' ) ); ?>
		<?php alrenas_pm_media( $name . '[image_id]', __( 'Panel image', 'alrenas' ), $tab['image_id'] ?? 0, __( 'Select panel image', 'alrenas' ) ); ?>
		<?php alrenas_pm_text( $name . '[highlights]', __( 'Highlight pills (comma-separated, optional)', 'alrenas' ), $tab['highlights'] ?? '', __( 'e.g. Maze, Plane, Tennis, Cognitive Memory', 'alrenas' ) ); ?>
		<div class="alrenas-pm-row">
			<?php alrenas_pm_text( $name . '[callout_title]', __( 'Boxed callout title (optional)', 'alrenas' ), $tab['callout_title'] ?? '', __( 'e.g. Personalized programs', 'alrenas' ) ); ?>
			<?php alrenas_pm_text( $name . '[callout_text]', __( 'Boxed callout text', 'alrenas' ), $tab['callout_text'] ?? '', __( 'e.g. Limits of Stability results can be used to adapt training...', 'alrenas' ) ); ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * "Feature spotlight" section meta box: eyebrow/heading/paragraph, image +
 * caption, and a fixed 3-item feature list.
 */
function alrenas_render_dynamic_care_meta_box( $post ) {
	$eyebrow       = alrenas_get_product_meta( $post->ID, 'care_eyebrow' );
	$heading       = alrenas_get_product_meta( $post->ID, 'care_heading' );
	$paragraph     = alrenas_get_product_meta( $post->ID, 'care_paragraph' );
	$image_id      = alrenas_get_product_meta( $post->ID, 'care_image_id', 0 );
	$caption_label = alrenas_get_product_meta( $post->ID, 'care_caption_label' );
	$caption_text  = alrenas_get_product_meta( $post->ID, 'care_caption_text' );
	$features      = alrenas_get_product_meta( $post->ID, 'care_features', array() );

	alrenas_pm_text( '_alrenas_care_eyebrow', __( 'Eyebrow', 'alrenas' ), $eyebrow, __( 'e.g. Adjustable dynamic platform', 'alrenas' ) );
	alrenas_pm_text( '_alrenas_care_heading', __( 'Heading', 'alrenas' ), $heading, __( 'e.g. Progress difficulty while keeping therapy controlled.', 'alrenas' ) );
	alrenas_pm_textarea( '_alrenas_care_paragraph', __( 'Paragraph', 'alrenas' ), $paragraph, __( 'e.g. The platform can tilt in all directions up to 20 degrees...', 'alrenas' ) );
	alrenas_pm_media( '_alrenas_care_image_id', __( 'Image', 'alrenas' ), $image_id, __( 'Select feature image', 'alrenas' ) );
	?>
	<div class="alrenas-pm-row">
		<?php alrenas_pm_text( '_alrenas_care_caption_label', __( 'Image caption label (optional)', 'alrenas' ), $caption_label, __( 'e.g. Controlled progression', 'alrenas' ) ); ?>
		<?php alrenas_pm_text( '_alrenas_care_caption_text', __( 'Image caption text', 'alrenas' ), $caption_text, __( 'e.g. Dynamic challenge that can evolve with recovery.', 'alrenas' ) ); ?>
	</div>
	<p class="description"><?php esc_html_e( 'Feature list (3 items):', 'alrenas' ); ?></p>
	<?php
	for ( $i = 1; $i <= ALRENAS_PRODUCT_FEATURE_COUNT; $i++ ) {
		$feature = isset( $features[ $i ] ) ? $features[ $i ] : array();
		?>
		<div class="alrenas-pm-repeater-item">
			<h4><?php printf( /* translators: %d: feature slot number. */ esc_html__( 'Feature %d', 'alrenas' ), $i ); ?></h4>
			<?php alrenas_pm_text( '_alrenas_care_features[' . $i . '][title]', __( 'Title', 'alrenas' ), $feature['title'] ?? '', __( 'e.g. Gradual progression', 'alrenas' ) ); ?>
			<?php alrenas_pm_textarea( '_alrenas_care_features[' . $i . '][description]', __( 'Description', 'alrenas' ), $feature['description'] ?? '', __( 'e.g. Increase instability and resistance as balance and confidence improve.', 'alrenas' ) ); ?>
		</div>
		<?php
	}
}

/**
 * Video meta box: a single YouTube URL.
 */
function alrenas_render_video_meta_box( $post ) {
	$url = alrenas_get_product_meta( $post->ID, 'video_url' );
	alrenas_pm_text( '_alrenas_video_url', __( 'YouTube video URL', 'alrenas' ), $url, 'https://www.youtube.com/watch?v=...' );
	echo '<p class="description">' . esc_html__( 'Leave blank to hide the video section on this product.', 'alrenas' ) . '</p>';
}

/**
 * Documentation meta box: a single downloadable file.
 */
function alrenas_render_document_meta_box( $post ) {
	$document_id = alrenas_get_product_meta( $post->ID, 'document_id', 0 );
	alrenas_pm_media( '_alrenas_document_id', __( 'Product document (PDF, etc.)', 'alrenas' ), $document_id, __( 'Select product document', 'alrenas' ) );
	echo '<p class="description">' . esc_html__( 'Leave blank to hide the documentation section on this product.', 'alrenas' ) . '</p>';
}

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

	$text_fields = array( '_alrenas_hero_kicker', '_alrenas_hero_stat_value', '_alrenas_hero_stat_label', '_alrenas_workflow_eyebrow', '_alrenas_workflow_heading', '_alrenas_care_eyebrow', '_alrenas_care_heading', '_alrenas_care_caption_label', '_alrenas_care_caption_text', '_alrenas_video_url' );

	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	$textarea_fields = array( '_alrenas_workflow_lead', '_alrenas_care_paragraph' );

	foreach ( $textarea_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	$media_fields = array( '_alrenas_care_image_id', '_alrenas_document_id' );

	foreach ( $media_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, absint( $_POST[ $key ] ) );
		}
	}

	update_post_meta( $post_id, '_alrenas_care_strip', alrenas_sanitize_pm_repeater( $_POST['_alrenas_care_strip'] ?? null, ALRENAS_PRODUCT_CARE_STRIP_COUNT, array( 'label' => 'text', 'description' => 'text' ) ) );
	update_post_meta( $post_id, '_alrenas_care_features', alrenas_sanitize_pm_repeater( $_POST['_alrenas_care_features'] ?? null, ALRENAS_PRODUCT_FEATURE_COUNT, array( 'title' => 'text', 'description' => 'textarea' ) ) );

	$workflow_tabs = array();

	if ( ! empty( $_POST['_alrenas_workflow_tabs'] ) && is_array( $_POST['_alrenas_workflow_tabs'] ) ) {
		foreach ( $_POST['_alrenas_workflow_tabs'] as $tab ) {
			$label = isset( $tab['label'] ) ? sanitize_text_field( wp_unslash( $tab['label'] ) ) : '';

			if ( '' === $label ) {
				continue; // Drop empty rows (e.g. the leftover template row, or a row the admin left blank).
			}

			$workflow_tabs[] = array(
				'label'         => $label,
				'kicker'        => isset( $tab['kicker'] ) ? sanitize_text_field( wp_unslash( $tab['kicker'] ) ) : '',
				'heading'       => isset( $tab['heading'] ) ? sanitize_text_field( wp_unslash( $tab['heading'] ) ) : '',
				'description'   => isset( $tab['description'] ) ? sanitize_textarea_field( wp_unslash( $tab['description'] ) ) : '',
				'image_id'      => isset( $tab['image_id'] ) ? absint( $tab['image_id'] ) : 0,
				'highlights'    => isset( $tab['highlights'] ) ? sanitize_text_field( wp_unslash( $tab['highlights'] ) ) : '',
				'callout_title' => isset( $tab['callout_title'] ) ? sanitize_text_field( wp_unslash( $tab['callout_title'] ) ) : '',
				'callout_text'  => isset( $tab['callout_text'] ) ? sanitize_text_field( wp_unslash( $tab['callout_text'] ) ) : '',
			);
		}
	}

	// Re-key 1..N so front-end lookups (isset($tabs[1])) stay predictable.
	$reindexed = array();
	foreach ( array_values( $workflow_tabs ) as $i => $tab ) {
		$reindexed[ $i + 1 ] = $tab;
	}
	update_post_meta( $post_id, '_alrenas_workflow_tabs', $reindexed );
}
add_action( 'save_post', 'alrenas_save_product_meta' );

/**
 * Sanitize a fixed-count 1-indexed repeater posted from the simple
 * (non-dynamic) product meta box repeaters.
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
