<?php

namespace Sales\Tracker\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Sales API
 */
class Sales extends WP_REST_Controller {

	/**
	 * Initialize the class
	 */
	function __construct() {
		$this->namespace = 'sales-tracker/v1';
		$this->rest_base = 'sales';
	}

	/**
	 * Register REST API Routes
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_sales' ),
					'permission_callback' => array( $this, 'get_sales_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_sale' ),
					'permission_callback' => array( $this, 'create_sale_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => esc_html__( 'Unique identifier for the object.', 'sales-track' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_sale' ),
					'permission_callback' => array( $this, 'get_sale_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_sale' ),
					'permission_callback' => array( $this, 'update_sale_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_sale' ),
					'permission_callback' => array( $this, 'delete_sale_permissions_check' ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);
	}

	/**
	 * Check if has permission to get sales
	 *
	 * @param  \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function get_sales_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get all the sales collection
	 *
	 * @param  \WP_Rest_Request $request
	 *
	 * @return \WP_Rest_Response|WP_Error
	 */
	public function get_sales( $request ) {
		$args = array();

		$params = $this->get_collection_params();

		foreach ( $params as $key => $value ) {
			
			if ( isset( $request[ $key ] ) ) {
				$args[ $key ] = $request[ $key ];
			}
		}

		$args['number'] = $args['per_page'];
		$args['offset'] = $args['number'] * ( $args['page'] - 1 );
		$current_page   = (int) $args['page'];

		$start_date = isset( $_GET['start_date'] ) ? $_GET['start_date'] : '';
		$end_date   = isset( $_GET['end_date'] ) ? $_GET['end_date'] : '';

		$args['start_date'] = $start_date;
		$args['end_date']   = $end_date;

		unset( $args['per_page'] );
		unset( $args['page'] );

		$data  = array();
		

		$sales = st_get_sales( $args ); // I have all the data her

		foreach ( $sales as $sale ) {
			$response = $this->prepare_item_for_response( $sale, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}

		$total        = st_sales_count( $args );

		$response  = rest_ensure_response( [
			'data'	       => $data,
			'current_page' => $current_page,
			'total'        => $total,
		] );

		return $response;
	}

	/**
	 * Checks permission for single sale
	 *
	 * @param  \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function get_sale_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return true;
		}

		$sale = $this->get_sale_item( $request['id'] );

		if ( is_wp_error( $sale ) ) {
			return $sale;
		}

		return true;
	}

	/**
	 * Get single sale item
	 *
	 * @param int $id
	 *
	 * @return Object|WP_error
	 */
	protected function get_sale_item( $id ) {
		$sale = st_get_sale( $id );

		if ( ! $sale ) {
			return new WP_Error(
				'rest_sale_invalid_id',
				esc_html__( 'Invalid sale ID', 'sales-tracker' ),
				array( 'status' => 404 )
			);
		}

		return $sale;
	}

	/**
	 * Get single sale.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_sale( $request ) {
		$sale = $this->get_sale_item( $request['id'] );

		$response = $this->prepare_item_for_response( $sale, $request );
		$response = rest_ensure_response( $response );

		return $response;
	}

	/**
	 * Checks if a given request has access to delete sale.
	 *
	 * @param  \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function delete_sale_permissions_check( $request ) {
		return $this->get_sale_permissions_check( $request );
	}

	/**
	 * Delete single sale item
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function delete_sale( $request ) {
		$sale     = $this->get_sale_item( $request['id'] );
		$previous = $this->prepare_item_for_response( $sale, $request );

		$deleted = st_delete_sale( $request['id'] );

		if ( ! $deleted ) {
			return new WP_Error(
				'rest_not_deleted',
				esc_html__( 'Sorry, the sale item could not be deleted.', 'sales-tracker' ),
				array( 'status' => 400 )
			);
		}

		$data = array(
			'deleted'  => true,
			'previous' => $previous->get_data(),
		);

		$response = rest_ensure_response( $data );

		return $data;
	}

	/**
	 * Checks if a given request has access to create sale item
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function create_sale_permissions_check( $request ) {
		return $this->get_sales_permissions_check( $request );
	}

	/**
	 * Creates sale item
	 *
	 * @param \WP_REST_Request
	 *
	 * @return \WP_ERROR|WP_REST_Response
	 */
	public function create_sale( $request ) {
		$sale = $this->prepare_item_for_database( $request );

		if ( is_wp_error( $sale ) ) {
			return $sale;
		}

		$sale_id = st_insert_sale( $sale );

		if ( is_wp_error( $sale_id ) ) {
			$sale_id->add_data( array( 'status' => 400 ) );

			return $sale_id;
		}

		$sale     = $this->get_sale_item( $sale_id );
		$response = $this->prepare_item_for_response( $sale, $request );

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $sale_id ) ) );

		return rest_ensure_response( $response );
	}

	/**
	 * Prepares the item for the REST response.
	 *
	 * @param mixed            $item    WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return \WP_Error|WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data   = array();
		$fields = $this->get_fields_for_response( $request );

		if ( in_array( 'id', $fields, true ) ) {
			$data['id'] = (int) $item->id;
		}

		if ( in_array( 'buyer', $fields, true ) ) {
			$data['buyer'] = $item->buyer;
		}

		if ( in_array( 'amount', $fields, true ) ) {
			$data['amount'] = $item->amount;
		}

		if ( in_array( 'phone', $fields, true ) ) {
			$data['phone'] = $item->phone;
		}

		if ( in_array( 'receipt_id', $fields, true ) ) {
			$data['receipt_id'] = $item->receipt_id;
		}

		if ( in_array( 'items', $fields, true ) ) {
			$data['items'] = $item->items;
		}

		if ( in_array( 'buyer_email', $fields, true ) ) {
			$data['buyer_email'] = $item->buyer_email;
		}

		if ( in_array( 'note', $fields, true ) ) {
			$data['note'] = $item->note;
		}

		if ( in_array( 'city', $fields, true ) ) {
			$data['city'] = $item->city;
		}

		if ( in_array( 'phone', $fields, true ) ) {
			$data['phone'] = $item->phone;
		}

		if ( in_array( 'entry_at', $fields, true ) ) {
			$data['entry_at'] = mysql_to_rfc3339( $item->entry_at );
		}

		if ( in_array( 'entry_by', $fields, true ) ) {
			$data['entry_by'] = $item->entry_by;
		}

		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		$response->add_links( $this->prepare_links( $item ) );

		return $response;
	}

	/**
	 * Prepares one item for create or update operation.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|object
	 */
	protected function prepare_item_for_database( $request ) {
		$prepared = array();

		if ( isset( $request['amount'] ) ) {
			$prepared['amount'] = $request['amount'];
		}

		if ( isset( $request['buyer'] ) ) {
			$prepared['buyer'] = $request['buyer'];
		}

		if ( isset( $request['receipt_id'] ) ) {
			$prepared['receipt_id'] = $request['receipt_id'];
		}

		if ( isset( $request['items'] ) ) {
			$prepared['items'] = $request['items'];
		}

		if ( isset( $request['buyer_email'] ) ) {
			$prepared['buyer_email'] = $request['buyer_email'];
		}

		if ( isset( $request['note'] ) ) {
			$prepared['note'] = $request['note'];
		}

		if ( isset( $request['city'] ) ) {
			$prepared['city'] = $request['city'];
		}

		if ( isset( $request['phone'] ) ) {
			$prepared['phone'] = $request['phone'];
		}

		if ( isset( $request['entry_by'] ) ) {
			$prepared['entry_by'] = $request['entry_by'];
		}

		return $prepared;
	}

	/**
	 * Checks if a given request has access to update sale item
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function update_sale_permissions_check( $request ) {
		return $this->get_sale_permissions_check( $request );
	}

	/**
	 * Update sale item
	 *
	 * @param \WP_REST_Request
	 *
	 * @return \WP_ERROR|WP_REST_Response
	 */
	public function update_sale( $request ) {
		$sale     = $this->get_sale_item( $request['id'] );
		$prepared = $this->prepare_item_for_database( $request );

		$prepared = array_merge( (array) $sale, $prepared );

		$updated = st_insert_sale( $prepared );

		if ( ! $updated ) {
			return new WP_Error(
				'rest_not_updated',
				esc_html__( 'Sorry, the sale could not be updated', 'sales-tracker' ),
				array( 'status' => 400 )
			);
		}

		$sale     = $this->get_sale_item( $request['id'] );
		$response = $this->prepare_item_for_response( $sale, $request );

		return rest_ensure_response( $response );
	}

	/**
	 * Prepares links for the request.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $item ) {
		$base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

		$links = array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $item->id ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);

		return $links;
	}

	/**
	 * Retrieves the sales schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'sales',
			'type'       => 'object',
			'properties' => array(
				'id'          => array(
					'description' => esc_html__( 'Unique identifier for the object.', 'sales-tracker' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'amount'      => array(
					'description' => esc_html__( 'Sale amount.', 'sales-tracker' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'buyer'       => array(
					'description' => esc_html__( 'Name of the buyer.', 'sales-tracker' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'receipt_id'  => array(
					'description' => esc_html__( 'Receipt ID of the sale.', 'sales-tracker' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'items'       => array(
					'description' => esc_html__( 'Purchased items', 'sales-tracker' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),
				'buyer_email' => array(
					'description' => esc_html__( 'Email address of the buyer.', 'sales-tracker' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_email',
					),
				),
				'note'        => array(
					'description' => esc_html__( 'Purchase note', 'sales-tracker' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),
				'city'        => array(
					'description' => esc_html__( 'Buyer city.', 'sales-tracker' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'phone'       => array(
					'description' => esc_html__( 'Phone number of the buyer.', 'sales-tracker' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'entry_at'    => array(
					'description' => esc_html__( 'Entry Date', 'sales-tracker' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'entry_by'    => array(
					'description' => esc_html__( 'Stuff ID', 'sales-tracker' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			),
		);

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}
}
