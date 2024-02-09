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
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_sale' ),
					'permission_callback' => array( $this, 'get_delete_sale_permissions_check' ),
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
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
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

		unset( $args['per_page'] );
		unset( $args['page'] );

		$data  = array();
		$sales = st_get_sales( $args );

		foreach ( $sales as $sale ) {
			$response = $this->prepare_item_for_response( $sale, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}

		$total     = st_sales_count();
		$max_pages = ceil( $total / (int) $args['number'] );
		$response  = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

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
	public function get_delete_sale_permissions_check( $request ) {
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
				esc_html__( 'Sorry, the sale item could not be deleted.' ),
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
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'buyer'       => array(
					'description' => __( 'Name of the buyer.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'amount'      => array(
					'description' => __( 'Sale amount.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'receipt_id'  => array(
					'description' => __( 'Receipt ID of the sale.' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'phone'       => array(
					'description' => __( 'Phone number of the buyer.' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'buyer_email' => array(
					'description' => __( 'Email address of the buyer.' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_email',
					),
				),
				'city'        => array(
					'description' => __( 'Buyer city.' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'date'        => array(
					'description' => __( "The date the object was published, in the site's timezone." ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'items'       => array(
					'description' => __( 'Purchased items' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),
				'note'        => array(
					'description' => __( 'Purchase note' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),
				'entry_by'    => array(
					'description' => __( 'Stuff ID' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'entry_at'    => array(
					'description' => __( 'Entry Date' ),
					'type'        => 'string',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}
}
