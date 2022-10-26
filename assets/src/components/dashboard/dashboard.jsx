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
 * Internal dependencies
 */
import ChallengeTable from '../challengeTable';
import DashboardLogo from './dashboardLogo';
import DashboardTitle from './dashboardTitle';

export const Dashboard = () => {
	return (
		<div className="s11-dashboard">
			<div className="s11-dashbaord__header">
				<DashboardLogo />
			</div>

			<div className="s11-dashboard__content">
				<DashboardTitle />
				<ChallengeTable />
			</div>
		</div>
	);
};

export default Dashboard;
