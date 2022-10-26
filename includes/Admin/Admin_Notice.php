<?php
/**
 * Admin Notice class.
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

/**
 * Class Admin_Notice.
 *
 * @since 1.0.0
 */
class Admin_Notice {

	/**
	 * WordPress action to trigger the component registration on.
	 *
	 * @var string
	 */
	const REGISTRATION_ACTION = 'admin_notices';

	/**
	 * WP_Error object.
	 *
	 * @var WP_Error
	 */
	private $wp_error;

	/**
	 * Admin Notice constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $wp_error WP_Error object.
	 */
	public function __construct( WP_Error $wp_error ) {
		$this->wp_error = $wp_error;
	}

	/**
	 * Register the plugin with the WordPress system.
	 *
	 * @since 1.0.0
	 */
	public function register() : void {
		add_action( static::REGISTRATION_ACTION, array( $this, 'print_notice' ) );
	}

	/**
	 * Print an admin notice.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function print_notice() {
		$wp_error = $this->wp_error;

		?>
		<div class="notice notice-error">
			<p><strong><?php esc_html_e( 'Sherv Challenge plugin could not be initialized.', 'sherv-challenge' ); ?></strong></p>
			<ul>
				<?php
				foreach ( array_keys( $wp_error->errors ) as $code ) {
					$message = $wp_error->get_error_message( $code );
					printf( '<li>%s</li>', wp_kses( $message, 'code' ) );
				}
				?>
			</ul>
		</div>
		<?php
	}
}
