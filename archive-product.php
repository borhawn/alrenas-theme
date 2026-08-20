<?php
/**
 * WooCommerce shop/product-archive template override.
 *
 * WooCommerce always routes whatever page is set as its Shop page through
 * this template (theme root archive-product.php takes priority over
 * WooCommerce's own bundled one -- that's the standard WC template
 * override mechanism, no extra wiring needed). Rather than fight that by
 * forcing a different template in, this template *is* the "Products
 * Landing" design: the same template parts as page-products.php, reusing
 * the identical Site Content data. The Shop page itself can be a blank,
 * unused page -- its own content/template selection is never rendered.
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php get_template_part( 'template-parts/products/hero' ); ?>
	<?php get_template_part( 'template-parts/products/introduction' ); ?>
	<?php get_template_part( 'template-parts/products/systems' ); ?>
	<?php get_template_part( 'template-parts/products/selector' ); ?>
	<?php get_template_part( 'template-parts/products/quote-process' ); ?>
	<?php get_template_part( 'template-parts/global/site-cta' ); ?>
</main>
<?php
get_footer();
