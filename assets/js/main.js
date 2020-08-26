/* global wp_set_consent altisConsent */
/**
 * Handle toggling cookie consent.
 *
 * @package Altis-Consent
 */

const giveConsentButton = document.querySelector( '.give-consent' ),
	revokeConsentButton = document.querySelector( '.revoke-consent' ),
	cookiePreferences = document.querySelector( '.cookie-preferences' ),
	cookiePrefsButton = document.querySelector( '.view-preferences' ),
	applyCookiePrefs = document.querySelector( '.apply-cookie-preferences' ),
	cookieUpdatedMessage = document.querySelector( '.consent-updated-message' ),
	closeUpdatedMessage = document.getElementById( 'consent-close-updated-message' );

	/**
	 * Update consent for individual categories.
	 */
	function updateConsentCategories() {
		const categories = document.getElementsByClassName( 'category-input' );
		let selected = [],
			unselected = [];

		// If we're selecting categories from inputs, add the selected categories to an array and the unselected categories to a different array.
		if ( cookiePreferences.hasClass( 'show' ) ) {
			for ( const category of categories ) {
				if ( category.checked ) {
					selected.push( category.value );
				} else {
					unselected.push( category.value );
				}
			}
		}

		// If we're consenting to all cookies, add all categories to the selected array.
		if ( this.className === 'give-consent' ) {
			// Accept all categories.
			for ( const category of altisConsent.categories ) {
				selected.push( category );
			}

			unselected = [];
		} else if ( this.className === 'revoke-consent' ) {
			// If we're only consenting to functional cookies, only add that category to selected and all others to unselected.
			for ( const category of altisConsent.categories ) {
				if ( ! altisConsent.alwaysAllowCategories.includes( category ) ) {
					unselected.push( category );
				}
			}

			selected.push( ...altisConsent.alwaysAllowCategories );
		}

		Array.from( new Set( selected ) );
		Array.from( new Set( unselected ) );

		// Set the allowed categories.
		for ( const selectedCategory of selected ) {
			wp_set_consent( selectedCategory, 'allow' );
		}

		// Set the disallowed categories.
		for ( const unselectedCategory of unselected ) {
			wp_set_consent( unselectedCategory, 'deny' );
		}

		// Toggle the cookie preferences if we've passed specific categories.
		if ( cookiePreferences.hasClass( 'show' ) ) {
			cookiePreferences.removeClass( 'show' );

			// Show the buttons if they are hidden.
			giveConsentButton.removeClass( 'hide' );
			revokeConsentButton.removeClass( 'hide' );
		}

	document.querrySelector( 'consent-banner' ).classList.add( 'hide' );

	preferencesUpdatedMessage();
}

	/**
	 * Show or hide the cookie preferences.
	 */
	function toggleCookiePrefs() {
		cookiePreferences.toggleClass( 'show' );

		// Toggle the other buttons when we show the cookie prefs.
		giveConsentButton.toggleClass( 'hide' );
		revokeConsentButton.toggleClass( 'hide' );
	}

/**
 * Show the preferences updated message.
 */
function preferencesUpdatedMessage() {
	document.querySelector( '.consent-updated-message' ).toggle( 'show' );
}

	giveConsentButton.on( 'click', updateConsentCategories );
	revokeConsentButton.on( 'click', updateConsentCategories );

	// Make sure the preverences button exists before triggering an on-click action.
	if ( cookiePrefsButton ) {
		cookiePrefsButton.on( 'click', toggleCookiePrefs );
		applyCookiePrefs.on( 'click', updateConsentCategories );
	}

	closeUpdatedMessage.on( 'click', () => cookieUpdatedMessage.toggleClass( 'show' ) );
