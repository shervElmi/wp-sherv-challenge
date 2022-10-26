<?php
/**
 * Sherv Challenge dashboard.
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

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

?>
<div class="sherv-challenge">
	<h1 class="screen-reader-text hide-if-no-js"><?php esc_html_e( 'Sherv Challenge', 'sherv-challenge' ); ?></h1>
	<div id="sherv-challenge-dashboard" class="sherv-challenge-dashboard hide-if-no-js"></div>

	<?php // JavaScript is disabled. ?>
	<div class="wrap sherv-challenge-no-js hide-if-js">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Sherv Challenge Plugin', 'sherv-challenge' ); ?></h1>
		<div class="notice notice-error notice-alt">
			<p><?php esc_html_e( 'Sherv Challenge requires JavaScript. Please enable JavaScript in your browser settings.', 'sherv-challenge' ); ?></p>
		</div>
	</div>
</div>
