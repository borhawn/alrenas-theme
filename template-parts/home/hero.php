<?php
/**
 * Homepage hero — editable text plus a rotating, crossfading set of
 * image slides (Site Content > Home — Hero).
 *
 * @package Alrenas
 */

$eyebrow            = alrenas_get_site_content( 'hero_eyebrow', esc_html__( 'Rehabilitation technology for clinical care', 'alrenas' ) );
$headline           = alrenas_get_site_content( 'hero_headline', esc_html__( 'Helping patients move with more', 'alrenas' ) );
$headline_highlight = alrenas_get_site_content( 'hero_headline_highlight', esc_html__( 'confidence.', 'alrenas' ) );
$lead               = alrenas_get_site_content( 'hero_lead', esc_html__( 'Advanced rehabilitation systems for balance assessment, physiotherapy and guided training — designed to support safer, measurable recovery.', 'alrenas' ) );
$primary_label      = alrenas_get_site_content( 'hero_primary_label', esc_html__( 'Explore Rehabilitation Devices', 'alrenas' ) );
$primary_url        = alrenas_get_site_content( 'hero_primary_url', '#products' );
$secondary_label    = alrenas_get_site_content( 'hero_secondary_label', esc_html__( 'Talk to Our Team', 'alrenas' ) );
$secondary_url      = alrenas_get_site_content( 'hero_secondary_url', '#contact' );
$tags               = array_filter( array_map( 'trim', explode( ',', alrenas_get_site_content( 'hero_tags', esc_html__( 'Physiotherapy, Neurological Rehab, Orthopedic Rehab, Geriatrics', 'alrenas' ) ) ) ) );

$configured_slides = alrenas_get_site_content( 'hero_slides', array() );
$slides             = array();

foreach ( $configured_slides as $slide ) {
	if ( empty( $slide['image_id'] ) ) {
		continue;
	}

	$product = ! empty( $slide['product_id'] ) && function_exists( 'wc_get_product' ) ? wc_get_product( $slide['product_id'] ) : null;

	$slides[] = array(
		'image_id'        => (int) $slide['image_id'],
		'caption_title'   => $slide['caption_title'] ?? '',
		'caption_subtext' => $slide['caption_subtext'] ?? '',
		'product'         => $product instanceof WC_Product ? $product : null,
	);
}

// Fall back to the previous single-image behavior when nothing is configured yet.
if ( ! $slides ) {
	$front_page_id  = (int) get_queried_object_id();
	$fallback_image = get_post_thumbnail_id( $front_page_id );
	$fallback_products = function_exists( 'wc_get_products' ) ? wc_get_products(
		array(
			'limit'      => 1,
			'status'     => 'publish',
			'featured'   => true,
			'visibility' => 'catalog',
		)
	) : array();

	$slides[] = array(
		'image_id'        => $fallback_image,
		'image_html'      => $fallback_image ? '' : sprintf(
			'<img src="%s" alt="%s">',
			esc_url( get_theme_file_uri( 'assets/images/rehab-session.webp' ) ),
			esc_attr__( 'Patient using an Alrenas rehabilitation system during balance training', 'alrenas' )
		),
		'caption_title'   => esc_html__( 'Guided rehabilitation', 'alrenas' ),
		'caption_subtext' => esc_html__( 'Assessment, training and real-time feedback', 'alrenas' ),
		'product'         => $fallback_products ? $fallback_products[0] : null,
	);
}
?>
<section class="hero">
	<div class="container hero-grid">
		<div class="hero-copy reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h1><?php echo esc_html( $headline ); ?> <span><?php echo esc_html( $headline_highlight ); ?></span></h1>
			<p class="hero-lead"><?php echo esc_html( $lead ); ?></p>
			<div class="hero-actions">
				<?php if ( $primary_label && $primary_url ) : ?>
					<a href="<?php echo esc_url( $primary_url ); ?>" class="btn btn-primary"><?php echo esc_html( $primary_label ); ?></a>
				<?php endif; ?>
				<?php if ( $secondary_label && $secondary_url ) : ?>
					<a href="<?php echo esc_url( $secondary_url ); ?>" class="btn btn-secondary"><?php echo esc_html( $secondary_label ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $tags ) : ?>
				<div class="hero-audiences" aria-label="<?php esc_attr_e( 'Clinical fields', 'alrenas' ); ?>">
					<?php foreach ( $tags as $tag ) : ?>
						<span><?php echo esc_html( $tag ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="hero-media reveal" data-hero-slider>
			<div class="hero-soft-shape" aria-hidden="true"></div>

			<?php foreach ( $slides as $i => $slide ) : ?>
				<div class="hero-photo<?php echo 0 === $i ? ' is-active' : ''; ?>" data-hero-slide="<?php echo esc_attr( $i ); ?>">
					<?php echo ! empty( $slide['image_html'] ) ? $slide['image_html'] : wp_get_attachment_image( $slide['image_id'], 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- image_html is built with esc_url()/esc_attr() above; wp_get_attachment_image() is self-escaping. ?>
					<?php if ( $slide['caption_title'] || $slide['caption_subtext'] ) : ?>
						<div class="hero-photo-caption">
							<span class="hero-status-dot" aria-hidden="true"></span>
							<span class="hero-photo-caption-text">
								<?php if ( $slide['caption_title'] ) : ?><strong><?php echo esc_html( $slide['caption_title'] ); ?></strong><?php endif; ?>
								<?php if ( $slide['caption_subtext'] ) : ?><span><?php echo esc_html( $slide['caption_subtext'] ); ?></span><?php endif; ?>
							</span>
						</div>
					<?php endif; ?>
					<?php if ( $slide['product'] ) : ?>
						<a class="hero-product-chip" href="<?php echo esc_url( $slide['product']->get_permalink() ); ?>">
							<?php echo wp_kses_post( $slide['product']->get_image( 'woocommerce_thumbnail' ) ); ?>
							<span class="hero-product-chip-text">
								<em><?php esc_html_e( 'Featured system', 'alrenas' ); ?></em>
								<strong><?php echo esc_html( $slide['product']->get_name() ); ?></strong>
							</span>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
