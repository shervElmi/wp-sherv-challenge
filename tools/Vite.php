<?php
/**
 * Vite class.
 *
 * Integrates Vite with WordPress.
 *
 * @package   Strategy11/Sherv_Challenge
 * @copyright 2022 Strategy11.
 * @license   GNU General Public License 3.0
 * @link      https://strategy11.com/
 */

/**
 * Copyright (C) 2022 Strategy11.
 *
 * Licensed under GNU GPL, Version 3.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.gnu.org/licenses/gpl-3.0.en.html
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

namespace Strategy11\Sherv_Challenge\Tools;

use Strategy11\Sherv_Challenge\Infrastructure\{Conditional, Service, Registerable, Delayed};

/**
 * Class Vite.
 *
 * @since 1.0.0
 */
final class Vite implements Service, Conditional, Registerable, Delayed {

	// Specify the output directory; can be customized in "vite.config.json".
	const OUT_DIR = 'dist';

	// Deafult server address, and port; can be customized in "vite.config.json".
	const SERVER = 'http://localhost:3000';

	// Deafult entry point; can be customized in "vite.config.json".
	const ENTRY_POINT = '/assets/js/index.js';

	/**
	 * Check whether the conditional object is currently needed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the conditional object is needed.
	 */
	public static function is_needed(): bool {
		return SHERV_CHALLENGE_DEV_MODE;
	}

	/**
	 * Get the action to use for registering the service.
	 *
	 * @since 1.0.0
	 *
	 * @return string  Registration action to use.
	 */
	public static function get_registration_action(): string {
		return 'wp_footer';
	}

	/**
	 * Get the action priority to use for registering the service.
	 *
	 * @since 1.0.0
	 *
	 * @return int Registration action priority to use.
	 */
	public static function get_registration_action_priority(): int {
		return 99999;
	}

	/**
	 * Register the service.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		// Insert hmr into head for live reload.
		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		echo '<script type="module" crossorigin src="' . esc_attr( static::SERVER . static::ENTRY_POINT ) . '"></script>';
	}
}
