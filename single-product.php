<?php
/**
 * Single product template override.
 *
 * Bypasses WooCommerce's default single-product content template (and its
 * long woocommerce_before/after_single_product_summary hook chain) in
 * favor of the theme's own curated layout, matching the al-balance-dyn.html
 * reference design. Cart/pricing/checkout are intentionally absent -- this
 * business quotes rather than sells online, matching every other product
 * touchpoint in the theme (homepage, /products).
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main product-page">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/product/hero' );
		get_template_part( 'template-parts/product/care-strip' );
		get_template_part( 'template-parts/product/intro' );
		get_template_part( 'template-parts/product/workflow' );
		get_template_part( 'template-parts/product/dynamic-care' );
		get_template_part( 'template-parts/product/clinical-setting' );
		get_template_part( 'template-parts/product/gallery' );
		get_template_part( 'template-parts/product/details' );
		get_template_part( 'template-parts/product/video' );
		get_template_part( 'template-parts/product/documentation' );
		get_template_part( 'template-parts/product/product-inquiry' );
	endwhile;
	?>
</main>
<?php
get_footer();
