<?php
/**
 * Strategy11_Controller class.
 *
 * @package   Strategy11/Sherv_Challenge
 * @license   GNU General Public License 3.0
 * @link      https://strategy11.com/
 * @copyright 2022 Strategy11
 */

/**
 * Copyright (C) 2022 Strategy11.
 *
 * Licensed under GNU GPL, Version 3.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * ADDITIONAL TERMS per GNU GPL Section 7 The origin of the Program
 * must not be misrepresented; you must not claim that you wrote
 * the original Program. Altered source versions must be plainly marked
 * as such, and must not be misrepresented as being the original Program.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Strategy11\Sherv_Challenge\REST_API;

use WP_REST_Server;
use WP_REST_Response;
use WP_Http;
use WP_Error;
use Strategy11\Sherv_Challenge\Remove_Transients;
/**
 * Strategy11_Controller class.
 *
 * @since 1.0.0
 */
class Strategy11_Controller extends REST_Controller {

	/**
	 * Developer applicant challenge api endpoint.
	 *
	 * @var string
	 */
	private const API_ENDPOINT = 'http://api.strategy11.com/wp-json/challenge/v1/1';

	/**
	 * Strategy11_Controller constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->namespace = 'sherv-challenge/v1';
		$this->rest_base = 'strategy11-data';
	}

	/**
	 * Registers routes for links.
	 *
	 * @since 1.0.0
	 *
	 * @see register_rest_route()
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'                => $this->get_collection_params(),
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Checks if a given request has access to get and create items.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		// Let anyone read the data.
		return true;
	}

	/**
	 * Retrieves all data.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$url = urldecode( untrailingslashit( static::API_ENDPOINT ) );

		/**
		 * Filters the link data TTL value.
		 *
		 * @since 1.0.0
		 *
		 * @param int $time Time to live (in seconds). Default is 1 hour.
		 * @param string $url The attempted URL.
		 */
		$cache_ttl = apply_filters( 'sherv_challenge_strategy11_data_cache_ttl', HOUR_IN_SECONDS, $url );
		$cache_key = 'sherv_challenge_strategy11_data_' . md5( $url );

		$data = get_transient( $cache_key );

		if ( is_string( $data ) && ! empty( $data ) ) {
			/**
			 * Decoded cached strategy11 data.
			 *
			 * @var array<string,mixed>|null $strategy11_data
			 */
			$strategy11_data = json_decode( $data, true );

			if ( $strategy11_data ) {
				$response = $this->prepare_data_for_response( $strategy11_data, $request );
				return rest_ensure_response( $response );
			}
		}

		$args = [
			'method'  => 'GET',
			'timeout' => 7,
		];

		/**
		 * Filters the HTTP request args for link data retrieval.
		 *
		 * Can be used to adjust timeout and response size limit.
		 *
		 * @since 1.0.0
		 *
		 * @param array<string,mixed> $args Arguments used for the HTTP request
		 * @param string $url The attempted URL.
		 */
		$args = apply_filters( 'sherv_challenge_strategy11_data_request_args', $args, $url );

		$response = wp_remote_request( $url, $args );

		if ( WP_Http::OK !== wp_remote_retrieve_response_code( $response ) ) {
			// Not saving the error response to cache since the error might be temporary.
			return new WP_Error( 'rest_invalid_url', __( 'Invalid URL', 'sherv-challenge' ), [ 'status' => 404 ] );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $data ) {
			return new WP_Error( 'rest_invalid_story', __( 'Connect to Strategy11 remote endpoint failed.', 'sherv-challenge' ), [ 'status' => 404 ] );
		}

		$response = $this->prepare_data_for_response( $data, $request );

		set_transient( $cache_key, wp_json_encode( $data ), $cache_ttl );

		return rest_ensure_response( $response );
	}

	/**
	 * Prepares data output for response.
	 *
	 * @since 1.0.0
	 *
	 * @param Product         $data    Data object.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_data_for_response( $data, $request ): WP_REST_Response {
		$fields = $this->get_fields_for_response( $request );

		$sanitized_data = [];

		if ( rest_is_field_included( 'title', $fields ) ) {
			$sanitized_data['title'] = sanitize_text_field( $data['title'] );
		}

		if ( rest_is_field_included( 'headers', $fields ) ) {
			$sanitized_data['header'] = [];
			foreach ( $data['data']['headers'] as $item ) {
				$sanitized_data['header'][] = sanitize_text_field( $item );
			}
		}

		if ( rest_is_field_included( 'rows', $fields ) ) {
			$sanitized_data['body'] = [];
			foreach ( $data['data']['rows'] as $row ) {
				$row['id']                = isset( $row['id'] ) ? absint( $row['id'] ) : '';
				$row['fname']             = isset( $row['fname'] ) ? sanitize_text_field( $row['fname'] ) : '';
				$row['lname']             = isset( $row['lname'] ) ? sanitize_text_field( $row['lname'] ) : '';
				$row['email']             = isset( $row['email'] ) ? sanitize_email( $row['email'] ) : '';
				$row['date']              = ( isset( $row['date'] ) && is_int( $row['date'] ) ) ? gmdate( 'd/m/Y', $row['date'] ) : '';
				$sanitized_data['body'][] = $row;
			}
		}

		/**
		 * Response object.
		 *
		 * @var WP_REST_Response $response
		 */
		$response = rest_ensure_response( $sanitized_data );

		return $response;
	}

	/**
	 * Retrieves the product schema, conforming to JSON Schema.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 *
	 * @since 1.0.0
	 *
	 * @return array Item schema data.
	 *
	 * @phpstan-return Schema
	 */
	public function get_item_schema(): array {
		if ( $this->schema ) {
			$schema = $this->add_additional_fields_schema( $this->schema );
			return $schema;
		}

		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'strategy11-data',
			'type'       => 'object',
			'properties' => [
				'title'   => [
					'type'     => 'string',
					'readonly' => true,
				],
				'headers' => [
					'type'     => 'array',
					'readonly' => true,
				],
				'rows'    => [
					'type'     => 'array',
					'readonly' => true,
				],
			],
		];

		$this->schema = $schema;

		$schema = $this->add_additional_fields_schema( $this->schema );
		return $schema;
	}

	/**
	 * Retrieves the query params for the strategy11 data collection.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string, array<string, mixed>> Collection parameters.
	 */
	public function get_collection_params(): array {
		$query_params = parent::get_collection_params();

		return $query_params;
	}
}
