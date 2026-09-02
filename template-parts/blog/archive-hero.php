<?php
/** Resources archive hero. @package Alrenas */
$eyebrow = alrenas_get_site_content( 'blog_hero_eyebrow', esc_html__( 'Rehabilitation resources', 'alrenas' ) );
$heading = alrenas_get_site_content( 'blog_hero_heading', esc_html__( 'Practical thinking for better movement and recovery.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'blog_hero_lead', esc_html__( 'Articles on balance assessment, physiotherapy, mobility, fall risk and the role of rehabilitation technology in clinical care.', 'alrenas' ) );

$image_id = (int) alrenas_get_site_content( 'blog_hero_image_id', 0 );
$image    = $image_id
	? wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'blog-hero-img' ) )
	: sprintf( '<img class="blog-hero-img" src="%s" alt="%s">', esc_url( get_theme_file_uri( 'assets/images/blog-devices.webp' ) ), esc_attr__( 'Alrenas rehabilitation systems in a clinical environment', 'alrenas' ) );
?>
<section class="page-hero blog-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => single_post_title( '', false ) ) ); ?>
	<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h1><?php echo esc_html( $heading ); ?></h1><p class="lead"><?php echo esc_html( $lead ); ?></p>
</div><div class="hero-panel-soft reveal"><?php echo wp_kses_post( $image ); ?></div></div></section>

