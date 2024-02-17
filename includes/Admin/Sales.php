<?php

namespace Sales\Tracker\Admin;

/**
 * Sale handler class
 */
class Sales {

	/**
	 * Sales tracker page routing
	 *
	 * @return void
	 */
	public function sales_tracker_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		$templates = array(
			'list' => __DIR__ . '/views/sale-list.php',
		);

		$template = isset( $templates[ $action ] ) ? $templates[ $action ] : $templates['list'];

		if ( file_exists( $template ) ) {
			include $template;
		}
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
