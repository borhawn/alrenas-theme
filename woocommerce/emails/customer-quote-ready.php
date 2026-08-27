<?php
/**
 * Customer quote-ready email -- the priced, itemized quote.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<p>
<?php
if ( $order->get_billing_first_name() ) {
	/* translators: %s: Customer first name. */
	printf( esc_html__( 'Hi %s,', 'alrenas' ), esc_html( $order->get_billing_first_name() ) );
} else {
	esc_html_e( 'Hi,', 'alrenas' );
}
?>
</p>

<p><?php esc_html_e( 'Here\'s the quote you requested, itemized below.', 'alrenas' ); ?></p>

<?php if ( ! empty( $quote_url ) ) : ?>
	<p>
		<a class="email-button" href="<?php echo esc_url( $quote_url ); ?>"><?php esc_html_e( 'View & Accept Quote', 'alrenas' ); ?></a>
	</p>
<?php endif; ?>

<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
?>

<?php if ( ! empty( $quote_url ) ) : ?>
	<p style="margin-top:8px;">
		<a class="email-button" href="<?php echo esc_url( $quote_url ); ?>"><?php esc_html_e( 'View & Accept Quote', 'alrenas' ); ?></a>
	</p>
<?php endif; ?>

<?php if ( $additional_content ) : ?>
	<p><?php echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) ); ?></p>
<?php endif; ?>

<?php
/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_footer', $email );
