<?php

namespace Sales\Tracker;

/**
 * Admin handler class
 */
class Admin {
    
    function __construct() {
        $tracker = new Admin\Tracker();
        new Admin\Menu( $tracker );
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue' ] );
        $this->dispatch_actions( $tracker );
    }

    /**
     * Enqueue admin styles and scripts
     * 
     * @return void
     */
    public function admin_enqueue() {
        wp_enqueue_style( 'sales-tracker-admin', SALES_TRACKER_ASSETS . '/css/admin/admin.css', [], SALES_TRACKER_VERSION );
    }

    /**
     * Dispatch form actions
     */
    public function dispatch_actions( $tracker ) {
        add_action( 'admin_init', [$tracker, 'form_handler'] );
    }
}