<?php
/**
 * Homepage rehabilitation specialties strip.
 *
 * @package Alrenas
 */

$care_text = alrenas_get_site_content( 'care_strip_text', esc_html__( 'Supporting rehabilitation across the continuum of care', 'alrenas' ) );
$care_tags = array_filter( array_map( 'trim', explode( ',', alrenas_get_site_content( 'care_strip_tags', esc_html__( 'Balance rehabilitation, Fall-risk assessment, Postural control, Mobility training, Muscle strengthening', 'alrenas' ) ) ) ) );
?>
<section class="care-strip" aria-label="<?php esc_attr_e( 'Rehabilitation specialties', 'alrenas' ); ?>">
	<div class="container care-strip-inner">
		<p><?php echo esc_html( $care_text ); ?></p>
		<?php if ( $care_tags ) : ?>
			<div class="care-list">
				<?php foreach ( $care_tags as $tag ) : ?>
					<span><?php echo esc_html( $tag ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

