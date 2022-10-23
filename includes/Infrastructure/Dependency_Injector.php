<?php
/**
 * Dependency_Injector final class.
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

use Throwable;
use Strategy11\Sherv_Challenge\Interfaces\Component\{Injector, Instantiator};
use Strategy11\Sherv_Challenge\Exception\Failed_To_Make_Instance;
use ReflectionParameter;
use ReflectionNamedType;
use ReflectionClass;

/**
 * A simplified implementation of a dependency injector.
 *
 * @since 1.0.0
 */
final class Dependency_Injector implements Injector {

	/**
	 * List of injections.
	 *
	 * @var array<string>
	 */
	private $injection_chain = [];

	/**
	 * List of shared instances.
	 *
	 * @var array<object|null>
	 */
	private $shared_instances = [];

	/**
	 * The instantiator to use for creating instances.
	 *
	 * @var Instantiator
	 */
	private $instantiator;

	/**
	 * Instantiate a Dependency_Injector object.
	 *
	 * @since 1.0.0
	 *
	 * @param Instantiator|null $instantiator Optional. Instantiator to use.
	 */
	public function __construct( Instantiator $instantiator = null ) {
		$this->instantiator = $instantiator ?? $this->get_fallback_instantiator();
	}

	/**
	 * Make an object instance out of an class.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $class Class to make an object instance out of.
	 * @return object  Instantiated object.
	 */
	public function make( string $class ) {
		$this->add_to_chain( $class );

		if ( $this->has_shared_instance( $class ) ) {
			return $this->get_shared_instance( $class );
		}

		$reflection = $this->get_class_reflection( $class );
		$this->ensure_is_instantiable( $reflection );

		$dependencies = $this->get_dependencies_for( $reflection );

		$object = $this->instantiator->instantiate( $class, $dependencies );

		if ( array_key_exists( $class, $this->shared_instances ) ) {
			$this->shared_instances[ $class ] = $object;
		}

		return $object;
	}

	/**
	 * Always reuse and share the same instance for the provided interface or
	 * class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Interface or class to reuse.
	 * @return Injector
	 */
	public function share( string $class ): Injector {
		$this->shared_instances[ $class ] = null;

		return $this;
	}

	/**
	 * Make an object instance out of an class.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $class Class to make an object instance out of.
	 * @return object  Instantiated object.
	 */
	private function make_dependency( string $class ) {
		return $this->make( $class );
	}

	/**
	 * Add class to injection chain.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Class to resolve.
	 */
	private function add_to_chain( string $class ) {
		$this->injection_chain[] = $class;
	}

	/**
	 * Get the array of constructor dependencies for a given reflected class.
	 *
	 * @since 1.0.0
	 *
	 * @param ReflectionClass $reflection Reflected class to get the dependencies for.
	 * @return array           Array of dependencies that represent the arguments for the class constructor.
	 */
	private function get_dependencies_for( ReflectionClass $reflection ): array {
		$constructor = $reflection->getConstructor();
		$class       = $reflection->getName();

		if ( null === $constructor ) {
			return [];
		}

		return array_map(
			function ( ReflectionParameter $parameter ) use ( $class ) {
				return $this->resolve_argument(
					$class,
					$parameter
				);
			},
			$constructor->getParameters()
		);
	}

	/**
	 * Resolve a given reflected argument.
	 *
	 * @since 1.0.0
	 *
	 * @param string              $class Name of the class to resolve the arguments for.
	 * @param ReflectionParameter $parameter Parameter to resolve.
	 * @return mixed               Resolved value of the argument.
	 */
	private function resolve_argument( string $class, ReflectionParameter $parameter ) {
		if ( ! $parameter->hasType() ) {
			return $this->resolve_argument_by_name(
				$class,
				$parameter
			);
		}

		$type = $parameter->getType();

		// In PHP 8.0, the isBuiltin method was removed from the parent {@see ReflectionType} class.
		if ( null === $type || ( $type instanceof ReflectionNamedType && $type->isBuiltin() ) ) {
			return $this->resolve_argument_by_name(
				$class,
				$parameter
			);
		}

		$type = $type instanceof ReflectionNamedType
			? $type->getName()
			: (string) $type;

		return $this->make_dependency( $type );
	}

	/**
	 * Resolve a given reflected argument by its name.
	 *
	 * @since 1.0.0
	 *
	 * @param string              $class Class to resolve the argument for.
	 * @param ReflectionParameter $parameter Argument to resolve by name.
	 * @return mixed               Resolved value of the argument.
	 */
	private function resolve_argument_by_name( string $class, ReflectionParameter $parameter ) {
		$name = $parameter->getName();

		try {
			if ( $parameter->isDefaultValueAvailable() ) {
				return $parameter->getDefaultValue();
			}
		} catch ( Throwable $exception ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// Just fall through into the Failed_To_Make_Instance exception.
		}

		// Out of options, fail with an exception.
		throw Failed_To_Make_Instance::for_unresolved_argument( $name, $class );
	}

	/**
	 * Ensure that a given reflected class is instantiable.
	 *
	 * @since 1.0.0
	 *
	 * @param ReflectionClass $reflection Reflected class to check.
	 * @return void
	 * @throws Failed_To_Make_Instance If the interface could not be resolved.
	 */
	private function ensure_is_instantiable( ReflectionClass $reflection ) {
		if ( ! $reflection->isInstantiable() ) {
			throw Failed_To_Make_Instance::for_unresolved_interface( $reflection->getName() );
		}
	}

	/**
	 * Check whether a shared instance exists for a given class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Class to check for a shared instance.
	 * @return bool   Whether a shared instance exists.
	 */
	private function has_shared_instance( string $class ): bool {
		return array_key_exists( $class, $this->shared_instances )
			&& null !== $this->shared_instances[ $class ];
	}

	/**
	 * Get the shared instance for a given class.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $class Class to get the shared instance for.
	 * @return object  Shared instance.
	 */
	private function get_shared_instance( string $class ) {
		if ( ! $this->has_shared_instance( $class ) ) {
			throw Failed_To_Make_Instance::for_uninstantiated_shared_instance( $class );
		}

		return (object) $this->shared_instances[ $class ];
	}

	/**
	 * Get the reflection for a class or throw an exception.
	 *
	 * @since 1.0.0
	 *
	 * @param string                   $class Class to get the reflection for.
	 * @return ReflectionClass          Class reflection.
	 * @throws Failed_To_Make_Instance  If the class could not be reflected.
	 */
	private function get_class_reflection( string $class ): ReflectionClass {
		try {
			return new ReflectionClass( $class );
		} catch ( Throwable $exception ) {
			throw Failed_To_Make_Instance::for_unreflectable_class( $class );
		}
	}

	/**
	 * Get a fallback instantiator in case none was provided.
	 *
	 * @since 1.0.0
	 *
	 * @return Instantiator Simplistic fallback instantiator.
	 */
	private function get_fallback_instantiator(): Instantiator {
		return new class() implements Instantiator {

			/**
			 * Make an object instance out of an class.
			 *
			 * @param string  $class Class to make an object instance out of.
			 * @param array   $dependencies Optional. Dependencies of the class.
			 * @return object  Instantiated object.
			 */
			public function instantiate( string $class, array $dependencies = [] ) {
				return new $class( ...$dependencies );
			}
		};
	}
}
