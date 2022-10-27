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
 * External dependencies
 */
import PropTypes from 'prop-types';
import slugify from 'slugify';

export const Table = ( { title, header, body, className = '' } ) => {
	if ( ! header || ! body ) {
		return null;
	}

	const TableTitle = () => {
		if ( ! title ) {
			return null;
		}

		return <caption className="s11-table__title">{ title }</caption>;
	};

	const TableHeader = () => {
		return (
			<thead className="s11-table__header">
				<tr className="s11-table__row">
					{ header.map( ( headerItem ) => (
						<th
							className="s11-table__cell s11-table__header-cell"
							key={ slugify( headerItem ) }
						>
							{ headerItem }
						</th>
					) ) }
				</tr>
			</thead>
		);
	};

	const TableBody = () => {
		return (
			<tbody className="s11-table__body">
				{ body.map( ( row ) => (
					<tr className="s11-table__row" key={ row.id }>
						{ Object.values( row ).map( ( cell ) => (
							<td className="s11-table__cell" key={ row.id }>
								{ cell }
							</td>
						) ) }
					</tr>
				) ) }
			</tbody>
		);
	};

	className = `s11-table ${ className }`;

	return (
		<table className={ className }>
			<TableTitle />
			<TableHeader />
			<TableBody />
		</table>
	);
};

Table.propTypes = {
	title: PropTypes.string,
	header: PropTypes.array,
	body: PropTypes.array,
	className: PropTypes.string,
};

export default Table;
