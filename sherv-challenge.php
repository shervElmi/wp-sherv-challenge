<?php
/**
 * Main plugin file.
 *
 * @package   Strategy11/Sherv_Challenge
 * @author    Sherv Elmi <sherv.elmi@gmail.com>
 * @license   GNU General Public License 3.0
 * @link      https://strategy11.com/
 * @copyright 2022 Strategy11.
 *
 * @wordpress-plugin
 * Plugin Name: Sherv Challenge
 * Description: Strateg11 coding challenge.
 * Plugin URI: https://strategy11.com/
 * Author: Sherv Elmi <sherv.elmi@gmail.com>
 * Author URI: https://elmi.dev/
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * Tested up to: 6.0.3
 * Text Domain: sherv-challenge
 * Domain Path: /languages
 * License: GNU General Public License 3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
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

/**
 * As this is the only PHP file having side effects, we need to provide a
 * safeguard, So we want to make sure this file is only run from within
 * WordPress and cannot be directly called through a web request.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use WP_Error;
use Plugin_Requirements;
use Admin_Notice;

define( 'SHERV_CHALLENGE_VERSION', '1.0.0' );
define( 'SHERV_CHALLENGE_FILE', __FILE__ );
define( 'SHERV_CHALLENGE_PATH', plugin_dir_path( SHERV_CHALLENGE_FILE ) );
define( 'SHERV_CHALLENGE_URL', plugin_dir_url( SHERV_CHALLENGE_FILE ) );
define( 'SHERV_CHALLENGE_MINIMUM_PHP_VERSION', '8.0' );
define( 'SHERV_CHALLENGE_MINIMUM_WP_VERSION', '5.8' );

if ( ! defined( 'SHERV_CHALLENGE_DEV_MODE' ) ) {
	define( 'SHERV_CHALLENGE_DEV_MODE', false );
}

/**
 * Setup Plugin Requirements class.
 */
require_once SHERV_CHALLENGE_PATH . '/includes/Compatibility/Plugin_Requirements.php';

$plugin_requirements = new Plugin_Requirements( new WP_Error() );

$plugin_requirements->set_php_version( SHERV_CHALLENGE_MINIMUM_PHP_VERSION );
$plugin_requirements->set_wp_version( SHERV_CHALLENGE_MINIMUM_WP_VERSION );
$plugin_requirements->set_required_files(
	[
		SHERV_CHALLENGE_PATH . '/vendor/autoload.php',
	]
);

$plugin_requirements->run_checks();

/**
 * We must stop further execution, If there is an error and
 * Displays an admin notice that show why the plugin is unable to load.
 */
if ( $plugin_requirements->get_wp_error()->errors ) {
	// Main plugin initialization happens there so that this file is still parsable in PHP < 7.0.
	require_once SHERV_CHALLENGE_PATH . '/includes/Admin/Admin_Notice.php';

	$admin_notice = new Admin_Notice( $plugin_requirements->get_wp_error() );
	$admin_notice->register();

	unset( $admin_notice );

	return;
}

unset( $plugin_requirements );

// Load the Composer autoloader.
require_once SHERV_CHALLENGE_PATH . '/vendor/autoload.php';

/**
 * Handles plugin activation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function activate() {
	// Run all Plugin_Activation components.
	Plugin_Factory::create()->on_plugin_activation();

	/**
	 * Fires after plugin activation.
	 */
	do_action( 'sherv_challenge_activation' );
}

register_activation_hook( SHERV_CHALLENGE_FILE, __NAMESPACE__ . '\activate' );

/**
 * Handles plugin deactivation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function deactivate() {
	// Run all Plugin_Deactivation components.
	Plugin_Factory::create()->on_plugin_deactivation();

	/**
	 * Fires after plugin deactivation.
	 */
	do_action( 'sherv_challenge_deactivation' );
}

register_deactivation_hook( SHERV_CHALLENGE_FILE, __NAMESPACE__ . '\deactivate' );

/**
 * Finally, we run the plugin's register method to Hook the plugin into the
 * WordPress request lifecycle.
 *
 * We use a factory to instantiate the actual plugin.
 * The factory keeps the object as a shared instance, so that you can also
 * get outside access to that same plugin instance through the factory.
 */
Plugin_Factory::create()->register();
