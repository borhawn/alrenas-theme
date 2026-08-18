<?php
/** Contact-page hero. @package Alrenas */
$image_id = get_post_thumbnail_id();
?>
<section class="page-hero contact-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => get_the_title() ) ); ?>
	<span class="eyebrow"><?php esc_html_e( 'Contact Alrenas', 'alrenas' ); ?></span><h1><?php esc_html_e( 'Start with the rehabilitation need.', 'alrenas' ); ?></h1><p class="lead"><?php esc_html_e( 'Whether you are evaluating a device for a hospital, physiotherapy clinic, rehabilitation center or research project, tell us what you need to achieve. We’ll help you take the next step.', 'alrenas' ); ?></p><div class="page-hero-actions"><a href="#inquiry" class="btn btn-primary"><?php esc_html_e( 'Send an Inquiry', 'alrenas' ); ?></a></div>
</div><div class="hero-panel-soft reveal"><?php if ( $image_id ) : ?><?php echo wp_get_attachment_image( $image_id, 'full' ); ?><?php else : ?><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/contact-device.webp' ) ); ?>" alt="<?php esc_attr_e( 'Patient using Alrenas rehabilitation equipment', 'alrenas' ); ?>"><?php endif; ?></div></div></section>

