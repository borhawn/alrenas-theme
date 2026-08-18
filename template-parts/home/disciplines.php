<?php
/**
 * Homepage clinical disciplines tabs.
 *
 * @package Alrenas
 */
?>
<section class="section disciplines" aria-labelledby="disciplines-title">
	<div class="container disciplines-head reveal">
		<span class="eyebrow"><?php esc_html_e( 'Across rehabilitation specialties', 'alrenas' ); ?></span>
		<h2 id="disciplines-title"><?php esc_html_e( 'Built for different patients. One goal: better movement.', 'alrenas' ); ?></h2>
	</div>

	<div class="container discipline-layout reveal" data-tabs>
		<div class="discipline-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Rehabilitation specialties', 'alrenas' ); ?>">
			<button class="discipline-tab is-active" type="button" role="tab" aria-selected="true" data-tab="neuro"><?php esc_html_e( 'Neurological', 'alrenas' ); ?></button>
			<button class="discipline-tab" type="button" role="tab" aria-selected="false" data-tab="ortho"><?php esc_html_e( 'Orthopedic', 'alrenas' ); ?></button>
			<button class="discipline-tab" type="button" role="tab" aria-selected="false" data-tab="geriatric"><?php esc_html_e( 'Geriatric', 'alrenas' ); ?></button>
			<button class="discipline-tab" type="button" role="tab" aria-selected="false" data-tab="physio"><?php esc_html_e( 'Physiotherapy', 'alrenas' ); ?></button>
		</div>
		<div class="discipline-panel" data-panel="neuro"><span><?php esc_html_e( 'Neurological rehabilitation', 'alrenas' ); ?></span><h3><?php esc_html_e( 'Support balance, motor control and confidence through structured training.', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Use objective assessment and guided exercises to support patients who need repeatable balance and postural-control rehabilitation.', 'alrenas' ); ?></p></div>
		<div class="discipline-panel" data-panel="ortho" hidden><span><?php esc_html_e( 'Orthopedic rehabilitation', 'alrenas' ); ?></span><h3><?php esc_html_e( 'Rebuild stability and controlled movement after injury or surgery.', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Progress from assessment to controlled exercise with adaptable training that supports mobility, strength and proprioception.', 'alrenas' ); ?></p></div>
		<div class="discipline-panel" data-panel="geriatric" hidden><span><?php esc_html_e( 'Geriatric rehabilitation', 'alrenas' ); ?></span><h3><?php esc_html_e( 'Make balance training safer and progress easier to follow.', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Support fall-risk assessment, safe standing and balance exercises with clear feedback for patients and care teams.', 'alrenas' ); ?></p></div>
		<div class="discipline-panel" data-panel="physio" hidden><span><?php esc_html_e( 'Physiotherapy', 'alrenas' ); ?></span><h3><?php esc_html_e( 'Bring assessment, treatment and progress tracking into one workflow.', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Give physiotherapists practical tools for personalized balance exercises, repeatable testing and patient progress reporting.', 'alrenas' ); ?></p></div>
	</div>
</section>

