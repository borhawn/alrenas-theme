<?php
/**
 * Homepage latest-posts section.
 *
 * @package Alrenas
 */

$latest_posts = new WP_Query(
	array_merge(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 2,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		),
		alrenas_featured_first_query_args()
	)
);

if ( ! $latest_posts->have_posts() ) {
	return;
}

$posts_page_id = (int) get_option( 'page_for_posts' );
$posts_url     = $posts_page_id ? get_permalink( $posts_page_id ) : '';
?>
<section class="section resources latest-posts" aria-labelledby="latest-posts-title">
	<div class="container">
		<div class="resource-filter reveal">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Latest insights', 'alrenas' ); ?></span>
				<h2 id="latest-posts-title"><?php esc_html_e( 'Explore the latest rehabilitation resources.', 'alrenas' ); ?></h2>
			</div>
			<?php if ( $posts_url ) : ?>
				<a class="text-link" href="<?php echo esc_url( $posts_url ); ?>">
					<?php esc_html_e( 'View all resources', 'alrenas' ); ?> <span aria-hidden="true">→</span>
				</a>
			<?php endif; ?>
		</div>

		<div class="article-grid">
			<?php while ( $latest_posts->have_posts() ) : ?>
				<?php $latest_posts->the_post(); ?>
				<?php get_template_part( 'template-parts/content/blog-card' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();

