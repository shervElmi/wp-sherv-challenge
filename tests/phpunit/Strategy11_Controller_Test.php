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
use Strategy11\Sherv_Challenge\REST_API\Strategy11_Controller;
use Strategy11\Sherv_Challenge\Plugin;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Injector, Component_Container};
use Spy_REST_Server;

/**
 * Strategy11_Controller_Test class.
 *
 * @coversDefaultClass Strategy11_Controller
 */
class Strategy11_Controller_Test extends TestCase {

	/**
	 * Plugin instance to test with.
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Component container instance to test with.
	 *
	 * @var Component_Container
	 */
	protected $container;

	/**
	 * Injector instance to test with.
	 *
	 * @var Injector
	 */
	protected $injector;

	/**
	 * Test instance.
	 *
	 * @var Strategy11_Controller
	 */
	private $controller;

	/**
	 * Set up the component architecture before each test run.
	 */
	public function set_up(): void {
		parent::set_up();

		/**
		 * WP REST server instance.
		 * WP REST Server is not available in the test environment.
		 *
		 * @var \WP_REST_Server $wp_rest_server
		 **/
		global $wp_rest_server;
		$wp_rest_server = new Spy_REST_Server();

		do_action( 'rest_api_init', $wp_rest_server );

		// We're intentionally avoiding the PluginFactory here as it uses a
		// static instance, because its whole point is to allow reuse across consumers.
		$this->plugin = new Plugin();
		$this->plugin->register();

		$this->container = $this->plugin->get_container();
		$this->injector  = $this->container->get( 'injector' );

		add_filter( 'pre_http_request', [ $this, 'mock_http_request' ], 10, 3 );
		$this->request_count = 0;

		$this->controller = $this->injector->make( Strategy11_Controller::class );
	}

	/**
	 * Clean up again after each test run.
	 */
	public function tear_down(): void {
		parent::tear_down();

		/**
		 * WP REST server instance.
		 * WP REST Server is not available in the test environment.
		 *
		 * @var \WP_REST_Server $wp_rest_server
		 **/
		global $wp_rest_server;
		$wp_rest_server = null;
	}
}
