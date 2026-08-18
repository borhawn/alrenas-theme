<?php
/**
 * Header call to action.
 *
 * @package Alrenas
 */
?>
<?php
$cta_label = get_theme_mod( 'alrenas_header_cta_label', __( 'Request a Demo', 'alrenas' ) );
$cta_url   = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<?php if ( $cta_label && $cta_url ) : ?>
	<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary header-cta">
		<?php echo esc_html( $cta_label ); ?>
	</a>
<?php endif; ?>
