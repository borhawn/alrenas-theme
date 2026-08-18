<?php /** Resources archive heading and filters. @package Alrenas */
$categories = get_categories( array( 'hide_empty' => true, 'number' => 6 ) );
?>
<div class="resource-filter reveal"><div><span class="eyebrow"><?php esc_html_e( 'Latest insights', 'alrenas' ); ?></span><h2><?php esc_html_e( 'Explore the rehabilitation library.', 'alrenas' ); ?></h2></div><?php if ( $categories ) : ?><div class="filter-tabs" aria-label="<?php esc_attr_e( 'Filter articles', 'alrenas' ); ?>"><button class="filter-tab is-active" type="button" data-filter="all"><?php esc_html_e( 'All', 'alrenas' ); ?></button><?php foreach ( $categories as $category ) : ?><button class="filter-tab" type="button" data-filter="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></button><?php endforeach; ?></div><?php endif; ?></div>

