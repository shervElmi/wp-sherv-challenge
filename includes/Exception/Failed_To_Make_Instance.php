<?php
/**
 * Failed_To_Make_Instance final class.
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

namespace Strategy11\Sherv_Challenge\Exception;

use RuntimeException;

/**
 * Failed_To_Make_Instance final class.
 *
 * @since 1.0.0
 */
final class Failed_To_Make_Instance extends RuntimeException implements Strategy11_Exception {

	// These constants are public so you can use them to find out what exactly
	// happened when you catch a "Failed_To_Make_Instance" exception.
	public const CIRCULAR_REFERENCE             = 100;
	public const UNRESOLVED_INTERFACE           = 200;
	public const UNREFLECTABLE_CLASS            = 300;
	public const UNRESOLVED_ARGUMENT            = 400;
	public const UNINSTANTIATED_SHARED_INSTANCE = 500;

	/**
	 * Create a new instance of the exception for an interface or class that
	 * created a circular reference.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Class name that generated the circular reference.
	 * @return static
	 */
	public static function for_circular_reference( string $class ) {
		$message = sprintf(
			'Circular reference detected while trying to resolve the interface or class "%s".',
			$class
		);

		return new static( $message, static::CIRCULAR_REFERENCE );
	}

	/**
	 * Create a new instance of the exception for an interface that could not
	 * be resolved to an instantiable class.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $interface Interface that was left unresolved.
	 * @return static
	 */
	public static function for_unresolved_interface( string $interface ) {
		$message = sprintf(
			'Could not resolve the interface "%s" to an instantiable class, probably forgot to bind an implementation.',
			$interface
		);

		return new static( $message, static::UNRESOLVED_INTERFACE );
	}

	/**
	 * Create a new instance of the exception for an interface or class that
	 * could not be reflected upon.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $class Class that could not be reflected upon.
	 * @return static
	 */
	public static function for_unreflectable_class( string $class ) {
		$message = sprintf(
			'Could not reflect on the interface or class "%s", probably not a valid FQCN.',
			$class
		);

		return new static( $message, static::UNREFLECTABLE_CLASS );
	}

	/**
	 * Create a new instance of the exception for an argument that could not be
	 * resolved.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $argument_name Name of the argument that could not be resolved.
	 * @param string  $class         Class that had the argument in its constructor.
	 * @return static
	 */
	public static function for_unresolved_argument( string $argument_name, string $class ) {
		$message = sprintf(
			'Could not resolve the argument "%s" while trying to instantiate the class "%s".',
			$argument_name,
			$class
		);

		return new static( $message, static::UNRESOLVED_ARGUMENT );
	}

	/**
	 * Create a new instance of the exception for a class that was meant to be
	 * reused but was not yet instantiated.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $class Class that was not yet instantiated.
	 * @return static
	 */
	public static function for_uninstantiated_shared_instance( string $class ) {
		$message = sprintf(
			'Could not retrieve the shared instance for "%s" as it was not instantiated yet.',
			$class
		);

		return new static( $message, static::UNINSTANTIATED_SHARED_INSTANCE );
	}
}
