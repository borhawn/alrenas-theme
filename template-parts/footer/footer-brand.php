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
				<?php
				echo wp_get_attachment_image(
					$logo_id,
					'full',
					false,
					array(
						'class' => 'brand-image',
						'alt'   => $site_name,
					)
				);
				?>
			<?php else : ?>
				<img class="brand-image" src="<?php echo esc_url( $site_icon ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
			<?php endif; ?>
		</a>
	<?php endif; ?>

	<?php if ( $footer_description ) : ?>
		<p><?php echo nl2br( esc_html( $footer_description ) ); ?></p>
	<?php endif; ?>
</div>
