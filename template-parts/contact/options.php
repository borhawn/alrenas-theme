<?php
/** Contact inquiry options. @package Alrenas */

$option_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Product guidance', 'alrenas' ),
		'description' => esc_html__( 'Help choosing the right rehabilitation system.', 'alrenas' ),
	),
	2 => array(
		'title'       => esc_html__( 'Request a quote', 'alrenas' ),
		'description' => esc_html__( 'A proposal based on your facility and requirements.', 'alrenas' ),
	),
	3 => array(
		'title'       => esc_html__( 'Request a demo', 'alrenas' ),
		'description' => esc_html__( 'Evaluate a system for clinical or research use.', 'alrenas' ),
	),
	4 => array(
		'title'       => esc_html__( 'Technical support', 'alrenas' ),
		'description' => esc_html__( 'Questions about an existing Alrenas system.', 'alrenas' ),
	),
);

$saved_options = alrenas_get_site_content( 'contact_options_items', array() );
?>
<section class="contact-options"><div class="container"><div class="contact-option-grid reveal">
	<?php foreach ( $option_defaults as $index => $default ) : ?>
		<?php
		$saved       = isset( $saved_options[ $index ] ) ? $saved_options[ $index ] : array();
		$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
		$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
		?>
		<button type="button" class="contact-option<?php echo 1 === $index ? ' is-active' : ''; ?>" data-contact-intent="<?php echo esc_attr( $title ); ?>"><small><?php echo esc_html( str_pad( $index, 2, '0', STR_PAD_LEFT ) ); ?></small><strong><?php echo esc_html( $title ); ?></strong><span><?php echo esc_html( $description ); ?></span></button>
	<?php endforeach; ?>
</div></div></section>
