<?php
/**
 * Invalid_Path final class.
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
 * Invalid_Path final class.
 *
 * @since 1.0.0
 */
final class Invalid_Path extends InvalidArgumentException implements Strategy11_Exception {

	/**
	 * Create a new instance of the exception for a file that is not accessible
	 * or not readable.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $path Path of the file that is not accessible or not readable.
	 * @return static
	 */
	public static function from_path( $path ) {
		$message = sprintf(
			'The view path "%s" is not accessible or readable.',
			$path
		);

		return new static( $message );
	}
}
