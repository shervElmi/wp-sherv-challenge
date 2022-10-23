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

import liveReload from 'vite-plugin-live-reload';

const { resolve } = require('path');
const fs = require('fs');

export default {
	root: '',
	base: process.env.NODE_ENV === 'development' ? '/' : '/dist/',
	plugins: [liveReload(__dirname + '/**/*.php')],
	build: {
		// Output dir for production build.
		outDir: resolve(__dirname, './dist'),
		emptyOutDir: true,
		// Emit manifest so PHP can find the hashed files.
		manifest: true,
		// Esbuild target.
		target: 'es2015',
		rollupOptions: {
			input: {
				main: resolve(__dirname + '/assets/js/index.js'),
			},
			output: {
				entryFileNames: 'index.js',
			},
			manualChunks: {
				'insider-progress-bar': [
					resolve(
						__dirname +
							'/assets/js/elementor-widgets/progress-bar/progress-bar.js'
					),
				],
				'insider-styles': [
					resolve(__dirname + '/assets/css/styles.css'),
				],
			},
		},
		// Minifying switch.
		minify: true,
		write: true,
	},
	server: {
		cors: true,
		strictPort: true,
		port: 3000,
		https: false,
		hmr: {
			host: 'localhost',
		},
	},
};
