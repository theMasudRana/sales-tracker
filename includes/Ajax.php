<?php

namespace Sales\Tracker;

/**
 * Handal ajax operations
 */
class Ajax {
    function __construct() {
        add_action( 'wp_ajax_st_fe_sales_form', [$this, 'submit_sales_form'] );
        add_action( 'wp_ajax_nopriv_st_fe_sales_form', [$this, 'submit_sales_form'] );
    }

    /**
     * Handel frontend sales form submission
     */
    public function submit_sales_form() {

        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'st-fe-sales-form-nonce' ) ) {
            wp_send_json_error([
                'message'   => esc_html__( 'Nonce verification failed', 'sales-tracker' )
            ]);
        }

        $this->handel_frontend_form_submission();

        wp_send_json_success([
            'message'   => esc_html__( 'Sales data has been sent.', 'sales-tracker')
        ]);
    }

    public function handel_frontend_form_submission() {

        error_log( print_r( $_POST, true ) );

        if ( ! isset( $_POST['action'] ) && $_POST['action'] !== 'st_fe_sales_form' ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'st-fe-sales-form-nonce' ) ) {
			wp_die( esc_html__( 'You are not authorized to submit this form.', 'sales-tracker' ) );
		}

		$amount      = ! empty( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] )        : '';
		$buyer       = ! empty( $_POST['buyer'] ) ? sanitize_text_field( $_POST['buyer'] )          : '';
		$receipt_id  = ! empty( $_POST['receipt_id'] ) ? sanitize_text_field( $_POST['receipt_id'] ): '';
		$items       = ! empty( $_POST['items'] ) ? sanitize_textarea_field( $_POST['items'] )      : '';
		$buyer_email = ! empty( $_POST['buyer_email'] ) ? sanitize_email( $_POST['buyer_email'] )   : '';
		$city        = ! empty( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] )            : '';
		$phone       = ! empty( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] )          : '';
		$entry_by    = ! empty( $_POST['entry_by'] ) ? sanitize_text_field( $_POST['entry_by'] )    : get_current_user_id();
		$note        = ! empty( $_POST['note'] ) ? sanitize_textarea_field( $_POST['note'] )        : '';
		$salt        = bin2hex( random_bytes(8) );
		
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

        $insert_id = st_insert_track( $args );

        
        error_log( print_r( $insert_id, true ) );

		if ( is_wp_error( $insert_id ) ) {
			wp_die( $insert_id->get_error_message() );
		}
    }
}