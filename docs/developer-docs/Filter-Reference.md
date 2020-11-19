## `altis.consent.allowlisted_categories`

An array of always allowed consent categories.

### Parameters

**`$allowlisted_categories`** _(array)_ An array of default categories to consent to automatically.

### Source

File: [`inc/namespace.php`](https://github.com/humanmade/altis-consent/blob/master/inc/namespace.php)  

## `altis.consent.consent_settings_fields`

Settings fields that appear on the Altis Privacy page.

### Parameters

**`$fields`** _(array)_ An array of settings fields with unique IDs, titles and callback functions in the following format:

```
$fields = [
	[
		'id'       => 'unique_setting_id',
		'title'    => __( 'Rendered Setting Label', 'altis-consent' ),
		'callback' => __NAMESPACE__ . '\\setting_callback_function',
	],
];
```

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.banner_options`

The available banner options to display on the options page.

### Parameters

**`$options`** _(array)_ An array of cookie banner options in the following format:

```
$options = [
	[
		'value' => 'none',
		'label' => __( 'Allow/Deny All Cookies', 'altis-consent' ),
	],
];
```

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.privacy_policy_message`

The actual text that displays above the Privacy Policy Page setting on the Privacy page. This language can be altered or removed entirely using this filter. The original text that displays duplicates the text that existed on the original WordPress core Privacy page.

### Parameters

**`$privacy_message`** _(string)_ The message to display above the Privacy Policy Page setting.

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.validate_privacy_options`

The Altis Privacy page saved options. If you create new options for the Privacy page, you must use this filter to add and validate that new option data.

### Parameters

**`$validated`** _(array)_ An array of validated data.  

**`$dirty`** _(array)_ An array of unvalidated data.

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.default_banner_message`

The default cookie consent banner message. This is the text that appears in the WordPress WYSIWYG editor on the Altis Privacy page by default, before it's been saved. 

### Parameters

**`$default_message`** _(string)_ The default cookie consent banner message.

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.allowed_policy_page_values`

An array of allowed policy pages we can create. Used by [`render_secondary_button function`](). The default pages that can be created are `privacy_policy` and `cookie_policy`.

If new policy pages should be created on a settings page using `render_secondary_button`, this filter must be used to allow those pages.

### Parameters

**`$allowed_policy_page_values`** _(array)_ An array of allowed policy page values.

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.use_block_editor`

Whether we are using the block editor.

This defaults to true, but if false, we omit the Gutenberg block support in the policy content.

### Parameters

**`$block_editor`** _(bool)_ True/false whether the site is using the block editor.

### Source

File: [`inc/settings.php`](https://github.com/humanmade/altis-consent/blob/master/inc/settings.php)

## `altis.consent.consent_banner_path`

The path to the consent banner template. Within the Altis Consent module, this template loads all the other templates. Overriding this setting allows you to create your own custom banner templates

### Parameters

**`$template_path`** The path to the consent banner template.

### Source

File: [`inc/functions.php`](https://github.com/humanmade/altis-consent/blob/master/inc/functions.php)

### Example
```php
// Override the default consent banner templates.
add_filter( 'altis.consent.consent_banner_path', __DIR__ . '/path/to/your/template.php' );
```

## `altis.consent.should_display_banner`

Whether to display the banner. This filter defaults to the value of the `display_banner` setting, or `false` if that's not set. Using this filter allows you to hijack the display of the banner based on external logic.

### Parameters

**`$display_banner`** _(bool)_ Whether to display the banner. Defaults to the stored `display_banner` value, or `false` if unset.

### Source

File: [`inc/functions.php`](https://github.com/humanmade/altis-consent/blob/master/inc/functions.php)

### Example
```php
add_filter( 'altis.consent.should_display_banner', function ( bool $display_banner ) : bool {
    // Don't display if the page ID is 10.
    if ( is_page( 10 ) ) {
        return false;
    }

    // Don't display on 404 pages.
    if ( is_404() ) {
        return false;
    }

    // Return whatever the expected, global result should be.
    return $display_banner;
} );
```

## `altis.consent.cookie_prefix`

The consent cookie prefix.

**Note:** The actual consent cookies will add an underscore (`_`) automatically between the prefix and the consent category.

### Parameters

**`$cookie_prefix`** _(string)_ The consent cookie prefix. Default is `altis_consent`.

### Example
```php
add_filter( 'altis.consent.cookie_prefix', function() {
    return 'some_other_prefix';
} );
```

## `altis.consent.types`

The allowed consent types.

### Parameters

**`$types`** _(array)_ The list of consent types. Defaults are `optin` and `optout`.

### Example
```php
add_filter( 'altis.consent.types', function( $types ) {
    $types[] = 'none';
    return $types;
} );

## `altis.consent.categories`

The allowed consent categories.

### Parameters

**`$categories`** _(array)_ The list of consent categories. Defaults are `functional`, `preferences`, `statistics`, `statistics-anonymous`, `marketing`.

### Example
```php
add_filter( 'altis.consent.categories', function( $categories ) {
    $categories[] = 'personalization';
    return $categories;
} );
```

## `altis.consent.values`

The possible consent values.

### Parameters

**`$values`** _(array)_ The list of consent values. Defaults are `allow`, `deny`.

### Example
```php
add_filter( 'altis.consent.values', function( $values ) {
    $values[] = 'other';
    return $values;
} );
```

## `altis.consent.cookie_policy_url`

The cookie policy page URL. Attempts to fetch from the saved option.

### Parameters

**`$cookie_policy_page_url`** _(string)_ The cookie policy page URL.

### Source

File: [`inc/functions.php`](https://github.com/humanmade/altis-consent/blob/master/inc/functions.php)

## `altis.consent.default_cookie_policy_content`

Filters the default content suggested for inclusion in a cookie policy.

### Parameters

**`$content`** _(string)_ The default policy content.

**`$strings`** _(array)_ An array of cookie policy content strings.

**`$blocks`** _(bool)_ Whether the content should be formatted for the block editor.

### Source

File: [`inc/cookie-policy.php`](https://github.com/humanmade/altis-consent/blob/master/inc/cookie-policy.php)

## `altis.consent.consent_updated_template_path`

The template that displays the "preferences updated" messaging after it's been saved.

### Parameters

**`$consent_updated_template_path`** _(string)_ The path to the consent updated template.

### Source

File: [`tmpl/consent-banner.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/consent-banner.php)

## `altis.consent.no_option_saved_message`

The message that displays in the `consent-banner.php` template if no consent options have been saved in the admin. This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)).

### Parameters

**`$no_option_saved_message`** _(string)_ The message to output when no consent option has been saved.

### Source

File: [`tmpl/consent-banner.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/consent-banner.php)

## `altis.consent.cookie_preferences_template_path`

The path to the cookie preferences template. Only displayed if banner options have been saved and the setting is not set to `none`.

### Parameters

**`$cookie_preferences_template_path`** _(string)_ The path to the cookie preferences template.

### Source

File: [`tmpl/consent-banner.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/consent-banner.php)

## `altis.consent.button_row_template_path`

The path to the template that includes the buttons and messaging of the banner.

### Source

File: [`tmpl/consent-banner.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/consent-banner.php)

### Parameters

**`$button_row_template_path`** _(string)_ The path to the button row template.

## `altis.consent.preferences_updated_message`

The message that displays when a user saves their cookie consent preference. This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)).

### Parameters

**`$preferences_updated_message`** The message to display when cookie preferences have been saved.

### Source

File: [`tmpl/consent-updated.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/consent-updated.php)

## `altis.consent.apply_cookie_preferences_button_text`

The button text for the Apply Changes button, when cookie category preferences are displayed. This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)) or if categories are not displayed as part of the cookie consent banner.

