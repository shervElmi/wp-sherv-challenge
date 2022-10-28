<?php
/**
 * Strategy11_Controller_Test class.
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

namespace Strategy11\Sherv_Challenge\Tests;

use Yoast\WPTestUtils\WPIntegration\TestCase;
use WP_REST_Request;
use Strategy11\Sherv_Challenge\{Plugin_Factory, Plugin, Remove_Transients};
use Strategy11\Sherv_Challenge\REST_API\Strategy11_Controller;

/**
 * Strategy11_Controller_Test class.
 *
 * @since 1.0.0
 */
class Strategy11_Controller_Test extends TestCase {

	/**
	 * Plugin instance to test with.
	 *
	 * @var Plugin
	 */
	protected static $plugin;

	/**
	 * Test instance.
	 *
	 * @var Strategy11_Controller
	 */
	protected static $controller;

	/**
	 * Remove_Transients instance.
	 *
	 * @var Remove_Transients
	 */
	protected static $remove_transients;

	/**
	 * WP_REST_Request instance.
	 *
	 * @var WP_REST_Request
	 */
	private static $wp_rest_request;

	/**
	 * Set up the component architecture before run tests.
	 */
	public static function set_up_before_class() : void {
		parent::set_up_before_class();

		do_action( 'rest_api_init' );

		static::$plugin = Plugin_Factory::create();
		static::$plugin->register();

		$container                 = static::$plugin->get_container();
		static::$remove_transients = $container->get( 'remove_transients' );
		static::$controller        = $container->get( 'rest.strategy11_controller' );

		static::$wp_rest_request = new WP_REST_Request( 'GET', 'sherv-challenge/v1/strategy11-data' );
	}

	public static function tear_down_after_class() : void {
		parent::tear_down_after_class();

		static::$plugin            = null;
		static::$remove_transients = null;
		static::$controller        = null;
		static::$wp_rest_request   = null;
	}

	public function setUp() : void {
		parent::setUp();

		static::$remove_transients->remove();
	}

	public function test_is_get_items_returns_expected_response(): void {
		$request  = static::$wp_rest_request;
		$response = static::$controller->get_items( $request );
		$data     = $response->get_data();

		$this->assertTrue( $this->is_expected_response( $data ) );

		// Check if the response is cached.
		$url             = Strategy11_Controller::API_ENDPOINT;
		$cache_key       = 'sherv_challenge_strategy11_data_' . md5( $url );
		$transient       = json_decode( get_transient( $cache_key ), true );
		$cached_response = static::$controller->prepare_data_for_response( $transient, $request );
		$cached_response = rest_ensure_response( $cached_response );
		$cached_data     = $cached_response->get_data();

		$this->assertTrue( $this->is_expected_response( $cached_data ) );
	}

	public function test_is_chache_expiration_time_correct(): void {
		static::$controller->get_items( static::$wp_rest_request );

		// Check if the expiration time is correct.
		$url             = Strategy11_Controller::API_ENDPOINT;
		$cache_key       = 'sherv_challenge_strategy11_data_' . md5( $url );
		$expiration_time = get_option( '_transient_timeout_' . $cache_key );
		$expiration_time = $expiration_time - time();

		$this->assertEquals( Strategy11_Controller::CACHE_EXPIRATION_TIME, $expiration_time );
	}

	public function is_expected_response( $data ) : bool {
		$is_expected_response = true;

		$expected_header = [
			'ID',
			'First Name',
			'Last Name',
			'Email',
			'Date',
		];

		$expected_body_key = [
			'id',
			'fname',
			'lname',
			'email',
			'date',
		];

		if ( $expected_header === $data['header'] ) {
			foreach ( $data['body'] as $item ) {
				if ( array_keys( $item ) !== $expected_body_key ) {
					$is_expected_response = false;
					break;
				}
			}
		}

		return $is_expected_response;
	}
}
