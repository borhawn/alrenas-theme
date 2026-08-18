<?php
/**
 * Visual breadcrumbs without SEO metadata.
 *
 * @package Alrenas
 */

$current_label = $args['current_label'] ?? get_the_title();
$parent_label  = $args['parent_label'] ?? '';
$parent_url    = $args['parent_url'] ?? '';
?>
<div class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'alrenas' ); ?>">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'alrenas' ); ?></a>
	<span aria-hidden="true">/</span>
	<?php if ( $parent_label && $parent_url ) : ?>
		<a href="<?php echo esc_url( $parent_url ); ?>"><?php echo esc_html( $parent_label ); ?></a>
		<span aria-hidden="true">/</span>
	<?php endif; ?>
	<strong><?php echo esc_html( $current_label ); ?></strong>
</div>
