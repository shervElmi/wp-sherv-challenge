<?php
/**
 * Google_Fonts class.
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

namespace Strategy11\Sherv_Challenge\Admin;

use WP_Styles;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Conditional, Hooked, Registerable};

/**
 * Google_Fonts Class.
 *
 * @since 1.0.0
 */
class Google_Fonts implements Component, Conditional, Registerable {

	/**
	 * Script handle.
	 *
	 * @var string
	 */
	const SCRIPT_HANDLE = 'sherv-challenge-fonts';

	/**
	 * Check whether the conditional object is currently needed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the conditional object is needed.
	 */
	public static function is_needed(): bool {
		return is_admin() && ! wp_doing_ajax();
	}

	/**
	 * Runs on instantiation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'wp_default_styles', [ $this, 'register_style' ] );
	}

	/**
	 * Registers the google font style.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Styles $wp_styles WP_Styles instance.
	 *
	 * @return void
	 */
	public function register_style( WP_Styles $wp_styles ) {
		// so we need to avoid specifying a version at all.
		$wp_styles->add(
			self::SCRIPT_HANDLE,
			'https://fonts.googleapis.com/css?family=Google+Sans|Google+Sans:b|Google+Sans:500&display=swap',
			[],
			SHERV_CHALLENGE_VERSION
		);
	}
}
