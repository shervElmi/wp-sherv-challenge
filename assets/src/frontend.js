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

/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import './sass/frontend.scss';
import { ChallengeTable } from './components/challengeTable';

const {
	api: { strategy11Data: endpoint },
} = window.shervChallengeShortocdeSettings;

domReady( () => {
	const shortcode = document.getElementById( 'sherv-challenge-shortcode' );

	if ( shortcode ) {
		wp.element.render(
			<ChallengeTable endpoint={ endpoint } />,
			shortcode
		);
	}
} );
