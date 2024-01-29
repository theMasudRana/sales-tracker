<?php

namespace Sales\Tracker\Frontend;

/**
 * Shortcode handler class
 */
class Shortcode {

    /**
     * Initializes the class
     */
    function __construct() {
        add_shortcode( 'sales-tracker', [ $this, 'render_shortcode' ] );
    }

    /**
     * Shortcode handler callback
     * 
     * @param array $atts
     * @param string $content 
     * 
     * @return string
     */
    public function render_shortcode( $atts, $content = '' ) {
        return 'Hello From Shortcode';
    }
}