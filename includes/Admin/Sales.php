<?php

namespace Sales\Tracker\Admin;
use Sales\Tracker\Traits\Form_Errors;

/**
 * Sale handler class
 */
class Sales {

	use Form_Errors;

	/**
	 * Sales tracker page routing
	 *
	 * @return string
	 */
	public function sales_tracker_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		$templates = array(
			'new'  => __DIR__ . '/views/sale-new.php',
			'edit' => __DIR__ . '/views/sale-edit.php',
			'view' => __DIR__ . '/views/sale-view.php',
			'list' => __DIR__ . '/views/sale-list.php',
		);

		$template = isset( $templates[ $action ] ) ? $templates[ $action ] : $templates['list'];

		$sale_item = null;
		if ( $action === 'edit' || $action === 'view' ) {
			$sale_item = st_get_sale( $id );
		}

		if ( file_exists( $template ) ) {
			include $template;
		}
	}

	/**
	 * New sales item form handler
	 *
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function form_handler() {

		if ( ! isset( $_POST['add_sale_item'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-tracker-item' ) ) {
			wp_die( esc_html__( 'You are not authorized to submit this form.', 'sales-tracker' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not authorized to submit this form.', 'sales-tracker' ) );
		}

		$id          = ! empty( $_POST['id'] ) ? intval( $_POST['id'] )                             : 0;
		$amount      = ! empty( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] )        : '';
		$buyer       = ! empty( $_POST['buyer'] ) ? sanitize_text_field( $_POST['buyer'] )          : '';
		$receipt_id  = ! empty( $_POST['receipt_id'] ) ? sanitize_text_field( $_POST['receipt_id'] ): '';
		$items       = ! empty( $_POST['items'] ) ? sanitize_textarea_field( $_POST['items'] )      : '';
		$buyer_email = ! empty( $_POST['buyer_email'] ) ? sanitize_email( $_POST['buyer_email'] )   : '';
		$note        = ! empty( $_POST['note'] ) ? sanitize_textarea_field( $_POST['note'] )        : '';
		$city        = ! empty( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] )            : '';
		$phone       = ! empty( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] )          : '';
		$entry_by    = ! empty( $_POST['entry_by'] ) ? sanitize_text_field( $_POST['entry_by'] )    : get_current_user_id();
		$salt        = bin2hex( random_bytes(8) );

		if ( empty( $amount ) ) {
			$this->errors['amount'] = esc_html__( 'Please enter amount.', 'sales-tracker' );
		}

		if ( empty( $buyer ) ) {
			$this->errors['buyer'] = esc_html__( 'Please enter buyer name.', 'sales-tracker' );
		}

		if ( empty( $buyer_email ) ) {
			$this->errors['buyer_email'] = esc_html__( 'Please enter buyer email.', 'sales-tracker' );
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}

		$args = array(
			'amount'      => $amount,
			'buyer'       => $buyer,
			'receipt_id'  => $receipt_id,
			'items'       => $items,
			'buyer_email' => $buyer_email,
			'buyer_ip'    => $_SERVER['REMOTE_ADDR'],
			'note'        => $note,
			'city'        => $city,
			'phone'       => $phone,
			'hash_key'    => hash( 'sha512', $receipt_id . $salt ),
			'entry_at'    => current_time( 'mysql' ),
			'entry_by'    => $entry_by,
		);

		if ( $id ) {
			$args['id'] = $id;
		}

		$insert_id = st_insert_track( $args );

		if ( is_wp_error( $insert_id ) ) {
			wp_die( $insert_id->get_error_message() );
		}

		if ( $id ) {
			$redirect_to = admin_url( 'admin.php?page=sales-tracker&action=edit&sale-updated=true&id=' . $id );
		} else {
			$redirect_to = admin_url( 'admin.php?page=sales-tracker&inserted=true' );
		}

		wp_redirect( $redirect_to );

		exit;
	}

	/**
	 * Delete single sale
	 */
	public function delete_sale() {
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'st_delete_sale' ) || ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not authorized delete this item.', 'sales-tracker' ) );
		}

		$id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

		if ( st_delete_sale( $id ) ) {
			$redirect_to = admin_url( 'admin.php?page=sales-tracker&sale-deleted=true' );
		} else {
			$redirect_to = admin_url( 'admin.php?page=sales-tracker&sale-deleted=false' );
		}

		wp_redirect( $redirect_to );

		exit;
	}
}
