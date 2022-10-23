<?php
/**
 * Lazily_Instantiated_Component final class.
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

namespace Strategy11\Sherv_Challenge\Infrastructure\Component_Container;

use Strategy11\Sherv_Challenge\Interfaces\Component\Component;
use Strategy11\Sherv_Challenge\Exception\Invalid_Component;

/**
 * Lazily_Instantiated_Component final class.
 *
 * A component that only gets properly instantiated when it is actually being
 * retrieved from the container.
 *
 * @since 1.0.0
 */
final class Lazily_Instantiated_Component implements Component {

	/**
	 * Instantiation of Class.
	 *
	 * @var callable
	 */
	private $instantiation;

	/**
	 * Instantiate a Lazily_Instantiated_Component object.
	 *
	 * @since 1.0.0
	 *
	 * @param callable $instantiation Instantiation callable to use.
	 */
	public function __construct( callable $instantiation ) {
		$this->instantiation = $instantiation;
	}

	/**
	 * Do the actual component instantiation and return the real component.
	 *
	 * @since 1.0.0
	 *
	 * @throws Invalid_Component If the component could not be properly instantiated.
	 * @return Component         Properly instantiated component.
	 */
	public function instantiate(): Component {
		$instantiation = $this->instantiation;
		$component     = $instantiation();

		if ( ! $component instanceof Component ) {
			throw Invalid_Component::from_component( $component );
		}

		return $component;
	}
}
