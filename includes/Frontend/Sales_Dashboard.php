<?php

namespace Sales\Tracker\Frontend;

/**
 * Sales Form shortcode
 *
 * @since 1.0.0
 */
class Sales_Dashboard {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_shortcode( 'sales_tracker_dashboard', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Render the [sales_tracker_dashboard] shortcode
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
			<div class="st-sales-dashboard">
				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
					<div id="sales-tracker-dashboard"></div>
				<?php else : ?>
					<p><?php esc_html_e( 'You need to be at list an editor to view Sales Tracker Dashboard.' ); ?></p>
				<?php endif; ?>
			</div>
		<?php
		return ob_get_clean();
	}
}
