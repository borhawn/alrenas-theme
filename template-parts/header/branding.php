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
		<?php elseif ( $site_icon ) : ?>
			<img class="brand-image" src="<?php echo esc_url( $site_icon ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
		<?php else : ?>
			<span class="brand-name"><?php echo esc_html( $site_name ); ?></span>
		<?php endif; ?>
	</a>
</div>
