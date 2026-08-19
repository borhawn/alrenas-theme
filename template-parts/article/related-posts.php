<?php
/** Related article cards. @package Alrenas */
$category_ids = wp_get_post_categories( get_the_ID() );
$related = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'category__in' => $category_ids, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
if ( ! $related->have_posts() ) { return; }
$posts_page = (int) get_option( 'page_for_posts' ); $posts_url = $posts_page ? get_permalink( $posts_page ) : '';
?>
<section class="section related-resources"><div class="container"><div class="related-head reveal"><div><span class="eyebrow"><?php esc_html_e( 'Continue reading', 'alrenas' ); ?></span><h2><?php esc_html_e( 'Related rehabilitation resources.', 'alrenas' ); ?></h2></div><?php if ( $posts_url ) : ?><a href="<?php echo esc_url( $posts_url ); ?>" class="text-link"><?php esc_html_e( 'View all resources', 'alrenas' ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div><div class="related-grid">
<?php while ( $related->have_posts() ) : $related->the_post(); $categories = get_the_category(); ?><article class="related-card reveal"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?><div><div class="article-meta"><?php if ( $categories ) : ?><span class="category"><?php echo esc_html( $categories[0]->name ); ?></span><?php endif; ?></div><h3><?php the_title(); ?></h3><p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p><a class="text-link stretched-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'alrenas' ); ?> <span aria-hidden="true">→</span></a></div></article><?php endwhile; ?>
</div></div></section>
<?php wp_reset_postdata(); ?>
