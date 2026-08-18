<?php
/** Single article hero. @package Alrenas */
$categories = get_the_category(); $category = $categories ? $categories[0] : null; $posts_page = (int) get_option( 'page_for_posts' ); $posts_url = $posts_page ? get_permalink( $posts_page ) : '';
?>
<section class="article-hero"><div class="container"><div class="article-hero-inner reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => get_the_title(), 'parent_label' => $posts_page ? get_the_title( $posts_page ) : '', 'parent_url' => $posts_url ) ); ?>
	<?php if ( $category ) : ?><span class="eyebrow"><?php echo esc_html( $category->name ); ?></span><?php endif; ?><?php the_title( '<h1>', '</h1>' ); ?><?php if ( has_excerpt() ) : ?><p class="article-hero-lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?><?php get_template_part( 'template-parts/article/article-meta' ); ?>
</div><?php if ( has_post_thumbnail() ) : ?><div class="article-cover reveal"><?php the_post_thumbnail( 'full' ); ?></div><?php endif; ?></div></section>
