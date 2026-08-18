<?php
/** Anchored block heading TOC. @package Alrenas */
$items = alrenas_collect_toc_items( parse_blocks( get_the_content() ) );
?>
<aside class="article-toc reveal" data-toc><?php if ( $items ) : ?><strong><?php esc_html_e( 'In this article', 'alrenas' ); ?></strong><nav><?php foreach ( $items as $item ) : ?><a href="#<?php echo esc_attr( $item['anchor'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a><?php endforeach; ?></nav><?php endif; ?></aside>

