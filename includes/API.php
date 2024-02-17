<?php

namespace Sales\Tracker;

use Sales\Tracker\API\Sales;
/**
 * The API class
 *
 * @since 1.0.0
 */
class API {
	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api' ) );
	}

	/**
	 * Register the API
	 *
	 * @return void
	 */
	public function register_api() {
		$sales = new Sales();
		$sales->register_routes();
	}
}
