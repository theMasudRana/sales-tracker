<?php

namespace Sales\Tracker\Frontend;

/**
 * Sales Form shortcode
 */
class Sales_Form {

    /**
     * Initialize the class
     */
    function __construct() {
        add_shortcode( 'sales_tracker_form', [$this, 'render_shortcode' ] );
    }

    /**
     * Render the [sales_tracker_form] shortcode
     * 
     * @param array $atts
     * @param string $content
     * 
     * @return string
     */
    public function render_shortcode( $atts, $content ) {
        wp_enqueue_style( 'sales-tracker-frontend-style' );
        wp_enqueue_style( 'sales-tracker-style-common' );
        wp_enqueue_script( 'sales-tracker-frontend-script' );

        ob_start();
        include __DIR__ . '/views/sales-form.php';

        return ob_get_clean();
    }
}