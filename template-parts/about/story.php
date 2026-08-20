<?php
/** About story. @package Alrenas */

$eyebrow     = alrenas_get_site_content( 'about_story_eyebrow', esc_html__( 'Your partner in rehabilitation', 'alrenas' ) );
$heading     = alrenas_get_site_content( 'about_story_heading', esc_html__( 'Built for the full care environment, not just the device itself.', 'alrenas' ) );
$paragraph_1 = alrenas_get_site_content( 'about_story_paragraph_1', esc_html__( 'Our team brings together product development, technical knowledge and clinical perspectives to create solutions for physiotherapy, neurological and orthopedic rehabilitation, geriatrics and movement training.', 'alrenas' ) );
$paragraph_2 = alrenas_get_site_content( 'about_story_paragraph_2', esc_html__( 'We also support healthcare professionals with product guidance, training and ongoing communication so the system can be integrated into real clinical practice.', 'alrenas' ) );

$image_id = (int) alrenas_get_site_content( 'about_story_image_id', 0 );
$image    = $image_id
	? wp_get_attachment_image( $image_id, 'large', false, array( 'alt' => esc_attr__( 'Rehabilitation session with Alrenas equipment', 'alrenas' ) ) )
	: sprintf( '<img src="%s" alt="%s">', esc_url( get_theme_file_uri( 'assets/images/rehab-session.webp' ) ), esc_attr__( 'Rehabilitation session with Alrenas equipment', 'alrenas' ) );

$point_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Professional support', 'alrenas' ),
		'description' => esc_html__( 'Product guidance and training for healthcare teams.', 'alrenas' ),
	),
	2 => array(
		'title'       => esc_html__( 'International reach', 'alrenas' ),
		'description' => esc_html__( 'Products and partner support for customers in multiple markets.', 'alrenas' ),
	),
	3 => array(
		'title'       => esc_html__( 'Clinical collaboration', 'alrenas' ),
		'description' => esc_html__( 'Continuous improvement informed by healthcare use and rehabilitation needs.', 'alrenas' ),
	),
);

$saved_points = alrenas_get_site_content( 'about_story_points', array() );
?>
<section class="section about-story"><div class="container about-story-grid"><div class="about-story-image reveal"><?php echo wp_kses_post( $image ); ?></div><div class="about-story-copy reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2><p><?php echo esc_html( $paragraph_1 ); ?></p><p><?php echo esc_html( $paragraph_2 ); ?></p><div class="about-story-list">
	<?php foreach ( $point_defaults as $index => $default ) : ?>
		<?php
		$saved       = isset( $saved_points[ $index ] ) ? $saved_points[ $index ] : array();
		$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
		$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
		?>
		<div><span aria-hidden="true">✓</span><div><strong><?php echo esc_html( $title ); ?></strong><p><?php echo esc_html( $description ); ?></p></div></div>
	<?php endforeach; ?>
</div></div></div></section>
