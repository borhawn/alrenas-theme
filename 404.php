<?php
/**
 * Not-found template.
 *
 * @package Alrenas
 */

get_header();
$shop_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
$posts_page   = (int) get_option( 'page_for_posts' );
$posts_url    = $posts_page ? get_permalink( $posts_page ) : '';
$about_page   = (int) get_theme_mod( 'alrenas_about_page_id' );
$about_url    = $about_page ? get_permalink( $about_page ) : '';
$contact_url  = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<main id="primary" class="site-main">
	<section class="error-wrap">
		<div class="error-card reveal">
			<div class="error-code"><span>404</span> <?php esc_html_e( 'Page not found', 'alrenas' ); ?></div>
			<h1><?php esc_html_e( 'We could not find the page you were looking for.', 'alrenas' ); ?></h1>
			<p><?php esc_html_e( 'The link may have changed or the page may no longer be available. You can return to the main site or continue to the most useful areas below.', 'alrenas' ); ?></p>
			<div class="error-actions"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Back to Home', 'alrenas' ); ?></a><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Contact Alrenas', 'alrenas' ); ?></a><?php endif; ?></div>
			<div class="error-paths">
				<?php if ( $shop_url ) : ?><a href="<?php echo esc_url( $shop_url ); ?>" class="error-path"><strong><?php esc_html_e( 'Rehabilitation Systems', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Explore balance assessment, dynamic rehabilitation and supported standing.', 'alrenas' ); ?></span></a><?php endif; ?>
				<?php if ( $posts_url ) : ?><a href="<?php echo esc_url( $posts_url ); ?>" class="error-path"><strong><?php esc_html_e( 'Clinical Resources', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Read about balance, physiotherapy, fall risk and rehabilitation practice.', 'alrenas' ); ?></span></a><?php endif; ?>
				<?php if ( $about_url ) : ?><a href="<?php echo esc_url( $about_url ); ?>" class="error-path"><strong><?php esc_html_e( 'About Alrenas', 'alrenas' ); ?></strong><span><?php esc_html_e( 'Learn about our approach to rehabilitation technology and patient-centered care.', 'alrenas' ); ?></span></a><?php endif; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
