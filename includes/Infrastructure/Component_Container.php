<?php
/**
 * Component_Container final class.
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

namespace Strategy11\Sherv_Challenge\Infrastructure;

use Strategy11\Sherv_Challenge\interfaces\Component\{
	Component_Container as Component_Container_Interface,
	Component
};
use Strategy11\Sherv_Challenge\Infrastructure\Component_Container\Lazily_Instantiated_Component;
use Strategy11\Sherv_Challenge\Exception\Invalid_Component;
use ArrayObject;

/**
 * Component_Container final class.
 *
 * A simplified implementation of a component container.
 *
 * We extend ArrayObject so we have default implementations for iterators and
 * array access.
 *
 * @since 1.0.0
 */
final class Component_Container extends ArrayObject implements Component_Container_Interface {

	/**
	 * Find a component of the container by its identifier and return it.
	 *
	 * @since 1.0.0
	 *
	 * @param string             $id Identifier of the component to look for.
	 * @throws Invalid_Component  If the component could not be found.
	 * @return Component          Component that was requested.
	 */
	public function get( string $id ): Component {
		if ( ! $this->has( $id ) ) {
			throw Invalid_Component::from_component_id( $id );
		}

		$component = $this->offsetGet( $id );

		// Instantiate actual components if they were stored lazily.
		if ( $component instanceof Lazily_Instantiated_Component ) {
			$component = $component->instantiate();
			$this->put( $id, $component );
		}

		return $component;
	}

	/**
	 * Check whether the container can return a component for the given
	 * identifier.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier of the component to look for.
	 * @return bool
	 */
	public function has( string $id ): bool {
		return $this->offsetExists( $id );
	}

	/**
	 * Put a component into the container for later retrieval.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $id        Identifier of the component to put into the container.
	 * @param Component $component Component to put into the container.
	 */
	public function put( string $id, Component $component ) {
		$this->offsetSet( $id, $component );
	}
}
