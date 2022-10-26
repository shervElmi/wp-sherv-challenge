<?php
/**
 * Remove Transients class.
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

namespace Strategy11\Sherv_Challenge;

use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Plugin_Deactivation_Aware};

/**
 * Remove Transients class.
 */
class Remove_Transients implements Component, Plugin_Deactivation_Aware {
	protected const PREFIX = 'sherv_challenge\_%';

	/**
	 * Run the component.
	 *
	 * @since 1.0.0
	 */
	public function run() : void {
		$this->on_plugin_deactivation();
	}

	/**
	 * Delete network and site transients.
	 *
	 * @since 1.0.0
	 */
	public function on_plugin_deactivation(): void {
		if ( wp_using_ext_object_cache() ) {
			return;
		}

		if ( ! is_multisite() ) {
			$this->delete_transients();
			return;
		}

		$this->delete_network_transients();
		$site_ids = get_sites(
			[
				'fields'                 => 'ids',
				'number'                 => 0,
				'update_site_cache'      => false,
				'update_site_meta_cache' => false,
			]
		);

		foreach ( $site_ids as $site_id ) {
			// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.switch_to_blog_switch_to_blog
			switch_to_blog( $site_id );

			$this->delete_transients();
		}

		restore_current_blog();
	}

	/**
	 * Delete transients.
	 *
	 * @since 1.0.0
	 */
	protected function delete_transients(): void {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$transients = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_' . self::PREFIX,
				'_transient_timeout_' . self::PREFIX
			)
		);

		if ( ! empty( $transients ) ) {
			array_map( 'delete_option', (array) $transients );
		}
	}

	/**
	 * Delete transients on multisite.
	 *
	 * @since 1.0.0
	 */
	protected function delete_network_transients(): void {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$transients = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT meta_key FROM $wpdb->sitemeta WHERE meta_key LIKE %s OR meta_key LIKE %s",
				'_site_transient_' . self::PREFIX,
				'_site_transient_timeout_' . self::PREFIX
			)
		);

		if ( ! empty( $transients ) ) {
			array_map( 'delete_site_option', (array) $transients );
		}
	}
}
