/* global jQuery wp_has_consent wp_set_consent */
jQuery( document ).ready( function ( $ ) {
	const consentCategory = $( '#cookie-consent-banner' ).data( 'consentcategory' ),
		giveConsentButton = $( '.give-consent' ),
		revokeConsentButton = $( '.revoke-consent' ),
		cookiePrefsButton = $( '.view-preferences' ),
		applyCookiePrefs = $( 'button.apply-cookie-preferences' );

	function updateConsent( category = '' ) {
		category = ( category ) ? category : consentCategory;
		if ( wp_has_consent( category ) ) {
			wp_set_consent( category, 'deny' );
		} else {
			wp_set_consent( category, 'allow' );
		}
	}

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

		for ( const selectedCategory of selected ) {
			wp_set_consent( selectedCategory, 'allow' );
		}

		for ( const unselectedCategory of unselected ) {
			wp_set_consent( unselectedCategory, 'deny' );
		}
	}

	function toggleCookiePrefs() {
		const cookiePrefs = $( '.cookie-preferences' );
		cookiePrefs.toggleClass( 'show' );

		// Toggle the other buttons when we show the cookie prefs.
		if ( cookiePrefs.hasClass( 'show' ) ) {
			giveConsentButton.css( {
				'opacity': 0,
				'z-index': -1,
			} );
			revokeConsentButton.css( {
				'opacity': 0,
				'z-index': -1,
			} );
		} else {
			giveConsentButton.css( {
				'opacity': 1,
				'z-index': 1,
			} );
			revokeConsentButton.css( {
				'opacity': 1,
				'z-index': 1,
			} );
		}
	}

	giveConsentButton.on( 'click', updateConsent );
	revokeConsentButton.on( 'click', updateConsent );

	// Make sure the preverences button exists before triggering an on-click action.
	if ( cookiePrefsButton ) {
		cookiePrefsButton.on( 'click', toggleCookiePrefs );
		applyCookiePrefs.on( 'click', updateConsentCategories );
	}
} );
