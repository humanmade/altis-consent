<?php
/**
 * Altis Consent Cookie Policy
 *
 * @package altis/consent
 */

namespace Altis\Consent\CookiePolicy;

/**
 * Return the default cookie policy content.
 *
 * This default cookie policy consent text is based on the EU cookie policy consent text in the Complianz plugin and released under the GPLv2 license.
 *
 * @param bool $blocks Whether the site is using the block editor.
 * @link               https://github.com/Really-Simple-Plugins/complianz-gdpr
 * @link               https://github.com/Really-Simple-Plugins/complianz-gdpr/blob/master/config/documents/cookie-policy-eu.php
 *
 * @return string      The cookie policy content.
 */
function get_default_content( bool $blocks = true ) : string {
	$content   = '';
	$strings   = [];
	$site      = get_current_site();
	$site_name = $site->site_name;
	$domain    = $site->domain;

	$strings[] = '<h2>' . __( 'Introduction', 'altis-consent' ) . '</h2>';
	// Translators: %s is the current site name.
	$strings[] = '<p>' . sprintf( __( 'Our website, %s (hereinafter: "the website") uses cookies and other related technologies (for convenience all technologies are referred to as "cookies"). Cookies are also placed by third parties we have engaged. In the document below we inform you about the use of cookies on our website.', 'altis-consent' ), $site_name ) . '</p>';
	$strings[] = '<h3>' . __( 'What are cookies?', 'altis-consent' ) . '</h3>';
	$strings[] = '<p>' . __( 'A cookie is a small simple file that is sent along with pages of this website and stored by your browser on the hard drive of your computer or another device. The information stored therein may be returned to our servers or to the servers of the relevant third parties during a subsequent visit.', 'altis-consent' ) . '</p>';
	$strings[] = '<h3>' . __( 'What are scripts?', 'altis-consent' ) . '</h3>';
	$strings[] = '<p>' . __( 'A script is a piece of program code that is used to make our website function properly and interactively. This code is executed on our server or on your device.', 'altis-consent' ) . '</p>';
	$strings[] = '<h2>' . __( 'Consent', 'altis-consent' ) . '</h2>';
	$strings[] = '<p>' . __( 'When you visit our website for the first time, we will show you a pop-up with an explanation about cookies. As soon as you click the button to accept cookies, you consent to us using all cookies and plug-ins as described in the pop-up and this Cookie Policy. You can disable the use of cookies via your browser, but please note that our website may no longer work properly.', 'altis-consent' ) . '</p>';
	$strings[] = '<h3>' . __( 'Technical or functional cookies', 'altis-consent' ) . '</h3>';
	$strings[] = '<p>' . __( 'Some cookies ensure that certain parts of the website work properly and that your user preferences remain known. By placing functional cookies, we make it easier for you to visit our website. This way, you do not need to repeatedly enter the same information when visiting our website and, for example, the items remain in your shopping cart until you have paid. We may place these cookies without your consent.', 'altis-consent' ) . '</p>';
	$strings[] = '<h3>' . __( 'Analytical cookies', 'altis-consent' ) . '</h3>';
	$strings[] = '<p>' . __( 'We use analytical cookies to optimize the website experience for our users. With these analytical cookies we get insights in the usage of our website.', 'altis-consent' ) .
		'&nbsp;' . __( 'Some of these statistics are tracked anonymously. No permission is asked to place these analytical cookies.', 'altis-consent' ) .
		'&nbsp;' . __( 'For all others, we ask your permission to place analytical cookies.', 'altis-consent' ) . '</p>';
	$strings[] = '<h3>' . __( 'Advertising cookies', 'altis-consent' ) . '</h3>';
	// Translators: %s is the current site name.
	$strings[] = '<p>' . sprintf( __( 'On this website we may use tracking cookies, enabling us to gain insights into the campaign results. This happens based on a profile we create based on your behavior on %s. With these cookies you, as website visitor are linked to a unique ID, but will not profile your behavior and interests to serve personalized ads.', 'altis-consent' ), $site_name ) . '</p>';
	$strings[] = '<p>' . __( 'Because these cookies are marked as tracking cookies, we ask your permission to place these.', 'altis-consent' ) . '</p>';
	$strings[] = '<h3>' . __( 'Social media buttons', 'altis-consent' ) . '</h3>';
	$strings[] = '<p>' . __( 'On our website there may be buttons for social media sites to promote webpages (e.g. “like”, “pin”) or share (e.g. “tweet”) on social networks. These buttons work using pieces of code coming from those social networks themselves. This code places cookies. These social media buttons also can store and process certain information, so a personalized advertisement can be shown to you.', 'altis-consent' ) . '</p>';
	$strings[] = '<p>' . __( 'Please read the privacy statement of these social networks (which can change regularly) to read what they do with your (personal) data which they process using these cookies. The data that is retrieved is anonymized as much as possible.', 'altis-consent' ) . '</p>';
	$strings[] = '<h2>' . __( 'Your rights with respect to personal data', 'altis-consent' ) . '</h2>';
	$strings[] = '<ul>' .
		'<li>' . __( 'You have the right to know why your personal data is needed, what will happen to it, and how long it will be retained for.', 'altis-consent' ) . '</li>' .
		'<li>' . __( 'Right of access: You have the right to access your personal data that is known to us.', 'altis-consent' ) . '</li>' .
		'<li>' . __( 'Right to rectification: you have the right to supplement, correct, have deleted or blocked your personal data whenever you wish.', 'altis-consent' ) . '</li>' .
		'<li>' . __( 'If you give us your consent to process your data, you have the right to revoke that consent and to have your personal data deleted.', 'altis-consent' ) . '</li>' .
		'<li>' . __( 'Right to transfer your data: you have the right to request all your personal data from the controller and transfer it in its entirety to another controller.', 'altis-consent' ) . '</li>' .
		'<li>' . __( 'Right to object: you may object to the processing of your data. We comply with this, unless there are justified grounds for processing.', 'altis-consent' ) . '</li>' .
	'</ul>';
	$strings[] = '<p>' . __( 'To exercise these rights, please contact us. Please refer to the contact details at the bottom of this Cookie Policy. If you have a complaint about how we handle your data, we would like to hear from you, but you also have the right to submit a complaint to the supervisory authority (the Data Protection Authority).', 'altis-consent' ) . '</p>';
	$strings[] = '<h3>' . __( 'Enabling, disabling and deleting cookies', 'altis-consent' ) . '</h3>';
	$strings[] = '<p>' . __( 'You can use your internet browser to automatically or manually delete cookies. You can also specify that certain cookies may not be placed. Another option is to change the settings of your internet browser so that you receive a message each time a cookie is placed. For more information about these options, please refer to the instructions in the Help section of your browser.', 'altis-consent' ) . '</p>';
	$strings[] = '<p>' . __( 'Please note that our website may not work properly if all cookies are disabled. If you do delete the cookies in your browser, they will be placed again after your consent when you visit our websites again.', 'altis-consent' ) . '</p>';
	$strings[] = '<h2>' . __( 'Contact details', 'altis-consent' ) . '</h2>';
	$strings[] = '<p>' . __( 'For questions and/or comments about our Cookie Policy and this statement, please contact us by using the following contact details:', 'altis-consent' ) . '</p>';
	$strings[] = '<address>' . __( '[Organization Name]', 'altis-consent' ) . '<br />' .
		__( '[Company Address]', 'altis-consent' ) . '<br />' .
		__( '[Company Country]', 'altis-consent' ) . '<br />' .
		sprintf( __( 'Website: %s', 'altis-consent' ), $domain ) . '<br />' .
		// Translators: %s is the default text to display for company email address.
		sprintf( __( 'Email: %s', 'altis-consent' ),
			/**
			 * Filter the company email address that appears in the default cookie policy content.
			 *
			 * @param string $email The email address (or default text) to use in the cookie policy content.
			 */
			apply_filters( 'altis.consent.default_cookie_policy_email', '[Company Email]' )
		) . '<br />' .
		__( '[Company Telephone]', 'altis-consent' ) .
	'</address>';

	if ( $blocks ) {
		foreach ( $strings as $key => $string ) {
			if ( strpos( $string, '<p>' ) === 0 ) {
				$strings[ $key ] = '<!-- wp:paragraph -->' . $string . '<!-- /wp:paragraph -->';
				$strings[ $key ] .= "\n";
			}

			if ( strpos( $string, '<h2>' ) === 0 ) {
				$strings[ $key ] = '<!-- wp:heading -->' . $string . '<!-- /wp:heading -->';
				$strings[ $key ] .= "\n";
			}

			if ( strpos( $string, '<h3>' ) === 0 ) {
				$strings[ $key ] = '<!-- wp:heading {"level":3} -->' . $string . '<!-- /wp:heading -->';
				$strings[ $key ] .= "\n";
			}

			if ( strpos( $string, '<ul>' ) === 0 ) {
				$strings[ $key ] = '<!-- wp:list -->' . $string . '<!-- /wp:list -->';
				$strings[ $key ] .= "\n";
			}

			if ( strpos( $string, '<address>' ) === 0 ) {
				$strings[ $key ] = '<!-- wp:html -->' . $string . '<!-- /wp:html -->';
				$strings[ $key ] .= "\n";
			}
		}
	}

	$content = implode( '', $strings );

	/**
	 * Filter the default cookie consent policy content.
	 *
	 * @param string $content The full markup (including block editor markup, if applicable) of the cookie consent page.
	 * @param array  $strings An array of cookie policy content strings.
	 * @param bool   $blocks  Whether the content should be formatted for the block editor.
	 */
	return apply_filters( 'altis.consent.default_cookie_policy_content', $content, $strings, $blocks );
}
