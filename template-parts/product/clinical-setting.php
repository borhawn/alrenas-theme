<?php
/**
 * Single-product "built for these settings" section -- identical on every
 * product page, edited once under Site Content > Single Product Page.
 *
 * @package Alrenas
 */

$eyebrow = alrenas_get_site_content( 'sp_setting_eyebrow', esc_html__( 'Built for rehabilitation settings', 'alrenas' ) );
$heading = alrenas_get_site_content( 'sp_setting_heading', esc_html__( 'A system for clinicians who need both measurable assessment and adaptable therapy.', 'alrenas' ) );

$card_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Hospitals', 'alrenas' ),
		'description' => esc_html__( 'Support structured balance evaluation and rehabilitation within multidisciplinary care pathways.', 'alrenas' ),
		'variant'     => '',
		'icon'        => '<path d="M7 25V11h18v14M4 25h24M12 11V6h8v5M16 14v7M12.5 17.5h7"/>',
	),
	2 => array(
		'title'       => esc_html__( 'Physiotherapy', 'alrenas' ),
		'description' => esc_html__( 'Combine assessment data with targeted exercises, dynamic challenges and patient feedback.', 'alrenas' ),
		'variant'     => 'setting-card--teal',
		'icon'        => '<circle cx="16" cy="8" r="3"/><path d="M16 11v7m0 0-6 7m6-7 6 7m-6-10-7 2m7-2 7 2"/>',
	),
	3 => array(
		'title'       => esc_html__( 'Rehabilitation centers', 'alrenas' ),
		'description' => esc_html__( 'Use one system across neurological, orthopedic, geriatric and balance-focused programs.', 'alrenas' ),
		'variant'     => 'setting-card--warm',
		'icon'        => '<path d="M6 25h20M8 25V9h16v16M12 13h8M12 17h8M12 21h4"/>',
	),
);

$saved_cards = alrenas_get_site_content( 'sp_setting_cards', array() );
?>
<section class="section clinical-setting">
	<div class="container clinical-setting-head reveal">
		<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<h2><?php echo esc_html( $heading ); ?></h2>
	</div>
	<div class="container setting-grid">
		<?php foreach ( $card_defaults as $index => $default ) : ?>
			<?php
			$saved       = isset( $saved_cards[ $index ] ) ? $saved_cards[ $index ] : array();
			$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
			$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
			?>
			<article class="setting-card <?php echo esc_attr( $default['variant'] ); ?> reveal">
				<div class="setting-icon" aria-hidden="true"><svg viewBox="0 0 32 32"><?php echo $default['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed inline SVG path data, not user input. ?></svg></div>
				<h3><?php echo esc_html( $title ); ?></h3>
				<p><?php echo esc_html( $description ); ?></p>
			</article>
		<?php endforeach; ?>
	</div>
</section>
