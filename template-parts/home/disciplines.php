<?php
/**
 * Homepage clinical disciplines tabs.
 *
 * @package Alrenas
 */

$eyebrow = alrenas_get_site_content( 'discipline_eyebrow', esc_html__( 'Across rehabilitation specialties', 'alrenas' ) );
$heading = alrenas_get_site_content( 'discipline_heading', esc_html__( 'Built for different patients. One goal: better movement.', 'alrenas' ) );

$tab_defaults = array(
	1 => array(
		'label'       => esc_html__( 'Neurological', 'alrenas' ),
		'kicker'      => esc_html__( 'Neurological rehabilitation', 'alrenas' ),
		'title'       => esc_html__( 'Support balance, motor control and confidence through structured training.', 'alrenas' ),
		'description' => esc_html__( 'Use objective assessment and guided exercises to support patients who need repeatable balance and postural-control rehabilitation.', 'alrenas' ),
	),
	2 => array(
		'label'       => esc_html__( 'Orthopedic', 'alrenas' ),
		'kicker'      => esc_html__( 'Orthopedic rehabilitation', 'alrenas' ),
		'title'       => esc_html__( 'Rebuild stability and controlled movement after injury or surgery.', 'alrenas' ),
		'description' => esc_html__( 'Progress from assessment to controlled exercise with adaptable training that supports mobility, strength and proprioception.', 'alrenas' ),
	),
	3 => array(
		'label'       => esc_html__( 'Geriatric', 'alrenas' ),
		'kicker'      => esc_html__( 'Geriatric rehabilitation', 'alrenas' ),
		'title'       => esc_html__( 'Make balance training safer and progress easier to follow.', 'alrenas' ),
		'description' => esc_html__( 'Support fall-risk assessment, safe standing and balance exercises with clear feedback for patients and care teams.', 'alrenas' ),
	),
	4 => array(
		'label'       => esc_html__( 'Physiotherapy', 'alrenas' ),
		'kicker'      => esc_html__( 'Physiotherapy', 'alrenas' ),
		'title'       => esc_html__( 'Bring assessment, treatment and progress tracking into one workflow.', 'alrenas' ),
		'description' => esc_html__( 'Give physiotherapists practical tools for personalized balance exercises, repeatable testing and patient progress reporting.', 'alrenas' ),
	),
);

$saved_tabs = alrenas_get_site_content( 'discipline_tabs', array() );

$tabs = array();

foreach ( $tab_defaults as $index => $default ) {
	$saved = isset( $saved_tabs[ $index ] ) ? $saved_tabs[ $index ] : array();

	$tabs[] = array(
		'key'         => 'tab-' . $index,
		'label'       => ! empty( $saved['label'] ) ? $saved['label'] : $default['label'],
		'kicker'      => ! empty( $saved['kicker'] ) ? $saved['kicker'] : $default['kicker'],
		'title'       => ! empty( $saved['title'] ) ? $saved['title'] : $default['title'],
		'description' => ! empty( $saved['description'] ) ? $saved['description'] : $default['description'],
	);
}
?>
<section class="section disciplines" aria-labelledby="disciplines-title">
	<div class="container disciplines-head reveal">
		<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<h2 id="disciplines-title"><?php echo esc_html( $heading ); ?></h2>
	</div>

	<div class="container discipline-layout reveal" data-tabs>
		<div class="discipline-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Rehabilitation specialties', 'alrenas' ); ?>">
			<?php foreach ( $tabs as $i => $tab ) : ?>
				<button class="discipline-tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-tab="<?php echo esc_attr( $tab['key'] ); ?>"><?php echo esc_html( $tab['label'] ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php foreach ( $tabs as $i => $tab ) : ?>
			<div class="discipline-panel" data-panel="<?php echo esc_attr( $tab['key'] ); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>><span><?php echo esc_html( $tab['kicker'] ); ?></span><h3><?php echo esc_html( $tab['title'] ); ?></h3><p><?php echo esc_html( $tab['description'] ); ?></p></div>
		<?php endforeach; ?>
	</div>
</section>
