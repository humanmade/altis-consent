/* global wp_set_consent wp_has_consent consent_api_set_cookie altisConsent */
/**
 * Handle toggling cookie consent.
 *
 * @package Altis-Consent
 */

const giveConsentButton  = document.querySelector( '.give-consent' ),
	revokeConsentButton  = document.querySelector( '.revoke-consent' ),
	cookiePreferences    = document.querySelector( '.cookie-preferences' ).classList,
	cookiePrefsButton    = document.querySelector( '.view-preferences' ),
	applyCookiePrefs     = document.querySelector( '.apply-cookie-preferences' ),
	cookieUpdatedMessage = document.querySelector( '.consent-updated-message' ).classList,
	closeUpdatedMessage  = document.getElementById( 'consent-close-updated-message' ),
	consentBanner        = document.getElementById( 'cookie-consent-banner' );

/**
 * Check if a user has given consent for a specific category.
 *
 * Wrapper function for wp_has_consent.
 *
 * @param {string} category The category to check consent against.
 */
function hasConsent( category ) {
	return wp_has_consent( category );
}

/**
 * Set a new consent category value.
 *
 * Wrapper function for wp_set_consent.
 *
 * @param {string} category The consent category to update.
 * @param {string} value The value to update the consent category to.
 */
function setConsent( category, value ) {
	wp_set_consent( category, value );
}

/**
 * Set cookie by consent type.
 *
 * Wrapper function for consent_api_set_cookie.
 *
 * @param {string} name The cookie name to set.
 * @param {string} value The cookie value to set.
 */
function setCookie( name, value ) {
	consent_api_set_cookie( name, value );
}

/**
 * Retrieve a cookie by name.
 *
 * Wrapper function for consent_api_get_cookie.
 *
 * @param {string} name The name of the cookie to get data from.
 */
function getCookie( name ) {
	return consent_api_get_cookie( name );
}

/**
 * Check if a consent cookie has already been saved on the client machine.
 *
 * @return {bool} Return true if consent has been given previously.
 */
function consentCookieSaved() {
	let consentExists = false;
	altisConsent.categories
		// Skip the functional cookies preference.
		.filter( category => category === 'functional' )
		// Loop through the rest of the categories.
		.forEach( category => {
			if ( hasConsent( category ) ) {
				consentExists = true;
			}

			return;
		} );

	return consentExists;
}

/**
 * Return an array of all the categories that a user has consented to.
 *
 * @return {array} An array of allowed cookie categories.
 */
function getConsentedCategories() {
	// Start off with the allowlisted categories.
	let hasConsent = altisConsent.alwaysAllowCategories;

	altisConsent.categories.forEach( category => {
		if ( hasConsent( category ) ) {
			hasConsent.push( category );
		}
	} );

	return hasConsent;
}

/**
 * Update consent for individual categories.
 */
function updateConsentCategories() {
	const categories = document.getElementsByClassName( 'category-input' );
	let selected   = [],
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
	const allowAllClasses      = giveConsentButton.classList,
		allowFunctionalClasses = revokeConsentButton.classList;

	cookiePreferences.toggle( 'show' );

	// Toggle the other buttons when we show the cookie prefs.
	allowAllClasses.toggle( 'hide' );
	allowFunctionalClasses.toggle( 'hide' );
}

/**
 * Check if consent has been given already. If not, toggle display of the banner.
 */
function maybeDisplayBanner() {
	if ( ! consentCookieSaved() && altisConsent.shouldDisplayBanner ) {
		consentBanner.style.display = 'block';
	}
}

/**
 * Show the preferences updated message.
 */
function preferencesUpdatedMessage() {
	const consentUpdated = document.querySelector( '.consent-updated-message' ).classList;
	consentUpdated.toggle( 'show' );
}

// Display the banner. Or not.
maybeDisplayBanner();

// Toggle consent when grant/revoke consent button is clicked.
giveConsentButton.addEventListener( 'click', updateConsentCategories );
revokeConsentButton.addEventListener( 'click', updateConsentCategories );

// Make sure the preverences button exists before triggering an on-click action.
if ( cookiePrefsButton ) {
	cookiePrefsButton.addEventListener( 'click', toggleCookiePrefs );
	applyCookiePrefs.addEventListener( 'click', updateConsentCategories );
}

// Close the banner if the close button is clicked.
closeUpdatedMessage.addEventListener( 'click', () => cookieUpdatedMessage.toggle( 'show' ) );
