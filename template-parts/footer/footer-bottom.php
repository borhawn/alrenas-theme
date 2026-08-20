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
			/* translators: 1: developer credit link, 2: current year */
			wp_kses_post( __( 'Designed / Developed by %1$s &nbsp; © %2$s Alrenas Technology', 'alrenas' ) ),
			'<a class="footer-credit-link" href="https://borhawn.dev" target="_blank" rel="noopener noreferrer">Borhan Milani</a>',
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
