<?php

namespace Sales\Tracker;

/**
 * Assets loader
 */
class Assets {

    /**
     * Load the scripts and styles in respective hook.
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_frontend_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
    }

    /**
     * Get all the frontend scripts as an array
     * 
     * @return array
     */
    public function get_frontend_scripts() {
        return [
            'sales-tracker-frontend-script' => [
                'src'     => SALES_TRACKER_ASSETS . '/js/frontend.js',
                'version' => SALES_TRACKER_VERSION,
                'deps'    => [ 'jquery' ]
            ],
        ];
    }

    /**
     * Get all the frontend styles as an array
     * 
     * @return array
     */
    public function get_frontend_styles() {
        return [
            'sales-tracker-frontend-style'  => [
                'src'     => SALES_TRACKER_ASSETS . '/css/frontend.css',
                'version' => SALES_TRACKER_VERSION,
                'deps'    => false,
            ]
        ];
    }

    /**
     * Get all the admin styles as an array
     * 
     * @return array
     */
    public function get_admin_styles() {
        return [
            'sales-tracker-admin-style'  => [
                'src'     => SALES_TRACKER_ASSETS . '/css/admin.css',
                'version' => SALES_TRACKER_VERSION,
            ]
        ];
    }

    /**
     * Get all the frontend scripts as an array
     * 
     * @return array
     */
    public function get_admin_scripts() {
        return [
            'sales-tracker-admin-script' => [
                'src'     => SALES_TRACKER_ASSETS . '/js/admin.js',
                'version' => SALES_TRACKER_VERSION,
                'deps'    => [ 'jquery' ]
            ],
        ];
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
            $deps = isset ( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $style['version'], $deps );
        }

        foreach ( $frontend_scripts as $handle => $script ) {
            $deps = isset ( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }
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
            $deps = isset ( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $style['version'], $deps );
        }

        foreach ( $admin_scripts as $handle => $script ) {
            $deps = isset ( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }
    }
}