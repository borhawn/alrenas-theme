<?php
/**
 * Homepage clinical-use story.
 *
 * @package Alrenas
 */

$eyebrow = alrenas_get_site_content( 'story_eyebrow', esc_html__( 'For real clinical environments', 'alrenas' ) );
$heading = alrenas_get_site_content( 'story_heading', esc_html__( 'Technology should support therapy, not get in the way of it.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'story_lead', esc_html__( 'Every interaction is built around the practical needs of rehabilitation: patient safety, adjustable support, clear feedback and repeatable clinical assessment.', 'alrenas' ) );

$main_image_id  = (int) alrenas_get_site_content( 'story_main_image_id', 0 );
$small_image_id = (int) alrenas_get_site_content( 'story_small_image_id', 0 );

$main_image  = $main_image_id
	? wp_get_attachment_image( $main_image_id, 'large', false, array( 'alt' => esc_attr__( 'Close-up of the Alrenas dynamic balance platform', 'alrenas' ) ) )
	: sprintf( '<img src="%s" alt="%s">', esc_url( get_theme_file_uri( 'assets/images/patient-foot-support.webp' ) ), esc_attr__( 'Close-up of the Alrenas dynamic balance platform', 'alrenas' ) );

$small_image = $small_image_id
	? wp_get_attachment_image( $small_image_id, 'medium', false, array( 'alt' => esc_attr__( 'Rehabilitation training session with Alrenas equipment', 'alrenas' ) ) )
	: sprintf( '<img src="%s" alt="%s">', esc_url( get_theme_file_uri( 'assets/images/rehab-session.webp' ) ), esc_attr__( 'Rehabilitation training session with Alrenas equipment', 'alrenas' ) );

$point_defaults = array(
	1 => array(
		'title' => esc_html__( 'Patient-centered', 'alrenas' ),
		'text'  => esc_html__( 'Adapt treatment to different abilities and rehabilitation stages.', 'alrenas' ),
	),
	2 => array(
		'title' => esc_html__( 'Clinician-friendly', 'alrenas' ),
		'text'  => esc_html__( 'Clear workflows for assessment, exercise and reporting.', 'alrenas' ),
	),
	3 => array(
		'title' => esc_html__( 'Measurable', 'alrenas' ),
		'text'  => esc_html__( 'Track results and compare patient progress over time.', 'alrenas' ),
	),
);

$saved_points = alrenas_get_site_content( 'story_points', array() );
?>
<section class="section patient-story">
	<div class="container story-grid">
		<div class="story-media reveal">
			<?php echo $main_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping (wp_get_attachment_image or esc_url/esc_attr sprintf above). ?>
			<div class="story-small-image"><?php echo $small_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- same as above. ?></div>
		</div>
		<div class="story-copy reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<p><?php echo esc_html( $lead ); ?></p>
			<div class="story-points">
				<?php foreach ( $point_defaults as $index => $default ) : ?>
					<?php
					$saved = isset( $saved_points[ $index ] ) ? $saved_points[ $index ] : array();
					$title = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
					$text  = ! empty( $saved['text'] ) ? $saved['text'] : $default['text'];
					?>
					<div><strong><?php echo esc_html( $title ); ?></strong><span><?php echo esc_html( $text ); ?></span></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

