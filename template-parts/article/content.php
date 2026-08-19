<?php
/** Single article body and sidebars. @package Alrenas */
$related_product = alrenas_get_related_product( get_the_ID() );
?>
<section class="article-shell"><div class="container article-layout">
	<?php get_template_part( 'template-parts/article/table-of-contents' ); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-body reveal' ); ?>><?php echo alrenas_get_article_content_with_anchors( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already-filtered post content, HTML anchors injected. ?><?php wp_link_pages(); ?></article>
	<aside class="article-aside reveal"><?php if ( $related_product ) : ?><div class="aside-card"><h4><?php esc_html_e( 'Related system', 'alrenas' ); ?></h4><p><?php echo esc_html( wp_strip_all_tags( $related_product->get_short_description() ) ); ?></p><a href="<?php echo esc_url( $related_product->get_permalink() ); ?>" class="text-link"><?php echo esc_html( $related_product->get_name() ); ?> <span aria-hidden="true">→</span></a></div><?php endif; ?><?php get_template_part( 'template-parts/article/share-links' ); ?></aside>
</div></section>

