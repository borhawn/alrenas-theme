<?php
/**
 * Class Alrenas_Email_Customer_Quote_Ready file.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Alrenas_Email_Customer_Quote_Ready', false ) ) {
	return new Alrenas_Email_Customer_Quote_Ready();
}

/**
 * The priced, itemized quote email -- sent once the admin clicks
 * "Send Quote to Customer" on the order-edit screen.
 */
class Alrenas_Email_Customer_Quote_Ready extends WC_Email {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id             = 'alrenas_customer_quote_ready';
		$this->customer_email = true;
		$this->title          = esc_html__( 'Your Quote Is Ready', 'alrenas' );
		$this->description    = esc_html__( 'Sent to the customer once the admin has priced their request and clicked "Send Quote to Customer".', 'alrenas' );
		$this->template_html  = 'emails/customer-quote-ready.php';
		$this->template_plain = 'emails/plain/customer-quote-ready.php';
		$this->placeholders   = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);

		add_action( 'woocommerce_order_status_quote-sent_notification', array( $this, 'trigger' ), 10, 2 );

		parent::__construct();
	}

	/**
	 * Default subject line.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return esc_html__( 'Your quote from {site_title} is ready', 'alrenas' );
	}

	/**
	 * Default heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return esc_html__( 'Your quote is ready', 'alrenas' );
	}

	/**
	 * Trigger the sending of this email. Also callable directly (e.g. an
	 * admin "Resend Quote Email" button) without a status transition, by
	 * passing the order straight in.
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
				'quote_url'          => $this->object instanceof WC_Order ? alrenas_get_quote_view_url( $this->object ) : '',
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
				'quote_url'          => $this->object instanceof WC_Order ? alrenas_get_quote_view_url( $this->object ) : '',
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
		return esc_html__( 'Questions about anything in this quote? Just reply to this email and we\'ll help out.', 'alrenas' );
	}
}

return new Alrenas_Email_Customer_Quote_Ready();
