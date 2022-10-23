<?php
/**
 * Hooked interface.
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

namespace Strategy11\Sherv_Challenge\Interfaces\Component;

/**
 * A component that is hooked to a specific point in the execution.
 *
 * A class marked as being Hooked can return the action at which it requires
 * to be registered.
 *
 * This can be used to only register a given object after certain contextual
 * requirements are met, like registering a frontend rendering component only
 * after the loop has been set up.
 *
 * @since 1.0.0
 */
interface Hooked extends Registerable {

	/**
	 * Get the action to use for registering the component.
	 *
	 * @since 1.0.0
	 *
	 * @return string Registration action to use.
	 */
	public static function get_registration_action(): string;

	/**
	 * Get the action priority to use for registering the component.
	 *
	 * @since 1.0.0
	 *
	 * @return int Registration action priority to use.
	 */
	public static function get_registration_action_priority(): int;
}
