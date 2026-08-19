<?php
/**
 * Site branding.
 *
 * @package Alrenas
 */
?>
<?php
$site_name = get_bloginfo( 'name' );
$logo_id   = (int) get_theme_mod( 'custom_logo' );
$site_icon = get_site_icon_url( 260 );
?>
<div class="site-branding">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" rel="home" aria-label="<?php echo esc_attr( $site_name ); ?>">
		<?php if ( $logo_id ) : ?>
			<?php echo alrenas_logo_image_html( $logo_id, $site_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?>
		<?php elseif ( $site_icon ) : ?>
			<img class="brand-image" src="<?php echo esc_url( $site_icon ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
		<?php else : ?>
			<span class="brand-name"><?php echo esc_html( $site_name ); ?></span>
		<?php endif; ?>
	</a>
</div>
