<?php
/**
 * Dashboard class.
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

use Strategy11\Sherv_Challenge\Traits\Screen;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Registerable};
use Strategy11\Sherv_Challenge\Assets;

/**
 * Dashboard class.
 *
 * @since 1.0.0
 */
class Dashboard implements Component, Registerable {
	use Screen;

	/**
	 * The slug of the sherv challenge dashboard.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'sherv-challenge-dashboard';

	/**
	 * Script handle.
	 *
	 * @var string
	 */
	const SCRIPT_HANDLE = 'sherv-challenge-admin';

	/**
	 * Admin page hook suffixes.
	 *
	 * @var array<string,string|bool> List of the admin page's hook_suffix values.
	 */
	private $hook_suffix = [];

	/**
	 * Assets instance.
	 *
	 * @var Assets Assets instance.
	 */
	private $assets;

	/**
	 * Dashboard constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Assets $assets Assets instance.
	 */
	public function __construct( Assets $assets ) {
		$this->assets = $assets;
	}

	/**
	 * Initializes the dashboard logic.
	 *
	 * @since 1.0.0
	 */
	public function register() : void {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Returns the admin page's hook suffix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The current admin page key.
	 * @return string|false|null The dashboard page's hook_suffix, or false if the user does not have the capability required.
	 */
	public function get_hook_suffix( $key ) {
		return $this->hook_suffix[ $key ] ?? false;
	}

	/**
	 * Registers the dashboard admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function add_menu_page() {
		add_menu_page(
			__( 'Sherv Challenge', 'sherv-challenge' ),
			__( 'Sherv Challenge', 'sherv-challenge' ),
			'manage_sherv_challenge',
			self::PAGE_SLUG,
			[ $this, 'render' ],
			'dashicons-editor-table',
			2
		);

		$this->hook_suffix[ self::PAGE_SLUG ] = add_submenu_page(
			self::PAGE_SLUG,
			__( 'Dashboard', 'sherv-challenge' ),
			__( 'Dashboard', 'sherv-challenge' ),
			'manage_sherv_challenge',
			self::PAGE_SLUG,
			[ $this, 'render' ],
			1
		);
	}

	/**
	 * Renders the dashboard page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		require_once SHERV_CHALLENGE_PATH . 'includes/templates/admin/dashboard.php';
	}

	/**
	 * Enqueues dashboard scripts and styles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_assets( string $hook_suffix ) {
		if ( $this->get_hook_suffix( self::PAGE_SLUG ) !== $hook_suffix ) {
			return;
		}

		$this->assets->enqueue_script_asset( self::SCRIPT_HANDLE );
		$this->assets->enqueue_style_asset( self::SCRIPT_HANDLE );

		wp_localize_script(
			self::SCRIPT_HANDLE,
			'shervChallengeDashboardSettings',
			$this->get_script_settings()
		);
	}

	/**
	 * Get dashboard settings as an array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_script_settings(): array {
		$settings = [
			'dashboardUrl' => admin_url( 'admin.php?page=' . self::PAGE_SLUG ),
			'version'      => SHERV_CHALLENGE_VERSION,
			'assetsUrl'    => $this->assets->get_base_url( 'assets' ),
			'isRTL'        => is_rtl(),
			'userId'       => get_current_user_id(),
			'api'          => [
				'ajaxURL'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'shev-challenge-dashboard-nonce' ),
				'strategy11Data' => 'sherv-challenge/v1/strategy11-data',
			],
		];

		/**
		 * Filters settings passed to the sherv challenge dashboard.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings Array of settings passed to sherv challenge dashboard.
		 */
		return apply_filters( 'sherv_challenge_dashboard_script_settings', $settings );
	}
}
