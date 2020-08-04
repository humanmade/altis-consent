/* global jQuery wp_set_consent altisConsent */
/**
 * Handle toggling cookie consent.
 *
 * @package Altis-Consent
 */

jQuery( document ).ready( function ( $ ) {
	const consentCategory = $( '#cookie-consent-banner' ).data( 'consentcategory' ),
		giveConsentButton = $( '.give-consent' ),
		revokeConsentButton = $( '.revoke-consent' ),
		cookiePrefsButton = $( '.view-preferences' ),
		applyCookiePrefs = $( '.apply-cookie-preferences' ),
		cookieUpdatedMessage = $( '.consent-updated-message' ),
		closeUpdatedMessage = $( '.consent-updated-message .close-message' );

	/**
	 * Toggle cookie consent.
	 *
	 * @param {string} category A specific category to update consent for.
	 * @param {string} value    The consent value to set. Should be either 'allow', 'deny' or another value that has been defined through the consent API.
	 */
	function updateConsent( category = '', value = 'allow' ) {
		// If no category is passed, default to the category defined in the banner wrap.
		category = ( category ) ? category : consentCategory;

		wp_set_consent( category, value );
	}

	/**
	 * Update consent for individual categories.
	 */
	function updateConsentCategories() {
		const categories = document.getElementsByClassName( 'category-input' );
		let selected = [],
			unselected = [];

		for ( const category of categories ) {
			if ( category.checked ) {
				selected.push( category.value );
			} else {
				unselected.push( category.value );
			}
		}

		// Set the allowed categories.
		for ( const selectedCategory of selected ) {
			updateConsent( selectedCategory );
		}

		// Set the disallowed categories.
		for ( const unselectedCategory of unselected ) {
			updateConsent( unselectedCategory, 'deny' );
		}

		// Toggle the cookie preferences if we've passed specific categories.
		if ( $( '.cookie-preferences' ).hasClass( 'show' ) ) {
			$( '.cookie-preferences' ).removeClass( 'show' );

			// Show the buttons if they are hidden.
			giveConsentButton.removeClass( 'hide' );
			revokeConsentButton.removeClass( 'hide' );
		}

		$( '#cookie-consent-banner' ).addClass( 'hide' );

		preferencesUpdatedMessage();
	}

	/**
	 * Show or hide the cookie preferences.
	 */
	function toggleCookiePrefs() {
		const cookiePrefs = $( '.cookie-preferences' );
		cookiePrefs.toggleClass( 'show' );

		// Toggle the other buttons when we show the cookie prefs.
		giveConsentButton.toggleClass( 'hide' );
		revokeConsentButton.toggleClass( 'hide' );
	}

	function preferencesUpdatedMessage() {
		$( '.consent-updated-message' ).toggleClass( 'show' );
	}

	giveConsentButton.on( 'click', updateConsentCategories );
	revokeConsentButton.on( 'click', updateConsentCategories );

	// Make sure the preverences button exists before triggering an on-click action.
	if ( cookiePrefsButton ) {
		cookiePrefsButton.on( 'click', toggleCookiePrefs );
		applyCookiePrefs.on( 'click', updateConsentCategories );
	}

	closeUpdatedMessage.on( 'click', () => cookieUpdatedMessage.toggleClass( 'show' ) );
} );
