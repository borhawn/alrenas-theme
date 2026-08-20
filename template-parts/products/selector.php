<?php
/** Product selector guidance. @package Alrenas */

$eyebrow = alrenas_get_site_content( 'products_selector_eyebrow', esc_html__( 'A simple starting point', 'alrenas' ) );
$heading = alrenas_get_site_content( 'products_selector_heading', esc_html__( 'Which rehabilitation need are you addressing?', 'alrenas' ) );

$item_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Measure static balance', 'alrenas' ),
		'description' => esc_html__( 'For objective postural stability, weight distribution and fall-risk assessment.', 'alrenas' ),
		'chip'        => esc_html__( 'Stabilometric', 'alrenas' ),
		'chip_class'  => '',
	),
	2 => array(
		'title'       => esc_html__( 'Challenge dynamic control', 'alrenas' ),
		'description' => esc_html__( 'For progressive balance, coordination and proprioceptive training with adjustable difficulty.', 'alrenas' ),
		'chip'        => esc_html__( 'Al Balance Dyn', 'alrenas' ),
		'chip_class'  => 'teal',
	),
	3 => array(
		'title'       => esc_html__( 'Support standing safely', 'alrenas' ),
		'description' => esc_html__( 'For patients who need substantial support while rebuilding postural control and confidence.', 'alrenas' ),
		'chip'        => esc_html__( 'SBT', 'alrenas' ),
		'chip_class'  => 'warm',
	),
);

$saved_items = alrenas_get_site_content( 'products_selector_items', array() );
?>
<section class="section selector"><div class="container"><div class="selector-head reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2></div><div class="selector-grid reveal">
	<?php foreach ( $item_defaults as $index => $default ) : ?>
		<?php
		$saved       = isset( $saved_items[ $index ] ) ? $saved_items[ $index ] : array();
		$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
		$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
		$chip        = ! empty( $saved['chip'] ) ? $saved['chip'] : $default['chip'];
		?>
		<div class="selector-item"><strong><?php echo esc_html( $title ); ?></strong><p><?php echo esc_html( $description ); ?></p><?php if ( $chip ) : ?><span class="chip <?php echo esc_attr( $default['chip_class'] ); ?>"><?php echo esc_html( $chip ); ?></span><?php endif; ?></div>
	<?php endforeach; ?>
</div></div></section>
