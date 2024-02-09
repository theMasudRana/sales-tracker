<?php

namespace Sales\Tracker;

use Sales\Tracker\API\Sales;

class API {
	function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api' ) );
	}

	public function register_api() {
		$sales = new Sales();
		$sales->register_routes();
	}
}
