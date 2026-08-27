<?php
/**
 * Guest-accessible quote view/accept/decline page. Rendered directly by
 * inc/quotes/actions.php's template_redirect handler (not through
 * WooCommerce's myaccount templates -- see that file for why).
 *
 * @package Alrenas
 */

$order = isset( $args['order'] ) && $args['order'] instanceof WC_Order ? $args['order'] : null;

if ( ! $order ) {
	return;
}

$statuses      = alrenas_get_quote_statuses();
$status        = $order->get_status();
$status_label  = isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
$is_actionable = 'quote-sent' === $status;
$decision_url  = admin_url( 'admin-post.php' );
?>
<section class="section quote-view">
	<div class="container quote-view-inner">
		<div class="quote-view-head reveal">
			<span class="eyebrow"><?php esc_html_e( 'Your Quote', 'alrenas' ); ?></span>
			<?php /* translators: %s: order number. */ ?>
			<h1><?php printf( esc_html__( 'Quote #%s', 'alrenas' ), esc_html( $order->get_order_number() ) ); ?></h1>
			<span class="quote-status-badge quote-status-<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $status_label ); ?></span>
		</div>

		<?php if ( 'quote-accepted' === $status ) : ?>
			<div class="quote-decision-banner is-accepted reveal"><?php esc_html_e( 'You accepted this quote. Our team will follow up shortly with next steps.', 'alrenas' ); ?></div>
		<?php elseif ( 'quote-declined' === $status ) : ?>
			<div class="quote-decision-banner is-declined reveal"><?php esc_html_e( 'You declined this quote. If anything changes, feel free to reach out to us.', 'alrenas' ); ?></div>
		<?php endif; ?>

		<div class="quote-view-card reveal">
			<div class="quote-items-table-wrap">
				<table class="quote-items-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Item', 'alrenas' ); ?></th>
							<th><?php esc_html_e( 'Qty', 'alrenas' ); ?></th>
							<th><?php esc_html_e( 'Price', 'alrenas' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $order->get_items() as $item ) : ?>
							<?php $product = $item->get_product(); ?>
							<tr>
								<td class="quote-item-cell">
									<?php if ( $product instanceof WC_Product ) : ?>
										<span class="quote-item-thumb"><?php echo wp_kses_post( $product->get_image( 'thumbnail' ) ); ?></span>
									<?php endif; ?>
									<span class="quote-item-name"><?php echo esc_html( $item->get_name() ); ?></span>
								</td>
								<td><?php echo esc_html( $item->get_quantity() ); ?></td>
								<td><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="quote-totals">
				<?php foreach ( $order->get_order_item_totals() as $total ) : ?>
					<div class="quote-totals-row<?php echo isset( $total['type'] ) && 'order_total' === $total['type'] ? ' is-grand-total' : ''; ?>">
						<span><?php echo wp_kses_post( $total['label'] ); ?></span>
						<span><?php echo wp_kses_post( $total['value'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $order->get_customer_note() ) : ?>
				<div class="quote-customer-note">
					<strong><?php esc_html_e( 'Your note', 'alrenas' ); ?></strong>
					<p><?php echo wp_kses_post( nl2br( esc_html( $order->get_customer_note() ) ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $is_actionable ) : ?>
			<div class="quote-decision-actions reveal">
				<form method="post" action="<?php echo esc_url( $decision_url ); ?>">
					<?php wp_nonce_field( 'alrenas_quote_decision_' . $order->get_id(), 'alrenas_quote_decision_nonce' ); ?>
					<input type="hidden" name="action" value="alrenas_customer_accept_quote">
					<input type="hidden" name="order_id" value="<?php echo esc_attr( $order->get_id() ); ?>">
					<input type="hidden" name="key" value="<?php echo esc_attr( $order->get_order_key() ); ?>">
					<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Accept This Quote', 'alrenas' ); ?></button>
				</form>
				<form method="post" action="<?php echo esc_url( $decision_url ); ?>">
					<?php wp_nonce_field( 'alrenas_quote_decision_' . $order->get_id(), 'alrenas_quote_decision_nonce' ); ?>
					<input type="hidden" name="action" value="alrenas_customer_decline_quote">
					<input type="hidden" name="order_id" value="<?php echo esc_attr( $order->get_id() ); ?>">
					<input type="hidden" name="key" value="<?php echo esc_attr( $order->get_order_key() ); ?>">
					<button type="submit" class="btn btn-secondary"><?php esc_html_e( 'Decline', 'alrenas' ); ?></button>
				</form>
			</div>
		<?php endif; ?>
	</div>
</section>
