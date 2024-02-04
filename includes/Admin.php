<?php

namespace Sales\Tracker;

/**
 * Admin handler class
 */
class Admin {

	function __construct() {
		$sales = new Admin\Sales();
		new Admin\Menu( $sales );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		$this->dispatch_actions( $sales );
	}

	/**
	 * Enqueue admin styles and scripts
	 *
	 * @return void
	 */
	public function admin_enqueue() {
		wp_enqueue_style( 'sales-tracker-admin', SALES_TRACKER_ASSETS . '/css/admin/admin.css', array(), SALES_TRACKER_VERSION );
	}

	/**
	 * Dispatch form actions
	 */
	public function dispatch_actions( $sales ) {
		add_action( 'admin_init', array( $sales, 'form_handler' ) );
		add_action( 'admin_post_st_delete_sale', array( $sales, 'delete_sale' ) );
	}
}
