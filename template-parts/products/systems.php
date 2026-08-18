<?php
/** WooCommerce systems list in the landing-page design. @package Alrenas */
if ( ! function_exists( 'wc_get_products' ) ) { return; }
$systems = wc_get_products( array( 'limit' => 3, 'status' => 'publish', 'visibility' => 'catalog', 'orderby' => 'date', 'order' => 'DESC' ) );
if ( ! $systems ) { return; }
$contact_url = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<section class="section solutions" id="systems"><div class="container"><div class="solutions-head reveal"><div><span class="eyebrow"><?php esc_html_e( 'Rehabilitation pathways', 'alrenas' ); ?></span><h2><?php esc_html_e( 'From objective assessment to supported standing.', 'alrenas' ); ?></h2></div><p><?php esc_html_e( 'Each system answers a different clinical need while sharing a focus on safe progression, feedback and measurable rehabilitation.', 'alrenas' ); ?></p></div><div class="solution-list">
	<?php foreach ( $systems as $product ) : $terms = get_the_terms( $product->get_id(), 'product_cat' ); ?>
		<article <?php wc_product_class( 'solution-card reveal', $product ); ?>><div class="solution-media"><?php if ( $terms && ! is_wp_error( $terms ) ) : ?><span class="solution-label"><?php echo esc_html( $terms[0]->name ); ?></span><?php endif; ?><?php echo wp_kses_post( $product->get_image( 'woocommerce_single' ) ); ?></div><div class="solution-copy"><span class="kicker"><?php echo esc_html( $product->get_sku() ? sprintf( __( 'System %s', 'alrenas' ), $product->get_sku() ) : __( 'Rehabilitation system', 'alrenas' ) ); ?></span><h3><?php echo esc_html( $product->get_name() ); ?></h3><div class="product-description"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div>
		<?php if ( $terms && ! is_wp_error( $terms ) ) : ?><div class="chips"><?php foreach ( array_slice( $terms, 0, 3 ) as $term ) : $term_url = get_term_link( $term ); ?><?php if ( ! is_wp_error( $term_url ) ) : ?><a class="chip" href="<?php echo esc_url( $term_url ); ?>"><?php echo esc_html( $term->name ); ?></a><?php else : ?><span class="chip"><?php echo esc_html( $term->name ); ?></span><?php endif; ?><?php endforeach; ?></div><?php endif; ?>
		<div class="solution-actions"><a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="btn btn-primary"><?php esc_html_e( 'View System', 'alrenas' ); ?></a><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="text-link"><?php esc_html_e( 'Request a quote', 'alrenas' ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div></div></article>
	<?php endforeach; ?>
</div></div></section>
