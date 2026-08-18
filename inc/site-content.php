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
}
add_action( 'admin_init', 'alrenas_site_content_settings' );

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
