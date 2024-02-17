<?php

namespace Sales\Tracker;

/**
 * The Assets class
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Load the scripts and styles in respective hook.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
	}

	/**
	 * Get all the frontend scripts as an array
	 *
	 * @return array
	 */
	public function get_frontend_scripts() {
		return array(
			'sales-tracker-frontend-script' => array(
				'src'     => SALES_TRACKER_ASSETS . '/js/application/index.js',
				'version' => SALES_TRACKER_VERSION,
				'deps'    => array( 'wp-element' ),
			),
		);
	}

	/**
	 * Get all the frontend styles as an array
	 *
	 * @return array
	 */
	public function get_frontend_styles() {
		return array(
			'sales-tracker-frontend-style' => array(
				'src'     => SALES_TRACKER_ASSETS . '/js/application/index.css',
				'version' => SALES_TRACKER_VERSION,
				'deps'    => false,
			),
		);
	}

	/**
	 * Get all the admin styles as an array
	 *
	 * @return array
	 */
	public function get_admin_styles() {
		return array(
			'sales-tracker-admin-style' => array(
				'src'     => SALES_TRACKER_ASSETS . '/css/admin.css',
				'version' => SALES_TRACKER_VERSION,
			),
		);
	}

	/**
	 * Get all the frontend scripts as an array
	 *
	 * @return array
	 */
	public function get_admin_scripts() {
		return array(
			'sales-tracker-admin-script' => array(
				'src'     => SALES_TRACKER_ASSETS . '/js/admin.js',
				'version' => SALES_TRACKER_VERSION,
				'deps'    => array( 'jquery' ),
			),
		);
	}

	/**
	 * Register admin assets
	 *
	 * @return void
	 */
	public function register_frontend_assets() {
		$frontend_styles  = $this->get_frontend_styles();
		$frontend_scripts = $this->get_frontend_scripts();

		foreach ( $frontend_styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $style['version'], $deps );
		}

		foreach ( $frontend_scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;

			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		wp_localize_script(
			'sales-tracker-frontend-script',
			'salesTracker',
			array(
				'site_url'         => site_url(),
				'success_message'  => esc_html__( 'Form submission successful.', 'sales-tracker' ),
				'submission_error' => esc_html__( 'Something went wrong!', 'sales-tracker' ),
				'nonce'            => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	/**
	 * Register admin assets
	 *
	 * @return void
	 */
	public function register_admin_assets() {
		$admin_styles  = $this->get_admin_styles();
		$admin_scripts = $this->get_admin_scripts();

		foreach ( $admin_styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $style['version'], $deps );
		}

		foreach ( $admin_scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;

			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}
	}
}
