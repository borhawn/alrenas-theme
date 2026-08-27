<?php
/**
 * Admin quote-accepted notification email.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items      = $order->get_items();
$first_item = $items ? reset( $items ) : null;

/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<p>
<?php
/* translators: 1: Customer billing full name, 2: order number. */
printf(
	esc_html__( '%1$s just accepted quote #%2$s.', 'alrenas' ),
	esc_html( $order->get_formatted_billing_full_name() ),
	esc_html( $order->get_order_number() )
);
?>
</p>

<?php if ( $first_item ) : ?>
	<div class="email-note-box">
		<strong><?php esc_html_e( 'Product:', 'alrenas' ); ?></strong> <?php echo esc_html( $first_item->get_name() ); ?>
	</div>
<?php endif; ?>

<p>
	<a class="email-button" href="<?php echo esc_url( $order->get_edit_order_url() ); ?>"><?php esc_html_e( 'View Order', 'alrenas' ); ?></a>
</p>

<?php if ( $additional_content ) : ?>
	<p><?php echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) ); ?></p>
<?php endif; ?>

<?php
/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_footer', $email );
