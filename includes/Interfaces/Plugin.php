<?php
/**
 * Plugin interface.
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

namespace Strategy11\Sherv_Challenge\Interfaces;

use Strategy11\Sherv_Challenge\Interfaces\Component\{
	Component_Container,
	Plugin_Activation_Aware,
	Plugin_Deactivation_Aware,
	Registerable
};

/**
 * A plugin is basically nothing more than a convention on how manage the
 * lifecycle of a modular piece of code, so that you can:
 *  1. activate it,
 *  2. register it with the framework, and
 *  3. deactivate it again.
 *
 * This is what this interface represents, by assembling the separate,
 * segregated interfaces for each of these lifecycle actions.
 *
 * Additionally, we provide a means to get access to the plugin's container that
 * collects all the components it is made up of. This allows direct access to the
 * components to outside code if needed.
 *
 * @since 1.0.0
 */
interface Plugin extends Plugin_Activation_Aware, Plugin_Deactivation_Aware, Registerable {

	/**
	 * Get the component container that contains the components that make up the
	 * plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Component_Container Component container of the plugin.
	 */
	public function get_container(): Component_Container;
}
