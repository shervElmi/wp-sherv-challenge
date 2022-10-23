<?php
/**
 * Str Trait.
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

namespace Strategy11\Sherv_Challenge\Traits;

/**
 * Str Trait.
 *
 * @since 1.0.0
 */
trait Str {

	/**
	 * Determine if a string contains a given substring.
	 *
	 * @since 1.0.0
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle The substring to search for in the haystack.
	 * @return bool True if the haystack contains the needle, false otherwise.
	 */
	protected function str_contains( string $haystack, string $needle ) : bool {
		return false !== strpos( $haystack, $needle );
	}
}
