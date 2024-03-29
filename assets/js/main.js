/* global wp_set_consent wp_has_consent consent_api_set_cookie consent_api_get_cookie altisConsent */
/**
 * Handle toggling cookie consent.
 */

// Use the Altis namespace. Since it's declared as a var, we can't redeclare as a let or const.
var Altis = window.Altis || {}; // eslint-disable-line no-var

/**
 * Set up the Altis.Consent namespace and preload with some variables we'll use in the baner display.
 */
Altis.Consent = {
	giveConsentButton: document.querySelector( '.give-consent' ),
	revokeConsentButton: document.querySelector( '.revoke-consent' ),
	cookiePreferences: document.querySelector( '.cookie-preferences' )?.classList || false,
	cookiePrefsButton: document.querySelector( '.view-preferences' ),
	applyCookiePrefs: document.querySelector( '.apply-cookie-preferences' ),
	cookieUpdatedMessage: document.querySelector( '.consent-updated-message' ).classList,
	closeUpdatedMessage: document.getElementById( 'consent-close-updated-message' ),
	consentBanner: document.getElementById( 'cookie-consent-banner' ),
};

/**
 * Check if a user has given consent for a specific category.
 *
 * Wrapper function for wp_has_consent.
 *
 * @param {string} category The category to check consent against.
 * @returns {boolean}           Whether the user has given consent for the given category.
 */
Altis.Consent.has = function ( category ) {
	return wp_has_consent( category );
};

/**
 * Set a new consent category value.
 *
 * Wrapper function for wp_set_consent.
 *
 * @param {string} category The consent category to update.
 * @param {string} value The value to update the consent category to.
 */
Altis.Consent.set = function ( category, value ) {
	wp_set_consent( category, value );
};

/**
 * Set cookie by consent type.
 *
 * Wrapper function for consent_api_set_cookie.
 *
 * @param {string} name The cookie name to set.
 * @param {string} value The cookie value to set.
 */
Altis.Consent.setCookie = function ( name, value ) { // eslint-disable-line no-unused-vars
	consent_api_set_cookie( name, value );
};

/**
 * Retrieve a cookie by name.
 *
 * Wrapper function for consent_api_get_cookie.
 *
 * @param {string} name The name of the cookie to get data from.
 * @returns {boolean}       Cookie data for the given cookie, if it exists.
 */
Altis.Consent.getCookie = function ( name ) { // eslint-disable-line no-unused-vars
	return consent_api_get_cookie( name );
};

/**
 * Check if a consent cookie has already been saved on the client machine.
 *
 * @returns {boolean} Return true if consent has been given previously.
 */
Altis.Consent.cookieSaved = function () {
	let consentExists = false;
	altisConsent.categories
		// Loop through the rest of the categories.
		.forEach( category => {
			if ( Altis.Consent.has( category ) ) {
				consentExists = true;
			}

			return;
		} );

	return consentExists;
};

/**
 * Return an array of all the categories that a user has consented to.
 *
 * @returns {Array} An array of allowed cookie categories.
 */
Altis.Consent.getCategories = function () { // eslint-disable-line no-unused-vars
	// Start off with the allowlisted categories.
	let hasConsent = altisConsent.alwaysAllowCategories.slice();

	altisConsent.categories.forEach( category => {
		if ( hasConsent.indexOf( category ) < 0 && Altis.Consent.has( category ) ) {
			hasConsent.push( category );
		}
	} );

	return hasConsent;
};

/**
 * Update consent for individual categories.
 */
Altis.Consent.updateCategories = function () {
	const categories = document.getElementsByClassName( 'category-input' );
	let selected   = [],
		unselected = [];

	// If we're selecting categories from inputs, add the selected categories to an array and the unselected categories to a different array.
	if ( Altis.Consent.cookiePreferences && Altis.Consent.cookiePreferences.contains( 'show' ) ) {
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
		Altis.Consent.set( selectedCategory, 'allow' );
	}

	// Set the disallowed categories.
	for ( const unselectedCategory of unselected ) {
		Altis.Consent.set( unselectedCategory, 'deny' );
	}

	// Toggle the cookie preferences if we've passed specific categories.
	if ( Altis.Consent.cookiePreferences && Altis.Consent.cookiePreferences.contains( 'show' ) ) {
		Altis.Consent.cookiePreferences.remove( 'show' );

		// Show the buttons if they are hidden.
		Altis.Consent.giveConsentButton.classList.remove( 'hide' );
		Altis.Consent.revokeConsentButton.classList.remove( 'hide' );
	}

	document.querySelector( '.consent-banner' ).classList.add( 'hide' );

	Altis.Consent.preferencesUpdatedMessage();
};

/**
 * Show or hide the cookie preferences.
 */
Altis.Consent.toggleCookiePrefs = function () {
	const allowAllClasses      = Altis.Consent.giveConsentButton.classList,
		allowFunctionalClasses = Altis.Consent.revokeConsentButton.classList;

	Altis.Consent.cookiePreferences.toggle( 'show' );

	// Toggle the other buttons when we show the cookie prefs.
	allowAllClasses.toggle( 'hide' );
	allowFunctionalClasses.toggle( 'hide' );
};

/**
 * Check if consent has been given already. If not, toggle display of the banner.
 */
Altis.Consent.maybeDisplayBanner = function () {
	if (
		// A consent cookie has not been saved...
		! Altis.Consent.cookieSaved() &&
		// We're not hiding the banner in the settings...
		altisConsent.shouldDisplayBanner &&
		// & the banner markup exists.
		Altis.Consent.consentBanner
	) {
		// Display the consent banner.
		Altis.Consent.consentBanner.style.display = 'block';
	}
};

/**
 * Show the preferences updated message.
 */
Altis.Consent.preferencesUpdatedMessage = function () {
	const consentUpdated = document.querySelector( '.consent-updated-message' ).classList;
	consentUpdated.toggle( 'show' );
};

// Display the banner. Or not.
Altis.Consent.maybeDisplayBanner();

// Toggle consent when grant/revoke consent button is clicked.
Altis.Consent.giveConsentButton.addEventListener( 'click', Altis.Consent.updateCategories );
Altis.Consent.revokeConsentButton.addEventListener( 'click', Altis.Consent.updateCategories );

// Make sure the preverences button exists before triggering an on-click action.
if ( Altis.Consent.cookiePrefsButton ) {
	Altis.Consent.cookiePrefsButton.addEventListener( 'click', Altis.Consent.toggleCookiePrefs );
	Altis.Consent.applyCookiePrefs.addEventListener( 'click', Altis.Consent.updateCategories );
}

// Close the banner if the close button is clicked.
Altis.Consent.closeUpdatedMessage.addEventListener( 'click', () => Altis.Consent.cookieUpdatedMessage.toggle( 'show' ) );