### Parameters

**`$apply_changes_button_text`** _(string)_ The button text to apply cookie preference changes.

### Source

File: [`tmpl/cookie-preferences.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/cookie-preferences.php)

## `altis.consent.cookie_consent_policy_link_text`

The hyperlinked message that links to the Cookie Policy page. Defaults to "Read our cookie policy". This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)).

### Parameters

**`$cookie_policy_link_text`** _(string)_ The text to link to the cookie policy page.

### Source

File: [`tmpl/cookie-consent-policy.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/cookie-consent-policy.php)

## `altis.consent.cookie_consent_policy_template_path`

The path to the cookie consent policy template. This template displays the link to the cookie policy page if a cookie policy page has been set in the Altis Privacy options.

### Parameters

**`$cookie_consent_policy_path`** _(string)_ The path to the cookie consent policy template.

### Source

File: [`tmpl/button-row.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/button-row.php)

## `altis.consent.accept_all_cookies_button_text`

The text to display in the "accept all cookies" button. This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)).

### Parameters

**`$accept_all_cookies_text`** _(string)_ The "accept all cookies" button text.

### Source

File: [`tmpl/button-row.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/button-row.php)

## `altis.consent.accept_only_functional_cookies_button_text`

The text to display in the "accept only functional cookies" button. This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)).

### Parameters

**`$accept_functional_cookies`** _(string)_ The "accept only functional cookies" button text.

### Source

File: [`tmpl/button-row.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/button-row.php)

## `altis.consent.cookie_preferences_button_text`

The text to display in the "cookie preferences" button. This message can potentially be overridden if using your own templates (see [`altis.consent.consent_banner_path`](#altisconsentconsent_banner_path)) or if cookie consent categories are not displayed.

### Parameters

**`$cookie_preferences_button_text`** _(string)_ The "cookie preferences" button text.

### Source

File: [`tmpl/button-row.php`](https://github.com/humanmade/altis-consent/blob/master/tmpl/button-row.php)