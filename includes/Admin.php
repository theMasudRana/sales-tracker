<?php

namespace Sales\Tracker;

/**
 * Admin handler class
 */
class Admin {

	function __construct() {
		$sales = new Admin\Sales();
		new Admin\Menu( $sales );
		$this->dispatch_actions( $sales );
	}

	/**
	 * Dispatch form actions
	 */
	public function dispatch_actions( $sales ) {
		add_action( 'admin_init', array( $sales, 'form_handler' ) );
		add_action( 'admin_post_st_delete_sale', array( $sales, 'delete_sale' ) );
	}
}
