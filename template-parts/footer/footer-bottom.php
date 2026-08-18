<?php
/**
 * Footer legal row.
 *
 * @package Alrenas
 */
?>
<div class="container footer-bottom">
	<span>
		<?php
		printf(
			esc_html__( '© %s Alrenas Technology', 'alrenas' ),
			esc_html( wp_date( 'Y' ) )
		);
		?>
	</span>
	<div>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'legal',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 1,
			)
		);
		?>
	</div>
</div>
