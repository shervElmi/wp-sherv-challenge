<?php
/**
 * Plugin_Base abstract class.
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

namespace Strategy11\Sherv_Challenge\Infrastructure;

use WP_CLI;
use Strategy11\Sherv_Challenge\Interfaces\{
	Plugin,
	Component\Component,
	Component\Component_Container as Component_Container_Interface,
	Component\Conditional,
	Component\CLI_Command,
	Component\Hookable,
	Component\Injector,
	Component\Plugin_Activation_Aware,
	Component\Plugin_Deactivation_Aware,
	Component\Registerable
};
use Strategy11\Sherv_Challenge\Infrastructure\{
	Component_Container\Lazily_Instantiated_Component
};
use Strategy11\Sherv_Challenge\Exception\Invalid_Component;

/**
 * Plugin_Base abstract class.
 *
 * This abstract base plugin provides all the code for working with
 * the dependency injector and the component container.
 *
 * @since 1.0.0
 */
abstract class Plugin_Base implements Plugin {

	/**
	 * The main structure we use to modularize our code is "components". These are
	 * what makes up the actual plugin, and they provide self-contained pieces
	 * of code that can work independently.
	 */

	/**
	 * List of components.
	 *
	 * The components array contains a map of <identifier> => <component class name>
	 * associations.
	 *
	 * @var array<string>
	 */
	public const COMPONENTS = [];

	/**
	 * Shared instances classes.
	 *
	 * The shared instances array contains FQCNs (fully qualified class names).
	 *
	 * @var array<string>
	 */
	public const SHARED_INSTANCE_CLASSES = [];


	/**
	 * WordPress action to trigger the component registration on.
	 *
	 * @var string
	 */
	public const REGISTRATION_ACTION = 'plugins_loaded';

	/**
	 * Component identifier for the injector.
	 *
	 * @var string
	 */
	public const INJECTOR_ID = 'injector';

	/**
	 * The dependency injector.
	 *
	 * @var Injector
	 */
	protected $injector;

	/**
	 * The component container.
	 *
	 * @since 1.0.0
	 *
	 * @var Component_Container_Interface<Component>
	 */
	protected $component_container;

	/**
	 * Instantiate a Plugin object.
	 *
	 * @since 1.0.0
	 *
	 * @param Injector|null                       $injector            Optional. Injector instance to use.
	 * @param Component_Container_Interface<Component>|null $component_container Optional. Component container instance to use.
	 */
	public function __construct(
		Injector $injector = null,
		Component_Container_Interface $component_container = null
	) {
		/**
		 * We need an injector and a container. We make them injectable so that
		 * we can easily provide overrides for testing, but we also make them
		 * optional and provide default implementations for easy regular usage.
		 */

		$this->injector = $injector ?? new Dependency_Injector();
		$this->injector = $this->configure_injector( $this->injector );

		$this->component_container = $component_container ?? new Component_Container();
	}

	/**
	 * Act on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function on_plugin_activation(): void {
		$this->register_components();

		foreach ( $this->component_container as $component ) {
			if ( $component instanceof Plugin_Activation_Aware ) {
				$component->on_plugin_activation();
			}
		}
	}

	/**
	 * Act on plugin deactivation.
	 *
	 * @since 1.0.0
	 */
	public function on_plugin_deactivation(): void {
		$this->register_components();

		foreach ( $this->component_container as $component ) {
			if ( $component instanceof Plugin_Deactivation_Aware ) {
				$component->on_plugin_deactivation();
			}
		}
	}

	/**
	 * Register the plugin with the WordPress system.
	 *
	 * @since 1.0.0
	 *
	 * @throws Invalid_Component If a component is not valid.
	 */
	public function register(): void {
		add_action(
			static::REGISTRATION_ACTION,
			[ $this, 'register_components' ]
		);
	}

