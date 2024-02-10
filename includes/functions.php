<?php

/**
 * Insert track item in to the database
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function st_insert_sale( $args = array() ) {
	global $wpdb;

	$entry_by   = ! empty( $_POST['entry_by'] ) ? sanitize_text_field( $_POST['entry_by'] ) : get_current_user_id();
	$receipt_id = ! empty( $_POST['receipt_id'] ) ? sanitize_text_field( $_POST['receipt_id'] ) : '';
	$salt       = bin2hex( random_bytes( 8 ) );

	$defaults = array(
		'amount'      => '',
		'buyer'       => '',
		'receipt_id'  => '',
		'items'       => '',
		'buyer_email' => '',
		'buyer_ip'    => $_SERVER['REMOTE_ADDR'],
		'note'        => '',
		'city'        => '',
		'phone'       => '',
		'hash_key'    => hash( 'sha512', $receipt_id . $salt ),
		'entry_at'    => current_time( 'mysql' ),
		'entry_by'    => $entry_by,
	);

	$data = wp_parse_args( $args, $defaults );

	if ( isset( $data['id'] ) ) {

		$id = $data['id'];
		unset( $data['id'] );

		$updated = $wpdb->update(
			"{$wpdb->prefix}st_sales",
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
			"{$wpdb->prefix}st_sales",
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
		'start_date' => '',
		'end_date' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$args['number'] = absint( $args['number'] );
	$args['offset'] = absint( $args['offset'] );

	$args['start_date'] = sanitize_text_field( $args['start_date'] );
	$args['end_date'] = sanitize_text_field( $args['end_date'] );
	


	$sql = '';

	if ( ! empty( $args['start_date'] ) && ! empty( $args['end_date'] ) ) {
		$sql = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}st_sales
			WHERE entry_at BETWEEN %s AND %s
			ORDER BY {$args['orderby']} {$args['order']}
			LIMIT %d, %d",
			$args['start_date'],
			$args['end_date'],
			$args['offset'],
			$args['number'],
		);
	} else {
		$sql = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}st_sales
			ORDER BY {$args['orderby']} {$args['order']}
			LIMIT %d, %d",
			$args['offset'],
			$args['number'],
		);
	}

	// error_log( print_r( $sql, true ) );


	$items = $wpdb->get_results( $sql );

	return $items;
}

/**
 * Numbers of tracks saved in the database.
 *
 * @return int
 */
function st_sales_count( $args ) {
	global $wpdb;

	$start_date = isset( $args['start_date'] ) ? $args['start_date'] : '';
	$end_date   = isset( $args['end_date'] ) ? $args['end_date'] : '';

	if ( empty( $start_date ) || empty( $end_date ) ) {
		return (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}st_sales" );
	}

	$sql = $wpdb->prepare(
		"SELECT count(id) FROM {$wpdb->prefix}st_sales
		WHERE entry_at BETWEEN %s AND %s",
		$start_date,
		$end_date
	);

	return (int) $wpdb->get_var( $sql );
}

/**
 * Filters table data based on a search key.
 *
 * @param array  $table_data The table data to filter.
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
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}st_sales WHERE id = %d", $id )
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
		$wpdb->prefix . 'st_sales',
		array( 'id' => $id ),
		array( '%d' )
	);
}

/**
 * Delete all sales
 *
 * @return int|boolean
 */
function st_delete_all_sales() {
	global $wpdb;
	$sales_table = $wpdb->prefix . 'st_sales';
	$sales       = ! empty( $_REQUEST['sale'] ) && is_array( $_REQUEST['sale'] ) ? $_REQUEST['sale'] : array();

	if ( ! empty( $sales ) ) {
		$sales        = array_map( 'intval', $sales ); // Ensure all values are integers
		$placeholders = implode( ', ', array_fill( 0, count( $sales ), '%d' ) );
		$sql          = "DELETE FROM $sales_table WHERE id IN($placeholders)";
		$wpdb->query( $wpdb->prepare( $sql, $sales ) );
	}
}
