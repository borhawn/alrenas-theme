<?php
/** About values. @package Alrenas */

$eyebrow = alrenas_get_site_content( 'about_values_eyebrow', esc_html__( 'What guides our work', 'alrenas' ) );
$heading = alrenas_get_site_content( 'about_values_heading', esc_html__( 'Clinical value before technical spectacle.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'about_values_lead', esc_html__( 'The best rehabilitation technology should quietly make care better. These principles shape how we think about products, software and support.', 'alrenas' ) );

$card_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Safety and confidence', 'alrenas' ),
		'description' => esc_html__( 'Patients may be working at the edge of their current ability. Equipment should create a controlled environment where progress can happen without unnecessary fear.', 'alrenas' ),
	),
	2 => array(
		'title'       => esc_html__( 'Personalized rehabilitation', 'alrenas' ),
		'description' => esc_html__( 'Recovery differs from patient to patient. Adjustable difficulty and individualized programs help clinicians meet people where they are.', 'alrenas' ),
	),
	3 => array(
		'title'       => esc_html__( 'Measurable progress', 'alrenas' ),
		'description' => esc_html__( 'Repeatable assessment and reporting make change easier to understand, communicate and use in treatment planning.', 'alrenas' ),
	),
);

$saved_cards = alrenas_get_site_content( 'about_value_cards', array() );
?>
<section class="section care-values"><div class="container"><div class="care-values-head reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2><p><?php echo esc_html( $lead ); ?></p></div><div class="value-grid">
	<?php foreach ( $card_defaults as $index => $default ) : ?>
		<?php
		$saved       = isset( $saved_cards[ $index ] ) ? $saved_cards[ $index ] : array();
		$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
		$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
		?>
		<article class="value-card reveal"><span class="value-number"><?php echo esc_html( str_pad( $index, 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $description ); ?></p></article>
	<?php endforeach; ?>
</div></div></section>
