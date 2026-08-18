<?php
/**
 * Homepage reasons to choose Alrenas.
 *
 * @package Alrenas
 */

$about_page_id = (int) get_theme_mod( 'alrenas_about_page_id' );
$about_url     = $about_page_id ? get_permalink( $about_page_id ) : '';
?>
<section class="section why" id="why">
	<div class="container why-grid">
		<div class="why-copy reveal">
			<span class="eyebrow"><?php esc_html_e( 'Why Alrenas', 'alrenas' ); ?></span>
			<h2><?php esc_html_e( 'A rehabilitation partner for clinicians and care providers.', 'alrenas' ); ?></h2>
			<p><?php esc_html_e( 'Alrenas develops rehabilitation devices for healthcare professionals and patients, with a focus on safety, effectiveness, comfort and practical clinical use.', 'alrenas' ); ?></p>
			<?php if ( $about_url ) : ?><a href="<?php echo esc_url( $about_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'About Alrenas', 'alrenas' ); ?></a><?php endif; ?>
		</div>
		<div class="why-list reveal">
			<article><span class="check" aria-hidden="true">✓</span><div><h3><?php esc_html_e( 'Safe rehabilitation', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Supportive device structures and guided workflows help clinicians create controlled training environments.', 'alrenas' ); ?></p></div></article>
			<article><span class="check" aria-hidden="true">✓</span><div><h3><?php esc_html_e( 'Personalized therapy', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Adapt exercises and protocols to individual patient needs and rehabilitation goals.', 'alrenas' ); ?></p></div></article>
			<article><span class="check" aria-hidden="true">✓</span><div><h3><?php esc_html_e( 'Objective progress', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Use repeatable measurement and reporting to evaluate changes throughout rehabilitation.', 'alrenas' ); ?></p></div></article>
			<article><span class="check" aria-hidden="true">✓</span><div><h3><?php esc_html_e( 'Professional support', 'alrenas' ); ?></h3><p><?php esc_html_e( 'Training, guidance and product assistance for healthcare professionals and rehabilitation facilities.', 'alrenas' ); ?></p></div></article>
		</div>
	</div>
</section>

