<?php
namespace Sales\Tracker;

/**
 * Installer class
 */
class Installer {

	/**
	 * Run the class
	 *
	 * @return void
	 */
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	/**
	 * Add plugin version
	 *
	 * @return void
	 */
	public function add_version() {
		$installed = get_option( 'sales_tracker_installed' );

		if ( ! $installed ) {
			update_option( 'sales_tracker_installed', time() );
		}
		update_option( 'sales_tracker_version', SALES_TRACKER_VERSION );
	}

	/**
	 * Create necessary database table
	 *
	 * @return void
	 */
	public function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sales_tracker_sales` (
			`id` BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`amount` INT(10) NOT NULL,
			`buyer` VARCHAR(255) NOT NULL,
			`receipt_id` VARCHAR(20) DEFAULT NULL,
			`items` VARCHAR(255) DEFAULT NULL,
			`buyer_email` VARCHAR(50) NOT NULL,
			`buyer_ip` VARCHAR(20) DEFAULT NULL,
			`note` TEXT,
			`city` VARCHAR(20) DEFAULT NULL,
			`phone` VARCHAR(20) DEFAULT NULL,
			`hash_key` VARCHAR(255) DEFAULT NULL,
			`entry_at` DATE DEFAULT NULL,
			`entry_by` INT(10) DEFAULT NULL
		) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . '/wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}
}
