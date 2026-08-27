<?php
/**
 * Class Alrenas_Email_Customer_Quote_Requested file.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Alrenas_Email_Customer_Quote_Requested', false ) ) {
	return new Alrenas_Email_Customer_Quote_Requested();
}

/**
 * Sent to the customer the moment their quote request is received, before
 * any pricing exists -- sets expectations while the admin builds the quote.
 */
class Alrenas_Email_Customer_Quote_Requested extends WC_Email {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id             = 'alrenas_customer_quote_requested';
		$this->customer_email = true;
		$this->title          = esc_html__( 'Quote Request Received', 'alrenas' );
		$this->description    = esc_html__( 'Sent to the customer immediately after they submit a "Get a Quote" request, before any pricing exists.', 'alrenas' );
		$this->template_html  = 'emails/customer-quote-requested.php';
		$this->template_plain = 'emails/plain/customer-quote-requested.php';
		$this->placeholders   = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);

		add_action( 'woocommerce_order_status_quote-requested_notification', array( $this, 'trigger' ), 10, 2 );

		parent::__construct();
	}

	/**
	 * Default subject line.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return esc_html__( 'We\'ve received your quote request', 'alrenas' );
	}

	/**
	 * Default heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return esc_html__( 'Your quote request is in hand', 'alrenas' );
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
			$this->object                          = $order;
			$this->recipient                       = $this->object->get_billing_email();
			$this->placeholders['{order_date}']    = wc_format_datetime( $this->object->get_date_created() );
			$this->placeholders['{order_number}']  = $this->object->get_order_number();
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
				'sent_to_admin'      => false,
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
				'sent_to_admin'      => false,
				'plain_text'         => true,
				'email'              => $this,
			)
		);
	}

	/**
	 * Default additional content shown below the main email body.
	 *
	 * @return string
	 */
	public function get_default_additional_content() {
		return esc_html__( 'If anything about your request changes in the meantime, just reply to this email.', 'alrenas' );
	}
}

return new Alrenas_Email_Customer_Quote_Requested();
