<?php

namespace Sales\Tracker\Admin;

/**
 * Menu handler class
 */

class Menu {

	public $tracker;

	/**
	 * Initialize the menu class
	 */
	function __construct( $tracker ) {
		$this->tracker = $tracker;
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Add Sales Tracker menu in the Dashboard
	 *
	 * @return void
	 */
	public function admin_menu() {
		$parent_slug = 'sales-tracker';
		$capability  = 'manage_options';

		$hook = add_menu_page( esc_html__( 'Sales Tracker', 'sales-tracker' ), esc_html__( 'Sales Tracker', 'sales-tracker' ), $capability, $parent_slug, array( $this->tracker, 'sales_tracker_page' ), 'dashicons-analytics' );
		add_submenu_page( $parent_slug, esc_attr__( 'All Sales', 'sales-tracker' ), esc_html__( 'All Sales', 'sales-tracker' ), $capability, $parent_slug, array( $this->tracker, 'sales_tracker_page' ) );
		add_submenu_page( $parent_slug, esc_attr__( 'Settings', 'sales-tracker' ), esc_html__( 'Settings', 'sales-tracker' ), $capability, 'sales-tracker-settings', array( $this, 'sales_tracker_settings' ) );
		add_action( 'admin_head-' . $hook, array( $this, 'admin_assets' ) );
	}

	/**
	 * Sales tracker settings callback
	 */
	public function sales_tracker_settings() {
		esc_html_e( 'Sales tracker settings' );
	}

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function admin_assets() {
		wp_enqueue_style( 'sales-tracker-admin-style' );
		wp_enqueue_script( 'sales-tracker-admin-script' );
	}
}
