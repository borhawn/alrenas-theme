<?php
/** About mission. @package Alrenas */

$eyebrow     = alrenas_get_site_content( 'about_mission_eyebrow', esc_html__( 'Redefining recovery', 'alrenas' ) );
$heading     = alrenas_get_site_content( 'about_mission_heading', esc_html__( 'Designed to support the people inside the rehabilitation process.', 'alrenas' ) );
$paragraph_1 = alrenas_get_site_content( 'about_mission_paragraph_1', esc_html__( 'Alrenas specializes in rehabilitation equipment and physiotherapy devices for healthcare professionals, patients and rehabilitation organizations. Our goal is straightforward: create practical systems that make assessment clearer, therapy more adaptable and patient progress easier to follow.', 'alrenas' ) );
$paragraph_2 = alrenas_get_site_content( 'about_mission_paragraph_2', esc_html__( 'Technology matters, but it is never the point on its own. A rehabilitation device has to fit clinical workflows, feel safe to the patient and give the therapist useful information.', 'alrenas' ) );

$point_defaults = array(
	1 => array(
		'title'       => esc_html__( 'For clinicians', 'alrenas' ),
		'description' => esc_html__( 'Objective assessment, adaptable training and clearer progress information.', 'alrenas' ),
	),
	2 => array(
		'title'       => esc_html__( 'For patients', 'alrenas' ),
		'description' => esc_html__( 'Supportive, understandable rehabilitation that builds confidence as ability returns.', 'alrenas' ),
	),
	3 => array(
		'title'       => esc_html__( 'For facilities', 'alrenas' ),
		'description' => esc_html__( 'Reliable systems designed for professional use, training and long-term support.', 'alrenas' ),
	),
	4 => array(
		'title'       => esc_html__( 'For research', 'alrenas' ),
		'description' => esc_html__( 'Repeatable measurement and structured data that can support clinical investigation.', 'alrenas' ),
	),
);

$saved_points = alrenas_get_site_content( 'about_mission_points', array() );
?>
<section class="section about-mission"><div class="container about-mission-grid"><div class="about-mission-copy reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2></div><div class="about-mission-body reveal"><p><?php echo esc_html( $paragraph_1 ); ?></p><p><?php echo esc_html( $paragraph_2 ); ?></p><div class="mission-points">
	<?php foreach ( $point_defaults as $index => $default ) : ?>
		<?php
		$saved       = isset( $saved_points[ $index ] ) ? $saved_points[ $index ] : array();
		$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
		$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
		?>
		<div class="mission-point"><strong><?php echo esc_html( $title ); ?></strong><p><?php echo esc_html( $description ); ?></p></div>
	<?php endforeach; ?>
</div></div></div></section>
