<?php
/** Product quotation process. @package Alrenas */

$eyebrow = alrenas_get_site_content( 'products_quote_eyebrow', esc_html__( 'How quotations work', 'alrenas' ) );
$heading = alrenas_get_site_content( 'products_quote_heading', esc_html__( 'A configuration based on your clinical environment.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'products_quote_lead', esc_html__( 'Medical equipment is rarely a one-size-fits-all purchase. We use a short consultation to understand what you actually need before pricing the system.', 'alrenas' ) );

$step_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Tell us about your facility', 'alrenas' ),
		'description' => esc_html__( 'Hospital, clinic, rehabilitation center, research institution or distributor.', 'alrenas' ),
	),
	2 => array(
		'title'       => esc_html__( 'Define the clinical need', 'alrenas' ),
		'description' => esc_html__( 'Patient groups, assessment requirements, training goals and expected use.', 'alrenas' ),
	),
	3 => array(
		'title'       => esc_html__( 'Receive the right proposal', 'alrenas' ),
		'description' => esc_html__( 'We prepare the appropriate system configuration, support scope and quotation.', 'alrenas' ),
	),
);

$saved_steps = alrenas_get_site_content( 'products_quote_steps', array() );
?>
<section class="section quote-explainer"><div class="container quote-grid"><div class="reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2><p><?php echo esc_html( $lead ); ?></p></div><div class="quote-steps reveal">
	<?php foreach ( $step_defaults as $index => $default ) : ?>
		<?php
		$saved       = isset( $saved_steps[ $index ] ) ? $saved_steps[ $index ] : array();
		$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
		$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
		?>
		<div class="quote-step"><span><?php echo esc_html( str_pad( $index, 2, '0', STR_PAD_LEFT ) ); ?></span><div><h4><?php echo esc_html( $title ); ?></h4><p><?php echo esc_html( $description ); ?></p></div></div>
	<?php endforeach; ?>
</div></div></section>
