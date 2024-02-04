<?php

namespace Sales\Tracker\Admin;

if ( ! class_exists( 'WP_List_table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Display Track List
 */
class Sales_List extends \WP_List_Table {

	/**
	 * Call parent construct for args
	 */
	function __construct() {
		parent::__construct(
			array(
				'singular' => 'track',
				'plural'   => 'tracks',
				'ajax'     => false,
			)
		);
	}

	/**
	 * No items found
	 *
	 * @return void
	 */
	function no_items() {
		esc_html_e( 'No sales data found', 'sales-tracker' );
	}

	/**
	 * Get table columns
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'          => '<input type = "checkbox" />',
			'buyer'       => esc_html__( 'Buyer', 'sales-tracker' ),
			'buyer_email' => esc_html__( 'Email', 'sales-tracker' ),
			'phone'       => esc_html__( 'Phone', 'sales-tracker' ),
			'amount'      => esc_html__( 'Amount', 'sales-tracker' ),
			'entry_at'    => esc_html__( 'Date', 'sales-tracker' ),
			'items'       => esc_html__( 'Items', 'sales-tracker' ),
		);
	}

	/**
	 * Make column sortable
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		$sortable_columns = array(
			'amount'   => array( 'amount', true ),
			'entry_at' => array( 'entry_at', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Set the bulk actions
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		$actions = array(
			'trash' => esc_html__( 'Move to Trash', 'sales-tracker' ),
		);

		return $actions;
	}

	/**
	 * Default column values
	 *
	 * @param  object $item
	 * @param  string $column_name
	 *
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'entry_at':
				return wp_date( get_option( 'date_format' ), strtotime( $item->entry_at ) );
			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	/**
	 * Buyer column
	 *
	 * @param  object $item
	 *
	 * @return string
	 */
	public function column_buyer( $item ) {
		$actions           = array();
		$actions['edit']   = sprintf( '<a href = "%s" title = "%s">%s</a>', admin_url( 'admin.php?page=sales-tracker&action=edit&id=' . $item->id ), $item->id, esc_html__( 'Edit', 'sales-tracker' ), esc_html__( 'Edit', 'sales-tracker' ) );
		$actions['delete'] = sprintf( '<a href="%s" class="st-delete-link" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=st_delete_sale&id=' . $item->id ), 'st_delete_sale' ), $item->id, esc_html__( 'Delete', 'sales-tracker' ), esc_html__( 'Delete', 'sales-tracker' ) );

		return sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s',
			admin_url( 'admin.php?page=sales-tracker&action=view&id=' . $item->id ),
			$item->buyer,
			$this->row_actions( $actions )
		);
	}


	/**
	 * Add the checkbox inside each item
	 *
	 * @param  object $item
	 *
	 * @return string
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="track_id[]" value="%d" />',
			$item->id
		);
	}

	/**
	 * Prepare items to display
	 *
	 * @return void
	 */
	public function prepare_items() {
		$column   = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $column, $hidden, $sortable );

		$per_page     = 10;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$args = array(
			'number' => $per_page,
			'offset' => $offset,
		);

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['orderby'] = $_REQUEST['orderby'];
			$args['order']   = $_REQUEST['order'];
		}

		$this->items = st_get_sales( $args );

		$user_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		if ( $user_search_key ) {
			$this->items = st_filter_sales( $this->items, $user_search_key );
		}

		$this->set_pagination_args(
			array(
				'total_items' => st_sales_count(),
				'per_page'    => $per_page,
			)
		);
	}
}
