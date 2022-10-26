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
import { Table } from '../ui';
import LoaderContainer from './loaderContainer';

const {
	api: { strategy11Data: strategy11DataAPI },
} = window.shervChallengeDashboardSettings;

export const ChallengeTable = () => {
	const [ fetchedChallengeData, setFetchedChallengeData ] = useState( [] );
	const [ isFetchingChallengeData, setIsFetchingChallengeData ] =
		useState( false );

	const fetchChallengeData = useCallback( async () => {
		try {
			setIsFetchingChallengeData( true );

			const challengeData = await apiFetch( {
				path: strategy11DataAPI,
			} );

			if ( Array.isArray( challengeData ) ) {
				setFetchedChallengeData( challengeData );
			}
		} catch ( error ) {
			setFetchedChallengeData( [] );
		} finally {
			setIsFetchingChallengeData( false );
		}
	}, [] );

	const debouncedFetchChallengeData = useDebouncedCallback(
		fetchChallengeData,
		1000
	);

	useEffect( () => {
		debouncedFetchChallengeData();
	}, [ debouncedFetchChallengeData ] );

	if ( isFetchingChallengeData ) {
		return (
			<LoaderContainer>
				{ __( 'Loading Posts…', 'sherv-challenge' ) }
			</LoaderContainer>
		);
	}

	const { header, body } = fetchedChallengeData;
	return <Table header={ header } body={ body } />;
};

export default ChallengeTable;
