<?php
/** About quality band. @package Alrenas */

$eyebrow = alrenas_get_site_content( 'about_quality_eyebrow', esc_html__( 'Quality and responsibility', 'alrenas' ) );
$heading = alrenas_get_site_content( 'about_quality_heading', esc_html__( 'Professional equipment has to earn clinical trust.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'about_quality_lead', esc_html__( 'Alrenas emphasizes safety, reliability, patient comfort, usability and compliance with relevant international requirements. Product-specific certifications and specifications are available on each system page and in technical documentation.', 'alrenas' ) );

$badge_defaults = array(
	1 => array(
		'value' => esc_html__( 'CE', 'alrenas' ),
		'label' => esc_html__( 'Product conformity', 'alrenas' ),
	),
	2 => array(
		'value' => esc_html__( 'UKCA', 'alrenas' ),
		'label' => esc_html__( 'Market conformity', 'alrenas' ),
	),
	3 => array(
		'value' => esc_html__( '2 years', 'alrenas' ),
		'label' => esc_html__( 'Parts and labor warranty*', 'alrenas' ),
	),
	4 => array(
		'value' => esc_html__( 'Global', 'alrenas' ),
		'label' => esc_html__( 'Distributor & partner support', 'alrenas' ),
	),
);

$saved_badges = alrenas_get_site_content( 'about_quality_badges', array() );
?>
<section class="section quality-band"><div class="container quality-grid"><div class="quality-copy reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2><p><?php echo esc_html( $lead ); ?></p></div><div class="quality-badges reveal">
	<?php foreach ( $badge_defaults as $index => $default ) : ?>
		<?php
		$saved = isset( $saved_badges[ $index ] ) ? $saved_badges[ $index ] : array();
		$value = ! empty( $saved['value'] ) ? $saved['value'] : $default['value'];
		$label = ! empty( $saved['label'] ) ? $saved['label'] : $default['label'];
		?>
		<div class="quality-badge"><strong><?php echo esc_html( $value ); ?></strong><span><?php echo esc_html( $label ); ?></span></div>
	<?php endforeach; ?>
</div></div></section>
