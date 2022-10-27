<?php
/**
 * Admin_Ajax class.
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

namespace Strategy11\Sherv_Challenge\Admin;

use Strategy11\Sherv_Challenge\Remove_Transients;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Registerable};

/**
 * Admin_Ajax class.
 *
 * @since 1.0.0
 */
class Admin_Ajax implements Component, Registerable {

	/**
	 * Remove_Transients instance.
	 *
	 * @var Remove_Transients
	 */
	private $remove_transients;

	/**
	 * Adamin_Ajax constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Assets $remove_transients Remove_Transients instance.
	 */
	public function __construct( Remove_Transients $remove_transients ) {
		$this->remove_transients = $remove_transients;
	}

	/**
	 * Register the component.
	 *
	 * @since 1.0.0
	 */
	public function register() : void {
		add_action( 'wp_ajax_sherv_challenge_remove_cache', [ $this, 'remove_cache' ] );
	}

	/**
	 * Remove the cache.
	 *
	 * @since 1.0.0
	 */
	public function remove_cache() : void {
		check_ajax_referer( 'shev-challenge-dashboard-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_sherv_challenge' ) ) {
			wp_send_json_error( __( 'You do not have permission to do this.', 'sherv-challenge' ) );
		}

		$this->remove_transients->remove();

		wp_send_json_success( __( 'Cache removed.', 'sherv-challenge' ) );
	}
}
