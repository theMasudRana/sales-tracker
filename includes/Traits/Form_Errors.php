<?php

namespace Sales\Tracker\Traits;

trait Form_Errors {
    /**
     * Store error messages
     */
    public $errors = [];

    /**
     * Check if the form has error with given key
     * 
     * @param string $key
     * 
     * @return boolean
     */
    public function has_error( $key ) {
        if ( isset( $this->errors[ $key ] ) ) {
            return true;
        }
        return false;
    }
    /**
     * Check if the form has error with given key
     * 
     * @param string $key
     * 
     * @return string|false
     */
    public function get_error( $key ) {
        if ( isset( $this->errors[ $key ] ) ) {
            return $this->errors[ $key ];
        }
        return false;
    }
}