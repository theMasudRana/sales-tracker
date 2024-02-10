<?php

namespace Sales\Tracker;

/**
 * Frontend handler class
 */
class Frontend {

	/**
	 * Initialize the frontend class
	 */
	function __construct() {
		new Frontend\Sales_Form();
		new Frontend\Sales_Dashboard();
	}
}
