<?php
/**
 * Homepage rehabilitation introduction.
 *
 * @package Alrenas
 */

$eyebrow    = alrenas_get_site_content( 'process_eyebrow', esc_html__( 'Designed around rehabilitation', 'alrenas' ) );
$heading    = alrenas_get_site_content( 'process_heading', esc_html__( 'From assessment to progress, one clearer path to recovery.', 'alrenas' ) );
$lead       = alrenas_get_site_content( 'process_lead', esc_html__( 'Alrenas systems help healthcare professionals assess balance objectively, guide patients through targeted rehabilitation exercises and follow progress over time.', 'alrenas' ) );
$link_label = alrenas_get_site_content( 'process_link_label', esc_html__( 'See the systems', 'alrenas' ) );
$link_url   = alrenas_get_site_content( 'process_link_url', '#products' );

$step_defaults = array(
	1 => array(
		'kicker'  => esc_html__( 'Step 01', 'alrenas' ),
		'title'   => esc_html__( 'Assess', 'alrenas' ),
		'variant' => '',
	),
	2 => array(
		'kicker'  => esc_html__( 'Step 02', 'alrenas' ),
		'title'   => esc_html__( 'Train', 'alrenas' ),
		'variant' => 'care-card--teal',
	),
	3 => array(
		'kicker'  => esc_html__( 'Step 03', 'alrenas' ),
		'title'   => esc_html__( 'Follow progress', 'alrenas' ),
		'variant' => 'care-card--warm',
	),
);

$saved_steps = alrenas_get_site_content( 'care_steps', array() );
?>
<section class="section intro" id="care">
	<div class="container intro-grid">
		<div class="section-heading reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2><?php echo esc_html( $heading ); ?></h2>
		</div>
		<div class="intro-copy reveal">
			<p><?php echo esc_html( $lead ); ?></p>
			<?php if ( $link_label ) : ?>
				<a href="<?php echo esc_url( $link_url ); ?>" class="text-link"><?php echo esc_html( $link_label ); ?> <span aria-hidden="true">→</span></a>
			<?php endif; ?>
		</div>
	</div>

	<div class="container care-cards">
		<?php foreach ( $step_defaults as $index => $default ) : ?>
			<?php
			$saved    = isset( $saved_steps[ $index ] ) ? $saved_steps[ $index ] : array();
			$kicker   = ! empty( $saved['kicker'] ) ? $saved['kicker'] : $default['kicker'];
			$title    = ! empty( $saved['title'] ) ? $saved['title'] : $default['title'];
			$media_id = ! empty( $saved['media_id'] ) ? (int) $saved['media_id'] : 0;
			?>
			<article class="care-card <?php echo esc_attr( $default['variant'] ); ?> reveal">
				<div class="care-card-body">
					<?php if ( $kicker ) : ?><span class="care-card-kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
					<h3><?php echo esc_html( $title ); ?></h3>
				</div>
				<?php if ( $media_id ) : ?>
					<div class="care-card-media">
						<?php echo alrenas_render_media_html( $media_id, 'care-card-media-el' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping helper. ?>
					</div>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>
