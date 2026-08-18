<?php
/** Resources archive hero. @package Alrenas */
$hero_posts = get_posts( array( 'numberposts' => 3, 'post_status' => 'publish', 'suppress_filters' => false ) );
?>
<section class="page-hero blog-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => single_post_title( '', false ) ) ); ?>
	<span class="eyebrow"><?php esc_html_e( 'Rehabilitation resources', 'alrenas' ); ?></span><h1><?php esc_html_e( 'Practical thinking for better movement and recovery.', 'alrenas' ); ?></h1><p class="lead"><?php esc_html_e( 'Articles on balance assessment, physiotherapy, mobility, fall risk and the role of rehabilitation technology in clinical care.', 'alrenas' ); ?></p>
</div><div class="hero-panel-soft reveal"><div class="blog-hero-art"><?php foreach ( $hero_posts as $post_item ) : ?><figure><?php echo get_the_post_thumbnail( $post_item, 'large' ); ?></figure><?php endforeach; ?></div></div></div></section>

