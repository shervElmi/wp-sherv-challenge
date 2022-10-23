<?php
/**
 * Plugin_Factory final class.
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

namespace Strategy11\Sherv_Challenge;

/**
 * Plugin_Factory final class.
 *
 * The plugin factory is responsible for instantiating the plugin and returning
 * that instance.
 *
 * It can decide whether to return a shared or a fresh instance as needed.
 *
 * To read more about why this is preferable to a Singleton,
 *
 * @see https://www.alainschlesser.com/singletons-shared-instances/
 *
 * @since 1.0.0
 */
final class Plugin_Factory {

	/**
	 * Create and return an instance of the plugin.
	 *
	 * This always returns a shared instance. This way, outside code can always
	 * get access to the object instance of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin Plugin instance.
	 */
	public static function create(): Plugin {
		static $plugin = null;

		if ( null === $plugin ) {
			$plugin = new Plugin();
		}

		return $plugin;
	}
}
