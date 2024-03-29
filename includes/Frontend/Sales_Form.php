<?php

namespace Sales\Tracker\Frontend;

/**
 * Sales Form shortcode
 *
 * @since 1.0.0
 */
class Sales_Form {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_shortcode( 'sales_tracker_form', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Render the [sales_tracker_form] shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function render_shortcode( $atts, $content ) {
		wp_enqueue_style( 'sales-tracker-frontend-style' );
		wp_enqueue_script( 'sales-tracker-frontend-script' );

		ob_start();
		?>
			<div class="st-sales-form" id="sales-tracker-form"></div>
		<?php
		return ob_get_clean();
	}
}
