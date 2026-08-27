<?php
/**
 * Admin new-quote-request notification email.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items         = $order->get_items();
$first_item    = $items ? reset( $items ) : null;
$customer_note = $order->get_customer_note();

/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<p>
<?php
/* translators: %s: Customer billing full name. */
printf( esc_html__( '%s just submitted a quote request.', 'alrenas' ), esc_html( $order->get_formatted_billing_full_name() ) );
?>
</p>

<div class="email-note-box">
	<?php if ( $first_item ) : ?>
		<p style="margin:0 0 8px;"><strong><?php esc_html_e( 'Product:', 'alrenas' ); ?></strong> <?php echo esc_html( $first_item->get_name() ); ?></p>
		<p style="margin:0 0 8px;"><strong><?php esc_html_e( 'Quantity:', 'alrenas' ); ?></strong> <?php echo esc_html( $first_item->get_quantity() ); ?></p>
	<?php endif; ?>
	<p style="margin:0 0 8px;"><strong><?php esc_html_e( 'Email:', 'alrenas' ); ?></strong> <?php echo esc_html( $order->get_billing_email() ); ?></p>
	<?php if ( $order->get_billing_phone() ) : ?>
		<p style="margin:0 0 8px;"><strong><?php esc_html_e( 'Phone:', 'alrenas' ); ?></strong> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
	<?php endif; ?>
	<?php if ( $order->get_billing_company() ) : ?>
		<p style="margin:0;"><strong><?php esc_html_e( 'Company/Clinic:', 'alrenas' ); ?></strong> <?php echo esc_html( $order->get_billing_company() ); ?></p>
	<?php endif; ?>
</div>

<?php if ( $customer_note ) : ?>
	<p><strong><?php esc_html_e( 'Notes from the customer:', 'alrenas' ); ?></strong></p>
	<p><?php echo wp_kses_post( nl2br( esc_html( $customer_note ) ) ); ?></p>
<?php endif; ?>

<p>
	<a class="email-button" href="<?php echo esc_url( $order->get_edit_order_url() ); ?>"><?php esc_html_e( 'View & Price This Quote', 'alrenas' ); ?></a>
</p>

<?php if ( $additional_content ) : ?>
	<p><?php echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) ); ?></p>
<?php endif; ?>

<?php
/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_footer', $email );
