<?php
/**
 * Challenge_Shortcode class.
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

namespace Strategy11\Sherv_Challenge\Shortcode;

use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Registerable};
use Strategy11\Sherv_Challenge\Assets;

/**
 * Challenge_Shortcode class.
 *
 * @since 1.0.0
 */
class Challenge_Shortcode implements Component, Registerable {

	/**
	 * Shortcode name.
	 *
	 * @var string
	 */
	public const SHORTCODE_NAME = 'sherv_challenge';

	/**
	 * Script handle.
	 *
	 * @var string
	 */
	const SCRIPT_HANDLE = 'sherv-challenge-frontend';

	/**
	 * Assets instance.
	 *
	 * @var Assets Assets instance.
	 */
	private $assets;

	/**
	 * Challenge Shortocde constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Assets $assets Assets instance.
	 */
	public function __construct( Assets $assets ) {
		$this->assets = $assets;
	}

	/**
	 * Initializes the Challenge shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register() : void {
		add_shortcode( self::SHORTCODE_NAME, [ $this, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Callback for the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		ob_start();
		require_once SHERV_CHALLENGE_PATH . 'includes/templates/frontend/challenge-shortcode.php';
		$content = (string) ob_get_clean();

		return $content;
	}

	/**
	 * Enqueues shortcode scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		$this->assets->enqueue_script_asset( self::SCRIPT_HANDLE );
		$this->assets->enqueue_style_asset( self::SCRIPT_HANDLE );

		wp_localize_script(
			self::SCRIPT_HANDLE,
			'shervChallengeShortocdeSettings',
			[
				'api' => [
					'strategy11Data' => 'sherv-challenge/v1/strategy11-data',
				],
			]
		);
	}
}
