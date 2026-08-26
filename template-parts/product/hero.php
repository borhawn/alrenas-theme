<?php
/**
 * Single-product hero.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$product_id = $product->get_id();
$kicker     = alrenas_get_product_meta( $product_id, 'hero_kicker' );
$badge_title    = alrenas_get_product_meta( $product_id, 'hero_badge_title' );
$badge_subtitle = alrenas_get_product_meta( $product_id, 'hero_badge_subtitle' );
$stat_value = alrenas_get_product_meta( $product_id, 'hero_stat_value' );
$stat_label = alrenas_get_product_meta( $product_id, 'hero_stat_label' );

$title       = $product->get_name();
$title_words = explode( ' ', $title );
$title_last  = array_pop( $title_words );
$title_rest  = implode( ' ', $title_words );

$lead = $product->get_short_description();
$tags = wc_get_product_tag_list( $product_id, ',' );
$tags = $tags ? array_filter( array_map( 'trim', explode( ',', wp_strip_all_tags( $tags ) ) ) ) : array();

$primary_label   = alrenas_get_site_content( 'sp_primary_label', esc_html__( 'Get a Quote', 'alrenas' ) );
$quote_note      = alrenas_get_site_content( 'sp_quote_note', esc_html__( 'Quoted according to your facility, intended use, configuration and support requirements — not sold as a fixed-price ecommerce product.', 'alrenas' ) );

$document_id  = (int) alrenas_get_product_meta( $product_id, 'document_id', 0 );
$document_url = $document_id ? wp_get_attachment_url( $document_id ) : '';

if ( $document_url ) {
	$secondary_label = alrenas_get_site_content( 'sp_documentation_download_label', esc_html__( 'Download Documentation', 'alrenas' ) );
	$secondary_url   = $document_url;
	$secondary_attrs = 'target="_blank" rel="noopener"';
} else {
	$secondary_label = alrenas_get_site_content( 'sp_documentation_request_label', esc_html__( 'Request Documentation', 'alrenas' ) );
	$secondary_url   = '#inquiry';
	$secondary_attrs = 'data-inquiry-intent="quote"';
}

$image_id  = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : wc_placeholder_img_src( 'large' );
?>
<section class="product-hero">
	<div class="container product-hero-grid">
		<div class="product-hero-copy reveal">
			<?php get_template_part( 'template-parts/global/breadcrumbs', null, array(
				'current_label' => $title,
				'parent_label'  => esc_html__( 'Products', 'alrenas' ),
				'parent_url'    => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '',
			) ); ?>
			<?php if ( $kicker ) : ?><span class="eyebrow"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
			<h1><?php echo esc_html( $title_rest ? $title_rest . ' ' : '' ); ?><span><?php echo esc_html( $title_last ); ?></span></h1>
			<?php if ( $lead ) : ?><p class="product-hero-lead"><?php echo esc_html( wp_strip_all_tags( $lead ) ); ?></p><?php endif; ?>

			<div class="product-hero-actions">
				<a href="#inquiry" class="btn btn-primary"><?php echo esc_html( $primary_label ); ?></a>
				<a href="<?php echo esc_url( $secondary_url ); ?>" class="btn btn-secondary" <?php echo $secondary_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string, not user input. ?>><?php echo esc_html( $secondary_label ); ?></a>
			</div>

			<?php if ( $quote_note ) : ?>
				<p class="quote-note"><span aria-hidden="true">i</span> <?php echo esc_html( $quote_note ); ?></p>
			<?php endif; ?>

			<?php if ( $tags ) : ?>
				<div class="product-clinical-tags" aria-label="<?php esc_attr_e( 'Clinical capabilities', 'alrenas' ); ?>">
					<?php foreach ( $tags as $tag ) : ?><span><?php echo esc_html( $tag ); ?></span><?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="product-hero-media reveal">
			<div class="product-hero-visual">
				<div class="product-hero-halo" aria-hidden="true"></div>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
				<?php if ( $badge_title ) : ?>
					<div class="clinical-badge clinical-badge--top">
						<span class="status-dot" aria-hidden="true"></span>
						<div><strong><?php echo esc_html( $badge_title ); ?></strong><small><?php echo esc_html( $badge_subtitle ); ?></small></div>
					</div>
				<?php endif; ?>
				<?php if ( $stat_value ) : ?>
					<div class="clinical-badge clinical-badge--bottom">
						<strong><?php echo esc_html( $stat_value ); ?></strong>
						<span><?php echo esc_html( $stat_label ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
