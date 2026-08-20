<?php
/**
 * Not-found template.
 *
 * @package Alrenas
 */

get_header();
$contact_url = home_url( '/contact' );
$products_url = home_url( '/products' );
$blogs_url    = home_url( '/blogs' );
$about_url    = home_url( '/about' );
?>
<main id="primary" class="site-main">
	<section class="error-wrap">
		<div class="error-card reveal">
			<div class="error-code"><span>404</span> <?php esc_html_e( 'Page not found', 'alrenas' ); ?></div>
			<h1><?php esc_html_e( 'We could not find the page you were looking for.', 'alrenas' ); ?></h1>
			<p><?php esc_html_e( 'The link may have changed or the page may no longer be available. You can return to the main site or continue to the most useful areas below.', 'alrenas' ); ?></p>
			<div class="error-actions"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Back to Home', 'alrenas' ); ?></a><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Contact Alrenas', 'alrenas' ); ?></a></div>
			<div class="error-paths">
				<a href="<?php echo esc_url( $products_url ); ?>" class="error-path"><strong><?php esc_html_e( 'Rehabilitation Systems', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Explore balance assessment, dynamic rehabilitation and supported standing.', 'alrenas' ); ?></span></a>
				<a href="<?php echo esc_url( $blogs_url ); ?>" class="error-path"><strong><?php esc_html_e( 'Clinical Resources', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Read about balance, physiotherapy, fall risk and rehabilitation practice.', 'alrenas' ); ?></span></a>
				<a href="<?php echo esc_url( $about_url ); ?>" class="error-path"><strong><?php esc_html_e( 'About Alrenas', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Learn about our approach to rehabilitation technology and patient-centered care.', 'alrenas' ); ?></span></a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
