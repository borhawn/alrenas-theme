<?php
/** Products introduction. @package Alrenas */

$eyebrow    = alrenas_get_site_content( 'products_intro_eyebrow', esc_html__( 'Not a catalogue of commodities', 'alrenas' ) );
$heading    = alrenas_get_site_content( 'products_intro_heading', esc_html__( 'Choose by clinical need, not by a price tag.', 'alrenas' ) );
$lead       = alrenas_get_site_content( 'products_intro_lead', esc_html__( 'Alrenas systems are configured for professional rehabilitation environments. Instead of fixed online pricing, we discuss your facility, patient population, training requirements and support needs before preparing a quotation.', 'alrenas' ) );
$link_label = alrenas_get_site_content( 'products_intro_link_label', esc_html__( 'Discuss your requirements', 'alrenas' ) );
$contact_url = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<section class="section products-intro"><div class="container products-intro-grid"><div class="reveal"><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2></div><div class="reveal"><p><?php echo esc_html( $lead ); ?></p><?php if ( $contact_url && $link_label ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="text-link"><?php echo esc_html( $link_label ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div></div></section>
