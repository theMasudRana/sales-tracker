<?php

namespace Sales\Tracker;

/**
 * Admin handler class
 */
class Admin {
    
    function __construct() {
        $sales = new Admin\Sales();
        new Admin\Menu( $sales );
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue' ] );
        $this->dispatch_actions( $sales );
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
    public function dispatch_actions( $sales ) {
        add_action( 'admin_init', [$sales, 'form_handler'] );
    }
}