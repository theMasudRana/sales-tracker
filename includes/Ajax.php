<?php

namespace Sales\Tracker;

/**
 * Handal ajax operations
 */
class Ajax {
	function __construct() {
		add_action( 'wp_ajax_st-sale-submission-action', array( $this, 'submit_sales_form' ) );
		add_action( 'wp_ajax_nopriv_st-sale-submission-action', array( $this, 'submit_sales_form' ) );
	}

	/**
	 * Handel sales form submission
	 */
	public function submit_sales_form() {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'st-sale-submission-nonce' ) ) {
			wp_send_json_error(
				array(
					'nonce_error'         => true,
					'nonce_error_message' => esc_html__( 'Nonce verification failed', 'sales-tracker' ),
				)
			);
		}

		$this->handel_frontend_form_submission();

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Sales data has been sent.', 'sales-tracker' ),
			)
		);

		wp_send_json_error(
			array(
				'message' => esc_html__( 'Something went wrong.', 'sales-tracker' ),
			)
		);
	}

	public function handel_frontend_form_submission() {

		if ( ! isset( $_POST['action'] ) && $_POST['action'] !== 'st-sale-submission-action' ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'st-sale-submission-nonce' ) ) {
			wp_die( esc_html__( 'You are not authorized to submit this form.', 'sales-tracker' ) );
		}

		$id          = ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$amount      = ! empty( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] ) : '';
		$buyer       = ! empty( $_POST['buyer'] ) ? sanitize_text_field( $_POST['buyer'] ) : '';
		$items       = ! empty( $_POST['items'] ) ? sanitize_textarea_field( $_POST['items'] ) : '';
		$buyer_email = ! empty( $_POST['buyer_email'] ) ? sanitize_email( $_POST['buyer_email'] ) : '';
		$city        = ! empty( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '';
		$phone       = ! empty( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
		$entry_by    = ! empty( $_POST['entry_by'] ) ? sanitize_text_field( $_POST['entry_by'] ) : get_current_user_id();
		$note        = ! empty( $_POST['note'] ) ? sanitize_textarea_field( $_POST['note'] ) : '';
		$receipt_id  = ! empty( $_POST['receipt_id'] ) ? sanitize_text_field( $_POST['receipt_id'] ) : '';
		$salt        = bin2hex( random_bytes( 8 ) );

		$args = array(
			'amount'      => $amount,
			'buyer'       => $buyer,
			'receipt_id'  => $receipt_id,
			'items'       => $items,
			'buyer_email' => $buyer_email,
			'city'        => $city,
			'phone'       => $phone,
			'entry_by'    => $entry_by,
			'note'        => $note,
			'buyer_ip'    => $_SERVER['REMOTE_ADDR'],
			'hash_key'    => hash( 'sha512', $receipt_id . $salt ),
			'entry_at'    => current_time( 'mysql' ),
		);

		if ( $id ) {
			$args['id'] = $id;
		}

		st_insert_sale( $args );
	}
}
