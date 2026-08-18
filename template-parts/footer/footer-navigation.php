<?php
/**
 * Footer menu columns.
 *
 * @package Alrenas
 */
?>
<div class="footer-menu footer-menu--products">
	<h3><?php esc_html_e( 'Products', 'alrenas' ); ?></h3>
	<?php if ( function_exists( 'wc_get_products' ) ) : ?>
		<?php
		$latest_products = wc_get_products(
			array(
				'limit'      => 3,
				'status'     => 'publish',
				'visibility' => 'catalog',
				'orderby'    => 'date',
				'order'      => 'DESC',
			)
		);
		?>
		<?php foreach ( $latest_products as $product ) : ?>
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<div class="footer-menu footer-menu--company">
	<h3><?php esc_html_e( 'Company', 'alrenas' ); ?></h3>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'footer',
			'container'      => false,
			'fallback_cb'    => false,
			'depth'          => 1,
		)
	);
	?>
</div>

<div class="footer-menu footer-menu--contact">
	<h3><?php esc_html_e( 'Contact', 'alrenas' ); ?></h3>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'contact',
			'container'      => false,
			'fallback_cb'    => false,
			'depth'          => 1,
		)
	);
	?>
</div>
