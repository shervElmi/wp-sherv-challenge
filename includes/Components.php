<?php
/**
 * Components class.
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

use Strategy11\Sherv_Challenge\Interfaces\Component\{
	Component,
	Component_Container,
	Injector,
};

/**
 * Components class.
 *
 * Convenience class to get easy access to the component container.
 *
 * Using this should always be the last resort.
 * Always prefer to use constructor injection instead.
 *
 * @since 1.0.0
 */
final class Components {

	/**
	 * Plugin object instance.
	 *
	 * @var Plugin
	 */
	private static $plugin;

	/**
	 * Component container object instance.
	 *
	 * @var Component_Container<Component>
	 */
	private static $container;

	/**
	 * Dependency injector object instance.
	 *
	 * @var Injector|Component
	 */
	private static $injector;

	/**
	 * Get a particular component out of the component container.
	 *
	 * @since 1.0.0
	 *
	 * @param string $component Component ID to retrieve.
	 */
	public static function get( string $component ): Component {
		return self::get_container()->get( $component );
	}

	/**
	 * Check if a particular component has been registered in the component container.
	 *
	 * @since 1.0.0
	 *
	 * @param string $component Component ID to retrieve.
	 */
	public static function has( string $component ): bool {
		return self::get_container()->has( $component );
	}

	/**
	 * Get an instance of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin Plugin object instance.
	 */
	public static function get_plugin(): Plugin {
		if ( null === self::$plugin ) {
			self::$plugin = Plugin_Factory::create();
		}

		return self::$plugin;
	}

	/**
	 * Get an instance of the component container.
	 *
	 * @since 1.0.0
	 *
	 * @return Component_Container<Component> Component container object instance.
	 */
	public static function get_container(): Component_Container {
		if ( null === self::$container ) {
			self::$container = self::get_plugin()->get_container();
		}

		return self::$container;
	}

	/**
	 * Get an instance of the dependency injector.
	 *
	 * @since 1.0.0
	 *
	 * @return Injector|Component Dependency injector object instance.
	 */
	public static function get_injector() {
		if ( null === self::$injector ) {
			self::$injector = self::get_container()->get( 'injector' );
		}

		return self::$injector;
	}
}
