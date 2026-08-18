<?php /** Products introduction. @package Alrenas */
$contact_url = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<section class="section products-intro"><div class="container products-intro-grid"><div class="reveal"><span class="eyebrow"><?php esc_html_e( 'Not a catalogue of commodities', 'alrenas' ); ?></span><h2><?php esc_html_e( 'Choose by clinical need, not by a price tag.', 'alrenas' ); ?></h2></div><div class="reveal"><p><?php esc_html_e( 'Alrenas systems are configured for professional rehabilitation environments. Instead of fixed online pricing, we discuss your facility, patient population, training requirements and support needs before preparing a quotation.', 'alrenas' ); ?></p><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="text-link"><?php esc_html_e( 'Discuss your requirements', 'alrenas' ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div></div></section>

