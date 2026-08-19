<?php
/** Auto-generated H2/H3 table of contents. @package Alrenas */
$items = alrenas_get_article_toc_items( get_the_ID() );
?>
<aside class="article-toc reveal" data-toc>
	<?php if ( $items ) : ?>
		<strong><?php esc_html_e( 'In this article', 'alrenas' ); ?></strong>
		<nav>
			<?php foreach ( $items as $item ) : ?>
				<a href="#<?php echo esc_attr( $item['anchor'] ); ?>" class="<?php echo 3 === $item['level'] ? 'is-sub' : ''; ?>"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>
</aside>
