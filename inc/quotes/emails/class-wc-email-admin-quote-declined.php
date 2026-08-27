<?php
/**
 * Class Alrenas_Email_Admin_Quote_Declined file.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Alrenas_Email_Admin_Quote_Declined', false ) ) {
	return new Alrenas_Email_Admin_Quote_Declined();
}

/**
 * Notifies the shop admin when a customer declines a sent quote.
 */
class Alrenas_Email_Admin_Quote_Declined extends WC_Email {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id             = 'alrenas_admin_quote_declined';
		$this->title          = esc_html__( 'Quote Declined (Admin)', 'alrenas' );
		$this->description    = esc_html__( 'Sent to the shop admin when a customer declines a quote.', 'alrenas' );
		$this->template_html  = 'emails/admin-quote-declined.php';
		$this->template_plain = 'emails/plain/admin-quote-declined.php';
		$this->placeholders   = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);

		add_action( 'woocommerce_order_status_quote-declined_notification', array( $this, 'trigger' ), 10, 2 );

		parent::__construct();

		$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
	}

	/**
	 * Default subject line.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return esc_html__( '[{site_title}] Quote #{order_number} declined', 'alrenas' );
	}

	/**
	 * Default heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return esc_html__( 'A customer declined their quote', 'alrenas' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param int            $order_id Order ID.
	 * @param WC_Order|false $order    Order object, if already loaded.
	 */
	public function trigger( $order_id, $order = false ) {
		$this->setup_locale();

		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}

		if ( $order instanceof WC_Order ) {
			$this->object                         = $order;
			$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
			$this->placeholders['{order_number}'] = $this->object->get_order_number();
		}

		if ( $this->is_enabled() && $this->get_recipient() ) {
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		$this->restore_locale();
	}

	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => false,
				'email'              => $this,
			)
		);
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html(
			$this->template_plain,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => true,
				'email'              => $this,
			)
		);
	}

	/**
	 * Initialise settings form fields, adding the admin recipient field
	 * that customer-facing emails don't need.
	 */
	public function init_form_fields() {
		/* translators: %s: list of placeholders. */
		$placeholder_text = sprintf( esc_html__( 'Available placeholders: %s', 'alrenas' ), '<code>' . implode( '</code>, <code>', array_keys( $this->placeholders ) ) . '</code>' );

		$this->form_fields = array(
			'enabled'   => array(
				'title'   => esc_html__( 'Enable/Disable', 'alrenas' ),
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Enable this email notification', 'alrenas' ),
				'default' => 'yes',
			),
			'recipient' => array(
				'title'       => esc_html__( 'Recipient(s)', 'alrenas' ),
				'type'        => 'text',
				/* translators: %s: site admin email. */
				'description' => sprintf( esc_html__( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'alrenas' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
				'placeholder' => '',
				'default'     => '',
				'desc_tip'    => true,
			),
			'subject'   => array(
				'title'       => esc_html__( 'Subject', 'alrenas' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_subject(),
				'default'     => '',
			),
			'heading'   => array(
				'title'       => esc_html__( 'Email heading', 'alrenas' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_heading(),
				'default'     => '',
			),
			'email_type' => array(
				'title'       => esc_html__( 'Email type', 'alrenas' ),
				'type'        => 'select',
				'description' => esc_html__( 'Choose which format of email to send.', 'alrenas' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options(),
				'desc_tip'    => true,
			),
		);
	}
}

return new Alrenas_Email_Admin_Quote_Declined();
