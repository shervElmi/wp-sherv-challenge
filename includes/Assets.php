<?php
/**
 * Assets class.
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

use Strategy11\Sherv_Challenge\Traits\Str;

/**
 * Assets class.
 *
 * @since 1.0.0
 */
class Assets {
	use Str;

	/**
	 * An array of registered styles.
	 *
	 * @var array<string|bool>
	 */
	protected $register_styles = [];

	/**
	 * An array of registered scripts.
	 *
	 * @var array<string|bool>
	 */
	protected $register_scripts = [];

	/**
	 * Get path to file and directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $path Path.
	 * @return string
	 */
	public function get_base_path( string $path ): string {
		return SHERV_CHALLENGE_PATH . $path;
	}

	/**
	 * Get url of file and directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $path Path.
	 * @return string
	 */
	public function get_base_url( string $path ): string {
		return SHERV_CHALLENGE_URL . $path;
	}

	/**
	 * Get asset metadata.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle Script handle.
	 * @return array  Array containing contents of "manifest.json".
	 *
	 * @phpstan-return AssetMetadata
	 */
	public function get_asset_metadata( string $handle ): array {
		$base_url = $this->get_base_url( 'dist/' );

		// "manifest.json" is generated by Vite.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$manifest_json = file_get_contents( $this->get_base_path( 'dist/manifest.json' ) );
		$manifest      = json_decode( $manifest_json, true );
		$assets_files  = array_column( $manifest, 'file' );

		$asset = [];

		foreach ( $assets_files as $file_name ) {
			if ( $this->str_contains( $file_name, $handle ) ) {
				$asset['file'] = $base_url . $file_name;
				break;
			}
		}

		$asset['version'] = SHERV_CHALLENGE_VERSION;

		return $asset;
	}

	/**
	 * Register script using handle.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since 1.0.0
	 *
	 * @param string   $script_handle Handle of script.
	 * @param string[] $script_dependencies Array of extra dependencies.
	 */
	public function register_script_asset( string $script_handle, array $script_dependencies = [] ): void {
		if ( isset( $this->register_scripts[ $script_handle ] ) || SHERV_CHALLENGE_DEV_MODE ) {
			return;
		}

		$asset         = $this->get_asset_metadata( $script_handle );
		$entry_path    = $asset['file'];
		$entry_version = $asset['version'];
		$in_footer     = true;

		$this->register_script(
			$script_handle,
			$entry_path,
			$script_dependencies,
			$entry_version,
			$in_footer
		);
	}

	/**
	 * Enqueue script using handle.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since 1.0.0
	 *
	 * @param string   $script_handle Handle of script.
	 * @param string[] $script_dependencies Array of extra dependencies.
	 */
	public function enqueue_script_asset( string $script_handle, array $script_dependencies = [] ): void {
		if ( SHERV_CHALLENGE_DEV_MODE ) {
			return;
		}

		$this->register_script_asset( $script_handle, $script_dependencies );
		$this->enqueue_script( $script_handle );
	}

	/**
	 * Register style using handle.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $style_handle Handle of style.
	 * @param string[] $style_dependencies Array of extra dependencies.
	 */
	public function register_style_asset( string $style_handle, array $style_dependencies = [] ): void {
		if ( isset( $this->register_styles[ $style_handle ] ) || SHERV_CHALLENGE_DEV_MODE ) {
			return;
		}

		$asset         = $this->get_asset_metadata( $style_handle );
		$entry_path    = $asset['file'];
		$entry_version = $asset['version'];

		$this->register_style(
			$style_handle,
			$entry_path,
			$style_dependencies,
			$entry_version
		);
	}

	/**
	 * Enqueue style using handle.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $style_handle Handle of style.
	 * @param string[] $style_dependencies Array of extra dependencies.
	 */
	public function enqueue_style_asset( string $style_handle, array $style_dependencies = [] ): void {
		if ( SHERV_CHALLENGE_DEV_MODE ) {
			return;
		}

		$this->register_style_asset( $style_handle, $style_dependencies );
		$this->enqueue_style( $style_handle );
	}

	/**
	 * Register a CSS stylesheet.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since 1.0.0
	 *
	 * @param string           $style_handle Name of the stylesheet. Should be unique.
	 * @param string|bool      $src Full URL of the stylesheet.
	 * @param string[]         $deps Optional. An array of registered stylesheet handles this stylesheet depends on.
	 * @param string|bool|null $ver Optional. String specifying stylesheet version number.
	 * @param string           $media Optional. The media for which this stylesheet has been defined.
	 *
	 * @return bool Whether the style has been registered. True on success, false on failure.
	 */
	public function register_style( string $style_handle, $src, array $deps = [], $ver = false, string $media = 'all' ): bool {
		if ( ! isset( $this->register_styles[ $style_handle ] ) ) {
			$this->register_styles[ $style_handle ] = wp_register_style( $style_handle, $src, $deps, $ver, $media );
		}

		return $this->register_styles[ $style_handle ];
	}

	/**
	 * Register a new script.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since 1.0.0
	 *
	 * @param string      $script_handle Name of the script. Should be unique.
	 * @param string|bool $src Full URL of the script.
	 * @param string[]    $deps Optional. An array of registered script handles this script depends on.
	 * @param bool        $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>.
	 *
	 * @return bool Whether the script has been registered. True on success, false on failure.
	 */
	public function register_script( string $script_handle, $src, array $deps = [], $ver = false, bool $in_footer = false ): bool {
		if ( ! isset( $this->register_scripts[ $script_handle ] ) ) {
			$this->register_scripts[ $script_handle ] = wp_register_script( $script_handle, $src, $deps, $ver, $in_footer );
		}

		return $this->register_scripts[ $script_handle ];
	}

	/**
	 * Enqueue a style.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since 1.0.0
	 *
	 * @param string           $style_handle Name of the stylesheet. Should be unique.
	 * @param string           $src Full URL of the stylesheet.
	 * @param string[]         $deps Optional. An array of registered stylesheet handles this stylesheet depends on.
	 * @param string|bool|null $ver Optional. String specifying stylesheet version number.
	 * @param string           $media Optional. The media for which this stylesheet has been defined.
	 */
	public function enqueue_style( string $style_handle, string $src = '', array $deps = [], $ver = false, string $media = 'all' ): void {
		$this->register_style( $style_handle, $src, $deps, $ver, $media );
		wp_enqueue_style( $style_handle, $src, $deps, $ver, $media );
	}

	/**
	 * Enqueue a script.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since 1.0.0
	 *
	 * @param string           $script_handle Name of the script. Should be unique.
	 * @param string           $src Full URL of the script
	 * @param string[]         $deps Optional. An array of registered script handles this script depends on.
	 * @param string|bool|null $ver Optional. String specifying script version number.
	 * @param bool             $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>.
	 */
	public function enqueue_script( string $script_handle, string $src = '', array $deps = [], $ver = false, bool $in_footer = false ): void {
		$this->register_script( $script_handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script( $script_handle, $src, $deps, $ver, $in_footer );
	}
}
