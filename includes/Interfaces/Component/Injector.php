<?php
/**
 * Injector interface.
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
 * The dependency injector should be the only piece of code doing actual
 * instantiations, with the following exceptions:
 *  - Factories can instantiate directly.
 *  - Value objects should be instantiated directly where they are being used.
 *
 * The dependency injector should be the only one to decide what
 * objects to "share" (always handing out the same instance) or not to share
 * (always returning a fresh new instance on each subsequent call). This
 * effectively gets rid of the dreaded Singletons.
 *
 * @since 1.0.0
 */
interface Injector extends Component {

	/**
	 * Make an object instance out of an class.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $class Class to make an object instance out of.
	 * @return object  Instantiated object.
	 */
	public function make( string $class );

	/**
	 * Always reuse and share the same instance for the provided class.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $class Class to reuse.
	 * @return Injector
	 */
	public function share( string $class ): Injector;
}
