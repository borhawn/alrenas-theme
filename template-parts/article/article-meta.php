<?php /** Article publication metadata. @package Alrenas */ ?>
<div class="article-meta">
	<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	<span><?php printf( esc_html__( '%d min read', 'alrenas' ), alrenas_reading_time() ); ?></span>
	<span><?php echo esc_html( get_the_author() ); ?></span>
</div>
