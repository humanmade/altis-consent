/* global jQuery wp_set_consent altisConsent */
/**
 * Handle toggling cookie consent.
 *
 * @package Altis-Consent
 */

jQuery( document ).ready( function ( $ ) {
	const giveConsentButton = $( '.give-consent' ),
		revokeConsentButton = $( '.revoke-consent' ),
		cookiePreferences = $( '.cookie-preferences' ),
		cookiePrefsButton = $( '.view-preferences' ),
		applyCookiePrefs = $( '.apply-cookie-preferences' ),
		cookieUpdatedMessage = $( '.consent-updated-message' ),
		closeUpdatedMessage = $( '.consent-updated-message .close-message' );

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

		// Set the allowed categories.
		for ( const selectedCategory of selected ) {
			wp_set_consent( selectedCategory, 'allow' );
		}

		// Set the disallowed categories.
		for ( const unselectedCategory of unselected ) {
			wp_set_consent( unselectedCategory, 'deny' );
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
