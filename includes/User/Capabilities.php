<?php
/**
 * Capabilities class.
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

namespace Strategy11\Sherv_Challenge\User;

use WP_Role;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Component, Plugin_Activation_Aware};

/**
 * Capabilities class.
 *
 * @since 1.0.0
 */
class Capabilities implements Component, Plugin_Activation_Aware {

	/**
	 * Sherv challenge manage cap.
	 *
	 * @var string
	 */
	const MANAGE_SHERV_CHALLENGE_CAP = 'manage_sherv_challenge';

	/**
	 * Act on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function on_plugin_activation() : void {
		$this->add_caps_to_roles();
	}

	/**
	 * Adds sherv challenge capabilities to default user roles.
	 *
	 * This gives WordPress site owners more granular control over sherv challenge management,
	 * as they can customize this to their liking.
	 *
	 * @since 1.0.0
	 */
	public function add_caps_to_roles() : void {
		$all_capabilities = $this->get_all_capabilities();
		$administrator    = get_role( 'administrator' );
		$editor           = get_role( 'editor' );

		if ( $administrator instanceof WP_Role ) {
			foreach ( $all_capabilities as $cap ) {
				$administrator->add_cap( $cap );
			}
		}

		if ( $editor instanceof WP_Role ) {
			foreach ( $all_capabilities as $cap ) {
				$editor->add_cap( $cap );
			}
		}

		/**
		 * Fires when adding the custom capabilities to existing roles.
		 * Can be used to add the capabilities to other, custom roles.
		 *
		 * @since 1.0.0
		 *
		 * @param array $all_capabilities List of all post type capabilities, for reference.
		 */
		do_action( 'sherv_challenge_add_capabilities', $all_capabilities );
	}

	/**
	 * Removes sherv challenge capabilities from all user roles.
	 *
	 * @since 1.0.0
	 */
	public function remove_caps_from_roles() : void {
		$all_capabilities = $this->get_all_capabilities();
		$all_roles        = wp_roles();
		$roles            = array_values( (array) $all_roles->role_objects );

		foreach ( $roles as $role ) {
			if ( $role instanceof WP_Role ) {
				foreach ( $all_capabilities as $cap ) {
					$role->remove_cap( $cap );
				}
			}
		}

		/**
		 * Fires when removing the custom capabilities from existing roles.
		 *
		 * Can be used to remove the capabilities from other, custom roles.
		 *
		 * @since 1.0.0
		 *
		 * @param array $all_capabilities List of all post type capabilities, for reference.
		 */
		do_action( 'sherv_challenge_remove_capabilities', $all_capabilities );
	}

	/**
	 * Get a array of capability.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of plugin capabilities
	 */
	protected function get_all_capabilities(): array {
		return [ self::MANAGE_SHERV_CHALLENGE_CAP ];
	}
}
