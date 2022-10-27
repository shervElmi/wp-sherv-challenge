<?php
/**
 * Admin class.
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

use Strategy11\Sherv_Challenge\Traits\{Screen, Str};
use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Hookable};

/**
 * Admin class.
 *
 * @since 1.0.0
 */
class Admin implements Component, Hookable {
	use Screen, Str;

	/**
	 * Get the action to use for registering the component.
	 *
	 * @since 1.0.0
	 *
	 * @return string Registration action to use.
	 */
	public static function get_registration_action(): string {
		return 'admin_init';
	}

	/**
	 * Get the action priority to use for registering the component.
	 *
	 * @since 1.0.0
	 *
	 * @return int Registration action priority to use.
	 */
	public static function get_registration_action_priority(): int {
		return 10;
	}

	/**
	 * Register the component.
	 *
	 * @since 1.0.0
	 */
	public function register() : void {
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ], 99 );
	}

	/**
	 * Add admin body class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classes Admin body classes.
	 * @return string
	 */
	public function admin_body_class( string $classes ) : string {
		$screen = $this->get_current_screen();

		if ( $screen && $this->str_contains( $screen->base, Dashboard::PAGE_SLUG ) ) {
			$classes .= ' sherv-challenge-dashboard';
		}

		return $classes;
	}
}
