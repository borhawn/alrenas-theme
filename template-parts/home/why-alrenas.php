<?php
/**
 * Homepage reasons to choose Alrenas (Site Content > Home — Why Alrenas).
 *
 * @package Alrenas
 */

$about_page_id = (int) get_theme_mod( 'alrenas_about_page_id' );
$about_url     = $about_page_id ? get_permalink( $about_page_id ) : '';

$eyebrow = alrenas_get_site_content( 'why_eyebrow', esc_html__( 'Why Alrenas', 'alrenas' ) );
$heading = alrenas_get_site_content( 'why_heading', esc_html__( 'A rehabilitation technology partner for clinical professionals.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'why_lead', esc_html__( 'Alrenas develops balance assessment and rehabilitation systems with a focus on practical clinical use, patient-specific progression and measurable information.', 'alrenas' ) );

$item_defaults = array(
	1 => array(
		'title'       => esc_html__( 'Safety-Focused Rehabilitation', 'alrenas' ),
		'description' => esc_html__( 'Supportive structures, adjustable configurations and controlled progression help professionals create appropriate environments for supervised balance rehabilitation.', 'alrenas' ),
	),
	2 => array(
		'title'       => esc_html__( 'Patient-Specific Progression', 'alrenas' ),
		'description' => esc_html__( 'Move from assessment to increasingly challenging exercises according to the patient\'s abilities rather than using the same training conditions for every patient.', 'alrenas' ),
	),
	3 => array(
		'title'       => esc_html__( 'Objective Assessment & Progress Review', 'alrenas' ),
		'description' => esc_html__( 'Use repeatable measurements, assessment results and rehabilitation records to complement clinical observation and make changes over time easier to evaluate.', 'alrenas' ),
	),
	4 => array(
		'title'       => esc_html__( 'Clinical Research & Professional Support', 'alrenas' ),
		'description' => esc_html__( 'We welcome collaboration with hospitals, clinicians, universities and research teams studying balance, postural control, rehabilitation and human performance. Research access, product evaluation, technical documentation, staff training and implementation requirements can be discussed directly with the Alrenas team.', 'alrenas' ),
	),
);

$saved_items = alrenas_get_site_content( 'why_items', array() );
?>
<section class="section why" id="why">
	<div class="container why-grid">
		<div class="why-copy reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<p><?php echo esc_html( $lead ); ?></p>
			<?php if ( $about_url ) : ?><a href="<?php echo esc_url( $about_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'About Alrenas', 'alrenas' ); ?></a><?php endif; ?>
		</div>
		<div class="why-list reveal">
			<?php foreach ( $item_defaults as $index => $default ) : ?>
				<?php
				$saved       = isset( $saved_items[ $index ] ) ? $saved_items[ $index ] : array();
				$title       = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
				$description = ! empty( $saved['description'] ) ? $saved['description'] : $default['description'];
				?>
				<article><span class="check" aria-hidden="true">✓</span><div><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $description ); ?></p></div></article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

