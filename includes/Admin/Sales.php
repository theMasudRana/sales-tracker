<?php

namespace Sales\Tracker\Admin;
use Sales\Tracker\Traits\Form_Errors;

/**
 * Sale handler class
 */
class Sales {

    use Form_Errors;

    /**
     * Sales tracker page routing
     * 
     * @return string
     */
    public function sales_tracker_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
    
        $templates = [
            'new'  => __DIR__ . '/views/sale-new.php',
            'edit' => __DIR__ . '/views/sale-edit.php',
            'view' => __DIR__ . '/views/sale-view.php',
            'list' => __DIR__ . '/views/sale-list.php',
        ];
    
        $template = isset( $templates[$action] ) ? $templates[$action] : $templates['list'];
    
        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * New sales item form handler
     * 
     * @return bool|WP_Error True on success, WP_Error on failure.
     */
    public function form_handler() {

        if ( ! isset( $_POST['add_tracker_item'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-tracker-item' ) ) {
            wp_die( esc_html__( 'You are not authorized to submit this form.', 'sales-tracker' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not authorized to submit this form.', 'sales-tracker' ) );
        }

        $amount      = isset( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] )        : '';
        $buyer       = isset( $_POST['buyer'] ) ? sanitize_text_field( $_POST['buyer'] )          : '';
        $receipt_id  = isset( $_POST['receipt_id'] ) ? sanitize_text_field( $_POST['receipt_id'] ): '';
        $items       = isset( $_POST['items'] ) ? sanitize_text_field( $_POST['items'] )          : '';
        $buyer_email = isset( $_POST['buyer_email'] ) ? sanitize_email( $_POST['buyer_email'] )   : '';
        $note        = isset( $_POST['note'] ) ? sanitize_textarea_field( $_POST['note'] )        : '';
        $city        = isset( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] )            : '';
        $phone       = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] )          : '';
        $entry_by    = isset( $_POST['entry_by'] ) ? sanitize_text_field( $_POST['entry_by'] )    : '';

        if ( empty( $amount ) ) {
            $this->errors['amount'] = esc_html__( 'Please enter amount.', 'sales-tracker' );
        }

        if ( empty( $buyer ) ) {
            $this->errors['buyer'] = esc_html__( 'Please enter buyer name.', 'sales-tracker' );
        }

        if ( empty( $buyer_email ) ) {
            $this->errors['buyer_email'] = esc_html__( 'Please enter buyer email.', 'sales-tracker' );
        }

        if ( ! empty( $this->errors ) ) {
            return ;
        }

        $insert_id = st_insert_track( [
            'amount'      => $amount,
            'buyer'       => $buyer,
            'receipt_id'  => $receipt_id,
            'items'       => $items,
            'buyer_email' => $buyer_email,
            'note'        => $note,
            'city'        => $city,
            'phone'       => $phone,
            'entry_by'    => $entry_by,
        ] );

        if ( is_wp_error( $insert_id ) ) {
            wp_die( $insert_id->get_error_message() );
        }

        $redirect_to = admin_url( 'admin.php?page=sales-tracker&inserted=true' );
        wp_redirect( $redirect_to );

        exit;
    }
}