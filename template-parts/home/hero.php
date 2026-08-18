<?php
/**
 * Homepage hero.
 *
 * @package Alrenas
 */

$front_page_id = (int) get_queried_object_id();
$hero_image_id = get_post_thumbnail_id( $front_page_id );
$hero_products = function_exists( 'wc_get_products' ) ? wc_get_products(
	array(
		'limit'      => 1,
		'status'     => 'publish',
		'featured'   => true,
		'visibility' => 'catalog',
	)
) : array();
$hero_product = $hero_products ? $hero_products[0] : null;
?>
<section class="hero">
	<div class="container hero-grid">
		<div class="hero-copy reveal">
			<span class="eyebrow"><?php esc_html_e( 'Rehabilitation technology for clinical care', 'alrenas' ); ?></span>
			<h1><?php esc_html_e( 'Helping patients move with more', 'alrenas' ); ?> <span><?php esc_html_e( 'confidence.', 'alrenas' ); ?></span></h1>
			<p class="hero-lead"><?php esc_html_e( 'Advanced rehabilitation systems for balance assessment, physiotherapy and guided training — designed to support safer, measurable recovery.', 'alrenas' ); ?></p>
			<div class="hero-actions">
				<a href="#products" class="btn btn-primary"><?php esc_html_e( 'Explore Rehabilitation Devices', 'alrenas' ); ?></a>
				<a href="#contact" class="btn btn-secondary"><?php esc_html_e( 'Talk to Our Team', 'alrenas' ); ?></a>
			</div>
			<div class="hero-audiences" aria-label="<?php esc_attr_e( 'Clinical fields', 'alrenas' ); ?>">
				<span><?php esc_html_e( 'Physiotherapy', 'alrenas' ); ?></span>
				<span><?php esc_html_e( 'Neurological Rehab', 'alrenas' ); ?></span>
				<span><?php esc_html_e( 'Orthopedic Rehab', 'alrenas' ); ?></span>
				<span><?php esc_html_e( 'Geriatrics', 'alrenas' ); ?></span>
			</div>
		</div>

		<div class="hero-media reveal">
			<div class="hero-photo">
				<?php if ( $hero_image_id ) : ?>
					<?php echo wp_get_attachment_image( $hero_image_id, 'full' ); ?>
				<?php else : ?>
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/rehab-session.webp' ) ); ?>" alt="<?php esc_attr_e( 'Patient using an Alrenas rehabilitation system during balance training', 'alrenas' ); ?>">
				<?php endif; ?>
				<div class="hero-photo-caption">
					<span class="status-dot" aria-hidden="true"></span>
					<div><strong><?php esc_html_e( 'Guided rehabilitation', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Assessment, training and real-time feedback', 'alrenas' ); ?></span></div>
				</div>
			</div>
			<div class="hero-soft-shape" aria-hidden="true"></div>
			<?php if ( $hero_product ) : ?>
				<a class="hero-device-card" href="<?php echo esc_url( $hero_product->get_permalink() ); ?>">
					<?php echo wp_kses_post( $hero_product->get_image( 'woocommerce_thumbnail' ) ); ?>
					<div><span><?php esc_html_e( 'Featured system', 'alrenas' ); ?></span><strong><?php echo esc_html( $hero_product->get_name() ); ?></strong></div>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>