	/**
	 * Register the individual components of this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @throws Invalid_Component If a component is not valid.
	 */
	public function register_components() {
		// We don't instantiate components twice.
		if ( count( $this->component_container ) > 0 ) {
			return;
		}

		// Add the injector as the very first component.
		$this->component_container->put(
			static::INJECTOR_ID,
			$this->injector
		);

		$components = $this->get_components();

		foreach ( $components as $id => $class ) {
			// Allow the components to hooked their registration.
			if ( is_a( $class, Hookable::class, true ) ) {
				$registration_action = $class::get_registration_action();

				if ( did_action( $registration_action ) ) {
					$this->register_component( $id, $class );

					continue;
				}

				add_action(
					$class::get_registration_action(),
					function () use ( $id, $class ) {
						$this->register_component( $id, $class );
					},
					$class::get_registration_action_priority()
				);

				continue;
			}

			$this->register_component( $id, $class );
		}
	}

	/**
	 * Register a single component.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id    ID of the component to register.
	 * @param string $class Class of the component to register.
	 */
	protected function register_component( string $id, string $class ) {
		// Only instantiate components that are actually needed.
		if ( is_a( $class, Conditional::class, true ) && ! $class::is_needed() ) {
			return;
		}

		$component = $this->instantiate_component( $class );

		$this->component_container->put( $id, $component );

		if ( $component instanceof CLI_Command && defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( $component::get_command_name(), $component );
		}

		if ( $component instanceof Registerable ) {
			$component->register();
		}
	}

	/**
	 * Instantiate a single component.
	 *
	 * @since 1.0.0
	 *
	 * @param string            $class Component class to instantiate.
	 * @throws Invalid_Component If the component could not be properly instantiated.
	 * @return Component         Instantiated component.
	 */
	protected function instantiate_component( $class ): Component {
		/**
		 * If the component is not registerable or plugin activation|deactivation aware
		 * we default to lazily instantiated components here for some basic optimization.
		 *
		 * The components will be properly instantiated once they are retrieved
		 * from the component container.
		 */
		$is_registerable       = is_a( $class, Registerable::class, true );
		$is_activation_aware   = is_a( $class, Plugin_Activation_Aware::class, true );
		$is_deactivation_aware = is_a( $class, Plugin_Deactivation_Aware::class, true );
		$is_cli_command        = defined( 'WP_CLI' ) && WP_CLI && is_a( $class, CLI_Command::class, true );

		if ( ! $is_registerable && ! $is_activation_aware && ! $is_deactivation_aware && ! $is_cli_command ) {
			return new Lazily_Instantiated_Component(
				function () use ( $class ) {
					return $this->injector->make( $class );
				}
			);
		}

		// The component needs to be registered, so instantiate right away.
		$component = $this->injector->make( $class );

		if ( ! $component instanceof Component ) {
			throw Invalid_Component::from_component( $component );
		}

		return $component;
	}

	/**
	 * Configure the provided injector.
	 *
	 * This method defines the mappings that the injector knows about, and the
	 * logic it requires to make more complex instantiations work.
	 *
	 * For more complex plugins, this should be extracted into a separate
	 * object or into configuration files.
	 *
	 * @since 1.0.0
	 *
	 * @param Injector $injector Injector instance to configure.
	 * @return Injector Configured injector instance.
	 */
	protected function configure_injector( Injector $injector ): Injector {
		$shared_instances = $this->get_shared_instances();

		foreach ( $shared_instances as $shared_instance ) {
			$injector = $injector->share( $shared_instance );
		}

		return $injector;
	}

	/**
	 * Get the component container that contains the components that make up the
	 * plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Component_Container_Interface Component container of the plugin.
	 */
	public function get_container(): Component_Container_Interface {
		return $this->component_container;
	}

	/**
	 * Get the list of components to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string> Associative array of identifiers mapped to fully qualified class names.
	 */
	protected function get_components(): array {
		return static::COMPONENTS;
	}

	/**
	 * Get the shared instances classes for the dependency injector.
	 *
	 * These classes will only be instantiated once by the injector and then
	 * reused on subsequent requests.
	 *
	 * This effectively turns them into singletons, without any of the
	 * drawbacks of the actual Singleton anti-pattern.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string> Array of fully qualified class names.
	 */
	protected function get_shared_instances(): array {
		return static::SHARED_INSTANCE_CLASSES;
	}
}
