<?php

namespace Sales\Tracker\Admin;

/**
 * Tracker handler class
 */
class Tracker {

    /**
     * Sales tracker page routing
     * 
     * @return void
     */
    public function tracker_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';

        switch ($action) {
            case 'new' :
                $template = __DIR__ . '/views/track-new.php';
                break;

            case 'edit' :
                $template = __DIR__ . '/views/track-edit.php';
                break;

            case 'view' :
                $template = __DIR__ . '/views/track-view.php';
                break;

            default:
                $template = __DIR__ . '/views/track-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * New sales item form handler
     */
    public function form_handler() {

        if ( ! isset( $_POST['add_tracker_item'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-tracker-item' ) ) {
            wp_die('You are not authorized to submit this form.');
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die('You are not authorized to submit this form.');
        }

        echo "<pre>";
        var_dump( $_POST );
        echo "</pre>";

        exit;
    }

}