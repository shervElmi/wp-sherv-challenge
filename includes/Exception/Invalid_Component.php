<?php
/**
 * Invalid_Component final class.
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

use InvalidArgumentException;

/**
 * Invalid_Component final class.
 *
 * @since 1.0.0
 */
final class Invalid_Component extends InvalidArgumentException implements Strategy11_Exception {

	/**
	 * Create a new instance of the exception for a component class name that is
	 * not recognized.
	 *
	 * @since 1.0.0
	 *
	 * @param string|object $component Class name of the component that was not recognized.
	 * @return static
	 */
	public static function from_component( $component ) {
		$message = sprintf(
			'The component "%s" is not recognized and cannot be registered.',
			is_object( $component ) ? get_class( $component ) : (string) $component
		);

		return new static( $message );
	}

	/**
	 * Create a new instance of the exception for a component identifier that is
	 * not recognized.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $component_id Identifier of the component that is not being recognized.
	 * @return static
	 */
	public static function from_component_id( string $component_id ) {
		$message = sprintf(
			'The component ID "%s" is not recognized and cannot be retrieved.',
			$component_id
		);

		return new static( $message );
	}
}
