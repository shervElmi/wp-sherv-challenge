<?php
/**
 * Clear_Cache_Command class.
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

namespace Strategy11\Sherv_Challenge\CLI;

use WP_CLI;
use Strategy11\Sherv_Challenge\Remove_Transients;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, CLI_Command};

/**
 * Clear_Cache_Command class.
 *
 * @since 2.1.0
 */
final class Clear_Cache_Command implements Component, CLI_Command {

	/**
	 * Remove_Transients instance.
	 *
	 * @var Remove_Transients
	 */
	private $remove_transients;

	/**
	 * Get the name under which to register the CLI command.
	 *
	 * @return string The name under which to register the CLI command.
	 */
	public static function get_command_name() {
		return 'sherv-challenge';
	}

	/**
	 * Clear_Cache_Command constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Remove_Transients $remove_transients Remove_Transients instance.
	 */
	public function __construct( Remove_Transients $remove_transients ) {
		$this->remove_transients = $remove_transients;
	}

	/**
	 * Clear the cache.
	 *
	 * @subcommand clear-cache
	 */
	public function clear_cache() {
		WP_CLI::confirm( __( 'This operation will clear the data chached data, are you sure?', 'sherv-challenge' ) );

		$this->remove_transients->remove();
		WP_CLI::success( 'Cache cleared.' );
	}
}
