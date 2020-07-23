/* global jQuery wp_has_consent wp_set_consent */
jQuery( document ).ready( function ( $ ) {
	const consentCategory = $( '#cookie-consent-banner' ).data( 'consentcategory' ),
		giveConsentButton = $( '.give-consent' ),
		revokeConsentButton = $( '.revoke-consent' ),
		cookiePrefsButton = $( '.view-preferences' );

	console.log( `checking consent for category ${consentCategory}` );
	/**
	 * cookie placing plugin can listen to consent change
	 *
	 */
	console.log( 'load plugin example' );
	document.addEventListener( 'wp_listen_for_consent_change', function ( e ) {
		console.log( 'listen for consent events' );
		let changedConsentCategory = e.detail;
		console.log( changedConsentCategory );
		for ( let key in changedConsentCategory ) {
			if ( changedConsentCategory.hasOwnProperty( key ) ) {
				if ( key === consentCategory && changedConsentCategory[key] === 'allow' ) {
					console.log( `set ${consentCategory} cookie on user actions` );
					activateConsent();
				}
			}
		}
	} );

	/**
	 * Or do stuff as soon as the consenttype is defined
	 */
	$( document ).on( 'wp_consent_type_defined', activateMyCookies );

	function activateMyCookies( consentData ) {
		//your code here
		if ( wp_has_consent( consentCategory ) ) {
			console.log( `do ${consentCategory} cookie stuff` );
		} else {
			console.log( `no ${consentCategory} cookies please` );
		}
	}

	//check if we need to wait for the consenttype to be set
	if ( ! window.waitfor_consent_hook ) {
		console.log( 'we don\'t have to wait for the consent type, we can check the consent level right away!' );
		if ( wp_has_consent( consentCategory ) ) {
			activateConsent();
			console.log( `set ${consentCategory} stuff now!` );
		} else {
			console.log( `No ${consentCategory} stuff please!` );
		}
	}

	/**
	 * Do stuff that normally would do stuff like tracking personal user data etc.
	 */

	function activateConsent() {
		console.log( `fire ${consentCategory}` );
		$( '#example-plugin-content .functional-content' ).hide();
		$( '#example-plugin-content .marketing-content' ).show();
	}

	function revokeConsent() {
		console.log( `fire ${consentCategory}` );
		$( '#example-plugin-content .marketing-content' ).hide();
		$( '#example-plugin-content .functional-content' ).show();
	}

	function updateConsent() {
		if ( wp_has_consent( consentCategory ) ) {
			wp_set_consent( consentCategory, 'deny' );
			revokeConsent();
		} else {
			wp_set_consent( consentCategory, 'allow' );
			activateConsent();
		}
	}

	function toggleCookiePrefs() {
		$( '.cookie-preferences' ).toggle();

		// Toggle the other buttons when we show the cookie prefs.
		if ( giveConsentButton.is( ':visible' ) || revokeConsentButton.is( ':visible' ) ) {
			giveConsentButton.hide();
			revokeConsentButton.hide();
		} else {
			giveConsentButton.show();
			revokeConsentButton.show();
		}
	}

	giveConsentButton.on( 'click', updateConsent );
	revokeConsentButton.on( 'click', updateConsent );
	cookiePrefsButton.on( 'click', toggleCookiePrefs );
} );
