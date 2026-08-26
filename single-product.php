<?php
/**
 * Single product template override.
 *
 * Bypasses WooCommerce's default single-product content template (and its
 * long woocommerce_before/after_single_product_summary hook chain) in
 * favor of the theme's own curated layout, matching the al-balance-dyn /
 * al-balance-stabilometric / standing-balance-trainer reference pages.
 * Cart/pricing/checkout are intentionally absent -- this business quotes
 * rather than sells online, matching every other product touchpoint in
 * the theme (homepage, /products).
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main product-page">
	<?php
	while ( have_posts() ) :
		the_post();
		// Ordered for engagement, not gatekeeping: hook with proof + purpose,
		// pay it off fast with video/gallery while interest is highest, then
		// go deeper (how it works, features, where it fits) before easing
		// into reference material (specs, before-you-quote, FAQ, docs) and
		// the conversion actions -- so nothing substantial is stranded at
		// the very top or buried at the very bottom.
		get_template_part( 'template-parts/product/hero' );
		get_template_part( 'template-parts/product/proof' );
		get_template_part( 'template-parts/product/purpose' );
		get_template_part( 'template-parts/product/video' );
		get_template_part( 'template-parts/product/gallery' );
		get_template_part( 'template-parts/product/workflow' );
		get_template_part( 'template-parts/product/story' );
		get_template_part( 'template-parts/product/applications' );
		get_template_part( 'template-parts/product/note-band' );
		get_template_part( 'template-parts/product/details' );
		get_template_part( 'template-parts/product/procurement' );
		get_template_part( 'template-parts/product/faq' );
		get_template_part( 'template-parts/product/documentation' );
		get_template_part( 'template-parts/product/related-products' );
		get_template_part( 'template-parts/product/product-inquiry' );
	endwhile;
	?>
</main>
<?php
get_footer();
