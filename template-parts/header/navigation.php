<?php
/**
 * Primary navigation.
 *
 * @package Alrenas
 */
?>
<nav class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'alrenas' ); ?>" data-nav>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'menu_id'        => 'primary-menu',
			'menu_class'     => 'site-nav',
			'container'      => false,
			'fallback_cb'    => false,
			'depth'          => 2,
		)
	);
	?>
</nav>

