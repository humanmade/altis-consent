/* global wp_set_consent altisConsent */
/**
 * Handle toggling cookie consent.
 *
 * @package Altis-Consent
 */

const giveConsentButton = document.querySelector( '.give-consent' ),
	revokeConsentButton = document.querySelector( '.revoke-consent' ),
	cookiePreferences = document.querySelector( '.cookie-preferences' ).classList,
	cookiePrefsButton = document.querySelector( '.view-preferences' ),
	applyCookiePrefs = document.querySelector( '.apply-cookie-preferences' ),
	cookieUpdatedMessage = document.querySelector( '.consent-updated-message' ).classList,
	closeUpdatedMessage = document.getElementById( 'consent-close-updated-message' );

/**
 * Update consent for individual categories.
 */
function updateConsentCategories() {
	const categories = document.getElementsByClassName( 'category-input' );
	let selected = [],
		unselected = [];

	// If we're selecting categories from inputs, add the selected categories to an array and the unselected categories to a different array.
	if ( cookiePreferences && cookiePreferences.contains( 'show' ) ) {
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
	}

	// Add the categories that we're always allowing.
	selected.push( ...altisConsent.alwaysAllowCategories );

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
	if ( cookiePreferences && cookiePreferences.contains( 'show' ) ) {
		cookiePreferences.remove( 'show' );

		// Show the buttons if they are hidden.
		giveConsentButton.classList.remove( 'hide' );
		revokeConsentButton.classList.remove( 'hide' );
	}

	document.querySelector( '.consent-banner' ).classList.add( 'hide' );

	preferencesUpdatedMessage();
}

/**
 * Show or hide the cookie preferences.
 */
function toggleCookiePrefs() {
	cookiePreferences.toggle( 'show' );

	// Toggle the other buttons when we show the cookie prefs.
	giveConsentButton.toggle( 'hide' );
	revokeConsentButton.toggle( 'hide' );
}

/**
 * Show the preferences updated message.
 */
function preferencesUpdatedMessage() {
	document.querySelector( '.consent-updated-message' ).toggle( 'show' );
}

giveConsentButton.addEventListener( 'click', updateConsentCategories );
revokeConsentButton.addEventListener( 'click', updateConsentCategories );

// Make sure the preverences button exists before triggering an on-click action.
if ( cookiePrefsButton ) {
	cookiePrefsButton.addEventListener( 'click', toggleCookiePrefs );
	applyCookiePrefs.addEventListener( 'click', updateConsentCategories );
}

closeUpdatedMessage.addEventListener( 'click', () => cookieUpdatedMessage.toggle( 'show' ) );
