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
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Link } from '../../ui';
import { Logo as LogoSVG } from '../../images';

export const DashboardLogo = () => {
	return (
		<div className="s11-dashboard__logo">
			<Link href="#" extraClass="s11-dashbaord__logo-link">
				<LogoSVG />
			</Link>

			{ /* We used the h1 tag in the "dashboard.php" template file. */ }
			{ /* Actually we check the javascript activation of the browser in the template file. */ }
			<span className="s11-dashbaord__logo-heading s11-h1">
				{ __( 'Strategy11 Sherv Challenge', 'sherv-challenge' ) }
			</span>
		</div>
	);
};

export default DashboardLogo;
