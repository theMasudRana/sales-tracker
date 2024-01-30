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
        $parent_slug = 'sales-tracker';
        $capability  = 'manage_options';
        add_menu_page( esc_html__( 'Sales Tracker', 'sales-tracker' ), esc_html__( 'Sales Tracker', 'sales-tracker' ), $capability, $parent_slug, [ $this, 'sales_tracker_page' ], 'dashicons-analytics' );
        add_submenu_page( $parent_slug, esc_attr__( 'Tracker', 'sales-tracker' ), esc_html__( 'Tracker', 'sales-tracker' ), $capability, $parent_slug, [ $this, 'sales_tracker_page' ] );
        add_submenu_page( $parent_slug, esc_attr__( 'Settings', 'sales-tracker' ), esc_html__( 'Settings', 'sales-tracker' ), $capability, 'seals-tracker-settings', [ $this, 'sales_tracker_settings' ] );
    }

    public function sales_tracker_page() {
        $tracker = new Tracker();
        $tracker->tracker_page();
    }

    public function sales_tracker_settings() {
        echo "Sales tracker settings";
    }
}