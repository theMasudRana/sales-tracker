<?php
/**
 * Plugin Name: Sales Tracker
 * Plugin URI: https://github.com/theMasudRana/sales-tracker
 * Description: This plugin will track your sales
 * Version: 1.0.0
 * Author: Masud Rana
 * Author URI: https://masudrana.me/
 * License: GPLv2 or later
 * Text Domain: sales-tracker
 * Domain Path: /languages/
 */

// Prevent direct access
defined( 'ABSPATH' ) || die( 'Direct access disabled!' );

require_once __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( 'Sales_Tracker' ) ) {

	/**
	 * The final class that holds the plugin
	 *
	 * @since 1.0.0
	 *
	 * @package Sales_Tracker
	 */
	final class Sales_Tracker {

		/**
		 * Plugin version
		 */
		const version = '1.0.0';

		/**
		 * Class constructor
		 */
		private function __construct() {
			$this->define_constants();

			register_activation_hook( __FILE__, array( $this, 'activate' ) );

			add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		}

		/**
		 * Initialize singleton instance
		 *
		 * @since 1.0.0
		 *
		 * @return \Sales_Tracker
		 */
		public static function init() {
			static $instance = false;

			if ( ! $instance ) {
				$instance = new self();
			}

			return $instance;
		}

		/**
		 * Define sales tracker constants
		 *
		 * @return void
		 */
		public function define_constants() {
			define( 'SALES_TRACKER_VERSION', self::version );
			define( 'SALES_TRACKER_FILE', __FILE__ );
			define( 'SALES_TRACKER_PATH', __DIR__ );
			define( 'SALES_TRACKER_URL', plugins_url( '', SALES_TRACKER_FILE ) );
			define( 'SALES_TRACKER_ASSETS', SALES_TRACKER_URL . '/assets' );
		}

		/**
		 * Initialize the plugin functions
		 */
		public function init_plugin() {

			new \Sales\Tracker\Assets();

			if ( is_admin() ) {
				new Sales\Tracker\Admin();
			} else {
				new \Sales\Tracker\Frontend();
			}

			new \Sales\Tracker\API();
		}

		/**
		 * Track installation time and installed version when installed
		 *
		 * @return void
		 */
		public function activate() {
			$installer = new \Sales\Tracker\Installer();
			$installer->run();
		}
	}
}



if ( ! function_exists( 'sales_tracker' ) ) {

	/**
	 * Initialize the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return \Sales_Tracker
	 */
	function sales_tracker() {
		return Sales_Tracker::init();
	}

	// Start the plugin
	sales_tracker();
}
