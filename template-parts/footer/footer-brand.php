<?php
/**
 * Footer branding.
 *
 * @package Alrenas
 */
?>
<?php
$site_name         = get_bloginfo( 'name' );
$logo_id           = (int) get_theme_mod( 'custom_logo' );
$site_icon         = get_site_icon_url( 260 );
$footer_description = alrenas_get_site_content( 'footer_description', get_bloginfo( 'description' ) );
?>
<div class="footer-brand">
	<?php if ( $logo_id || $site_icon ) : ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand brand--footer" rel="home" aria-label="<?php echo esc_attr( $site_name ); ?>">
			<?php if ( $logo_id ) : ?>
				<?php echo alrenas_logo_image_html( $logo_id, $site_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?>
			<?php else : ?>
				<img class="brand-image" src="<?php echo esc_url( $site_icon ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
			<?php endif; ?>
		</a>
	<?php endif; ?>

	<?php if ( $footer_description ) : ?>
		<p><?php echo nl2br( esc_html( $footer_description ) ); ?></p>
	<?php endif; ?>
</div>
