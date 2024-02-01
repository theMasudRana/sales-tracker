<?php

/**
 * Insert track item in to the database
 * 
 * @param array $args
 * 
 * @return int|WP_Error
 */
function st_insert_track( $args = [] ) {
    global $wpdb;

    $defaults = [
        'amount'      => '',
        'buyer'       => '',
        'receipt_id'  => '',
        'items'       => '',
        'buyer_email' => '',
        'buyer_ip'    => $_SERVER['REMOTE_ADDR'],
        'note'        => '',
        'city'        => '',
        'phone'       => '',
        'hash_key'    => '',
        'entry_at'    => current_time( 'mysql' ),
        'entry_by'    => get_current_user_id(),
    ];

    $data     = wp_parse_args( $args, $defaults );
    $inserted = $wpdb->insert( 
        "{$wpdb->prefix}sales_tracker_tracks",
        $data,
        [
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
        ]
    );

    if( ! $inserted ) {
        return new \WP_Error( 'failed-to-insert', esc_html__( 'Failed to insert data.', 'sales-tracker' ) );
    }

    return $wpdb->inserted_id;
}
