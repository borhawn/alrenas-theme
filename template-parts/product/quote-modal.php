<?php
/**
 * "Get a Quote" intake modal -- hidden until a [data-quote-trigger]
 * element (the hero's primary button) is clicked. Submits via AJAX to
 * inc/quotes/intake.php, which turns it into a WooCommerce order sitting
 * in the quote-requested status.
 *
 * @package Alrenas
 */
?>
<div class="quote-modal" data-quote-modal role="dialog" aria-modal="true" aria-labelledby="quote-modal-title">
	<div class="quote-modal-backdrop" data-quote-modal-close></div>
	<div class="quote-modal-card">
		<button type="button" class="quote-modal-close" data-quote-modal-close aria-label="<?php esc_attr_e( 'Close', 'alrenas' ); ?>">&times;</button>

		<div class="quote-modal-panel" data-quote-modal-panel="form">
			<span class="eyebrow"><?php esc_html_e( 'Request pricing', 'alrenas' ); ?></span>
			<h2 id="quote-modal-title"><?php esc_html_e( 'Get a Quote', 'alrenas' ); ?></h2>

			<form data-quote-form novalidate>
				<?php // The AJAX nonce is supplied via wp_localize_script() in inc/quotes/frontend.php, not a form field. ?>
				<input type="hidden" name="product_id" data-quote-product-id value="">

				<div class="quote-product-card" data-quote-modal-product hidden>
					<img class="quote-product-thumb" data-quote-modal-image src="" alt="">
					<div class="quote-product-info">
						<span class="quote-product-name" data-quote-modal-product-name></span>
						<label class="quote-product-qty">
							<span><?php esc_html_e( 'Quantity', 'alrenas' ); ?></span>
							<input type="number" name="quantity" min="1" max="999" step="1" value="1" inputmode="numeric">
						</label>
					</div>
				</div>

				<div class="quote-field-row">
					<label class="quote-field">
						<span><?php esc_html_e( 'Name', 'alrenas' ); ?> <em>*</em></span>
						<input type="text" name="name" required autocomplete="name">
					</label>
					<label class="quote-field">
						<span><?php esc_html_e( 'Email', 'alrenas' ); ?> <em>*</em></span>
						<input type="email" name="email" required autocomplete="email">
					</label>
				</div>
				<div class="quote-field-row">
					<label class="quote-field">
						<span><?php esc_html_e( 'Phone', 'alrenas' ); ?> <small><?php esc_html_e( '(optional)', 'alrenas' ); ?></small></span>
						<input type="tel" name="phone" autocomplete="tel">
					</label>
					<label class="quote-field">
						<span><?php esc_html_e( 'Company or Clinic Name', 'alrenas' ); ?> <small><?php esc_html_e( '(optional)', 'alrenas' ); ?></small></span>
						<input type="text" name="company" autocomplete="organization">
					</label>
				</div>
				<label class="quote-field">
					<span><?php esc_html_e( 'What are you looking for?', 'alrenas' ); ?> <small><?php esc_html_e( '(optional)', 'alrenas' ); ?></small></span>
					<textarea name="notes" rows="3"></textarea>
				</label>

				<p class="quote-field-honeypot" aria-hidden="true">
					<label><?php esc_html_e( 'Website', 'alrenas' ); ?>
						<input type="text" name="alrenas_quote_website" tabindex="-1" autocomplete="off">
					</label>
				</p>

				<p class="quote-form-error" data-quote-form-error hidden></p>

				<button type="submit" class="btn btn-primary quote-form-submit" data-quote-submit>
					<span data-quote-submit-label><?php esc_html_e( 'Send Request', 'alrenas' ); ?></span>
				</button>
			</form>
		</div>

		<div class="quote-modal-panel" data-quote-modal-panel="success" hidden>
			<div class="quote-success-status">
				<span class="status-dot" aria-hidden="true"></span>
				<span><?php esc_html_e( 'Request received', 'alrenas' ); ?></span>
			</div>
			<h2><?php esc_html_e( 'We\'ve got your request.', 'alrenas' ); ?></h2>

			<div class="quote-product-card" data-quote-modal-success-product hidden>
				<img class="quote-product-thumb" data-quote-modal-success-image src="" alt="">
				<div class="quote-product-info">
					<span class="quote-product-name" data-quote-modal-success-name></span>
				</div>
			</div>

			<p data-quote-success-message><?php esc_html_e( 'Thanks -- your request has been received. We will follow up shortly with pricing.', 'alrenas' ); ?></p>
			<button type="button" class="btn btn-secondary" data-quote-modal-close><?php esc_html_e( 'Close', 'alrenas' ); ?></button>
		</div>
	</div>
</div>
