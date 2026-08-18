<?php
/**
 * Reusable blog card.
 *
 * @package Alrenas
 */

$permalink  = get_permalink();
$title      = get_the_title();
$categories = get_the_category();
$category   = $categories ? $categories[0] : null;
$slugs      = wp_list_pluck( $categories, 'slug' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card reveal' ); ?> data-category="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
	<a class="article-card-media" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'alrenas' ), $title ) ); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			echo get_the_post_thumbnail(
				get_the_ID(),
				'large',
				array(
					'loading' => 'lazy',
				)
			);
		}
		?>
	</a>

	<div class="article-card-copy">
		<div class="article-meta">
			<?php if ( $category ) : ?>
				<span class="category"><?php echo esc_html( $category->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'd M' ) ); ?></time>
		</div>

		<h3><?php echo esc_html( $title ); ?></h3>
		<p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<a href="<?php echo esc_url( $permalink ); ?>" class="text-link">
			<?php esc_html_e( 'Read article', 'alrenas' ); ?> <span aria-hidden="true">→</span>
		</a>
	</div>
</article>

