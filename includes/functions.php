<?php

/**
 * Insert track item in to the database
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function st_insert_track( $args = array() ) {
	global $wpdb;

	$defaults = array(
		'amount'      => '',
		'buyer'       => '',
		'receipt_id'  => '',
		'items'       => '',
		'buyer_email' => '',
		'buyer_ip'    => '',
		'note'        => '',
		'city'        => '',
		'phone'       => '',
		'hash_key'    => '',
		'entry_at'    => '',
		'entry_by'    => '',
	);

	$data = wp_parse_args( $args, $defaults );

	if ( isset( $data['id'] ) ) {

		$id = $data['id'];
		unset( $data['id'] );

		$updated = $wpdb->update(
			"{$wpdb->prefix}sales_tracker_sales",
			$data,
			array( 'id' => $id ),
			array(
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
			),
			array( '%d' )
		);

		return $updated;

	} else {
		$inserted = $wpdb->insert(
			"{$wpdb->prefix}sales_tracker_sales",
			$data,
			array(
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
			)
		);

		if ( ! $inserted ) {
			return new \WP_Error( 'failed-to-insert', esc_html__( 'Failed to insert data.', 'sales-tracker' ) );
		}

		return $wpdb->insert_id;
	}
}

/**
 * Get All the tracks
 *
 * @return array
 */
function st_get_sales( $args = array() ) {
	global $wpdb;

	$defaults = array(
		'number'  => 20,
		'offset'  => 0,
		'orderby' => 'id',
		'order'   => 'DESC',
	);

	$args = wp_parse_args( $args, $defaults );

	$args['number'] = absint( $args['number'] );
	$args['offset'] = absint( $args['offset'] );

	$sql = $wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}sales_tracker_sales
        ORDER BY {$args['orderby']} {$args['order']}
        LIMIT %d, %d",
		$args['offset'],
		$args['number']
	);

	$items = $wpdb->get_results( $sql );

	return $items;
}

/**
 * Numbers of tracks saved in the database.
 *
 * @return int
 */
function st_sales_count() {
	global $wpdb;

	return (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}sales_tracker_sales" );
}

/**
 * Filters table data based on a search key.
 *
 * @param array $table_data The table data to filter.
 * @param string $search_key The search key to filter by.
 *
 * @return array The filtered table data.
 */
function st_filter_sales( $table_data, $search_key ) {
	$filtered_table_data = array_values(
		array_filter(
			$table_data,
			function ( $row ) use( $search_key ) {
				foreach ( $row as $row_val ) {
					if ( stripos( $row_val, $search_key ) !== false ) {
						return true;
					}
				}
			}
		)
	);

	return $filtered_table_data;
}

/**
 * Get single sale item
 *
 * @return object
 */
function st_get_sale( $id ) {
	global $wpdb;

	$sale_item = $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sales_tracker_sales WHERE id = %d", $id )
	);

	return $sale_item;
}

/**
 * Delate sale item
 *
 * @return int|boolean
 */
function st_delete_sale( $id ) {
	global $wpdb;

	return $wpdb->delete(
		$wpdb->prefix . 'sales_tracker_sales',
		array( 'id' => $id ),
		array( '%d' )
	);
}
