<?php

namespace Sales\Tracker;

/**
 * The Admin class
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		$sales = new Admin\Sales();
		new Admin\Menu( $sales );
		$this->dispatch_actions( $sales );
	}

	/**
	 * Dispatch form actions
	 *
	 * @param object $sales The sales object
	 */
	public function dispatch_actions( $sales ) {
		add_action( 'admin_post_st_delete_sale', array( $sales, 'delete_sale' ) );
	}
}
