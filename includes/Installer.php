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

        if( ! $installed ) {
            update_option( 'sales_tracker_installed', time() );
        }
        update_option('sales_tracker_version', SALES_TRACKER_VERSION );
    }

    /**
     * Create necessary database table
     * 
     * @return void
     */
    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sales_tracker_tracks` (
            `id` bigint NOT NULL AUTO_INCREMENT,
            `amount` int NOT NULL,
            `buyer` varchar(255) NOT NULL,
            `receipt_id` varchar(20) DEFAULT NULL,
            `items` varchar(255) DEFAULT NULL,
            `buyer_email` varchar(50) NOT NULL,
            `buyer_ip` varchar(20) DEFAULT NULL,
            `note` text,
            `city` varchar(20) DEFAULT NULL,
            `phone` varchar(20) DEFAULT NULL,
            `hash_key` varchar(255) DEFAULT NULL,
            `entry_at` date DEFAULT NULL,
            `entry_by` bigint DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }
}