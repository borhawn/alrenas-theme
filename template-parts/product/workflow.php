<?php
/**
 * Single-product clinical workflow: an admin-managed, add/remove-able set
 * of tabs (Site Content > per-product editor). Tab switching reuses the
 * theme's existing generic [data-tabs] handler (same one driving the
 * homepage specialty tabs), so no extra JS is needed here.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$eyebrow = alrenas_get_product_meta( $product->get_id(), 'workflow_eyebrow' );
$heading = alrenas_get_product_meta( $product->get_id(), 'workflow_heading' );
$lead    = alrenas_get_product_meta( $product->get_id(), 'workflow_lead' );
$tabs    = alrenas_get_product_meta( $product->get_id(), 'workflow_tabs', array() );

if ( ! $tabs ) {
	return;
}
?>
<section class="section clinical-workflow" id="clinical-workflow">
	<div class="container">
		<div class="workflow-heading reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
			<?php if ( $lead ) : ?><p><?php echo esc_html( $lead ); ?></p><?php endif; ?>
		</div>

		<div class="workflow-tabs" data-tabs>
			<div class="workflow-tab-list reveal" role="tablist" aria-label="<?php echo esc_attr( $product->get_name() ); ?> workflow">
				<?php foreach ( array_values( $tabs ) as $i => $tab ) : ?>
					<button class="workflow-tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-tab="tab-<?php echo esc_attr( $i ); ?>"><span><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span> <?php echo esc_html( $tab['label'] ); ?></button>
				<?php endforeach; ?>
			</div>

			<div class="workflow-panels reveal">
				<?php foreach ( array_values( $tabs ) as $i => $tab ) : ?>
					<?php
					$image_id   = ! empty( $tab['image_id'] ) ? (int) $tab['image_id'] : 0;
					$highlights = ! empty( $tab['highlights'] ) ? array_filter( array_map( 'trim', explode( ',', $tab['highlights'] ) ) ) : array();
					?>
					<article class="workflow-panel" data-panel="tab-<?php echo esc_attr( $i ); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
						<div class="workflow-copy" <?php echo $image_id ? '' : 'style="grid-column:1/-1"'; ?>>
							<?php if ( ! empty( $tab['kicker'] ) ) : ?><span class="product-kicker"><?php echo esc_html( $tab['kicker'] ); ?></span><?php endif; ?>
							<?php if ( ! empty( $tab['heading'] ) ) : ?><h3><?php echo esc_html( $tab['heading'] ); ?></h3><?php endif; ?>
							<?php if ( ! empty( $tab['description'] ) ) : ?><p><?php echo esc_html( $tab['description'] ); ?></p><?php endif; ?>
							<?php if ( $highlights ) : ?>
								<div class="soft-points">
									<?php foreach ( $highlights as $highlight ) : ?><span><?php echo esc_html( $highlight ); ?></span><?php endforeach; ?>
								</div>
							<?php elseif ( ! empty( $tab['callout_title'] ) ) : ?>
								<div class="progress-callout">
									<strong><?php echo esc_html( $tab['callout_title'] ); ?></strong>
									<p><?php echo esc_html( $tab['callout_text'] ?? '' ); ?></p>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( $image_id ) : ?>
							<div class="workflow-image"><?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?></div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
