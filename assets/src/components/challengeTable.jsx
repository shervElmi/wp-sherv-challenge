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
import { useDebouncedCallback } from 'use-debounce';

/**
 * WordPress dependencies
 */
import { useCallback, useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Button from '../ui/button';
import Table from '../ui/table';
import LoaderContainer from './loaderContainer';

export const ChallengeTable = ( {
	endpoint = '',
	refreshButtonVisible = false,
	nonce = '',
	ajaxURL = '',
} ) => {
	const [ fetchedChallengeData, setFetchedChallengeData ] = useState( {} );
	const [ isFetchingChallengeData, setIsFetchingChallengeData ] =
		useState( true );

	const fetchChallengeData = useCallback( async () => {
		if ( ! endpoint ) {
			// eslint-disable-next-line no-alert
			alert( 'Strategy11 remote endpoint does not available.' );
			return;
		}

		try {
			setIsFetchingChallengeData( true );

			const challengeData = await apiFetch( {
				path: endpoint,
			} );

			if ( typeof challengeData === 'object' && challengeData !== null ) {
				setFetchedChallengeData( challengeData );
			}
		} catch ( error ) {
			setFetchedChallengeData( {} );
		} finally {
			setIsFetchingChallengeData( false );
		}
	}, [ endpoint ] );

	const debouncedFetchChallengeData = useDebouncedCallback(
		fetchChallengeData,
		1000
	);

	useEffect( () => {
		debouncedFetchChallengeData();
	}, [ debouncedFetchChallengeData ] );

	const handleRemoveCache = useCallback( async () => {
		setIsFetchingChallengeData( true );

		const data = new FormData();
		data.append( 'action', 'sherv_challenge_remove_cache' );
		data.append( 'nonce', nonce );

		try {
			const response = await fetch( ajaxURL, {
				method: 'POST',
				credentials: 'same-origin',
				body: data,
			} );

			if ( response.ok ) {
				const responseJson = await response.json();

				if ( responseJson.success ) {
					await fetchChallengeData();
				}
			} else {
				// eslint-disable-next-line no-alert
				alert( `HTTP-Error: ${ response.status }` );
			}
		} finally {
			setIsFetchingChallengeData( false );
		}
	}, [ ajaxURL, fetchChallengeData, nonce ] );

	return (
		<div className="s11-challenge-table">
			{ refreshButtonVisible && (
				<Button
					onClick={ handleRemoveCache }
					disabled={ isFetchingChallengeData }
					className="button-primary"
				>
					{ __( 'Refresh Data', 'sherv-challenge' ) }
				</Button>
			) }
			{ isFetchingChallengeData ? (
				<LoaderContainer>
					{ __( 'Loading Data…', 'sherv-challenge' ) }
				</LoaderContainer>
			) : (
				<Table
					header={ fetchedChallengeData.header }
					body={ fetchedChallengeData.body }
					className="widefat"
				/>
			) }
		</div>
	);
};

ChallengeTable.propTypes = {
	endpoint: PropTypes.string.isRequired,
	refreshButtonVisible: PropTypes.bool,
	nonce: PropTypes.string,
	ajaxURL: PropTypes.string,
};

export default ChallengeTable;
