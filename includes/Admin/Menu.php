<?php

namespace Sales\Tracker\Admin;

/**
 * Menu handler class
 */

class Menu {

    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    public function admin_menu() {
        add_menu_page( esc_html__( 'Sales Tracker', 'sales-tracker' ), esc_html__( 'Sales Tracker', 'sales-tracker' ), 'manage_options', 'sales-tracker', [ $this, 'plugin_page' ], 'dashicons-analytics' );
    }

    public function plugin_page() {
        echo 'Hello World';
    }
}