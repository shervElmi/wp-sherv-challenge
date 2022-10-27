<?php
/**
 * Plugin (main) class.
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

use Strategy11\Sherv_Challenge\Infrastructure\Plugin_Base;

/**
 * Plugin class.
 *
 * The "plugin" is only a tool to hook arbitrary code up to the WordPress
 * execution flow.
 *
 * The main structure we use to modularize our code is "components". These are
 * what makes up the actual plugin, and they provide self-contained pieces
 * of code that can work independently.
 *
 * @since 1.0.0
 */
final class Plugin extends Plugin_Base {

	/**
	 * List of components to register.
	 *
	 * The components array contains a map of <identifier> => <component class name>
	 * associations.
	 *
	 * @var array<string> Associative array of identifiers mapped to fully qualified class names.
	 */
	public const COMPONENTS = [
		'capabilities'               => User\Capabilities::class,
		'rest.strategy11_controller' => REST_API\Strategy11_Controller::class,
		'admin'                      => Admin\Admin::class,
		'dashboard'                  => Admin\Dashboard::class,
		'admin_ajax'                 => Admin\Admin_Ajax::class,
		'challenge_shortocde'        => Shortcode\Challenge_Shortcode::class,
	];

	/**
	 * Shared instances classes.
	 *
	 * The shared instances array contains a list of FQCNs (fully qualified class names)
	 * that are meant to be reused. For multiple "make()" requests, the injector will return
	 * the same instance reference for these, instead of always returning a new one.
	 *
	 * This effectively turns these FQCNs (fully qualified class names) into a "singleton",
	 * without incurring all the drawbacks of the Singleton design anti-pattern.
	 *
	 * @var array<string> Array of fully qualified class names.
	 */
	public const SHARED_INSTANCE_CLASSES = [
		Assets::class,
	];
}
