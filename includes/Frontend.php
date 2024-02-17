<?php

namespace Sales\Tracker;

/**
 * Frontend handler class
 *
 * @since 1.0.0
 */
class Frontend {

	/**
	 * Initialize the frontend class
	 */
	public function __construct() {
		new Frontend\Sales_Form();
		new Frontend\Sales_Dashboard();
	}
}
