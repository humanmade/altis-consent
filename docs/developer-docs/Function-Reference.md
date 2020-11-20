**Note:** All the functions in the Altis Consent plugin use the `Altis\Consent` namespace except where noted.

## `load_consent_banner`

Loads the templates used to display the cookie consent banner. The path to the banner can be customized using the [`altis.consent.consent_banner_path`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentconsent_banner_path) filter.

* Uses [`load_template`](https://developer.wordpress.org/reference/functions/load_template/)
* See [`altis.consent.consent_banner_path`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentconsent_banner_path)

### Example
```php
function render_consent_banner() : string {
    ob_start();
    load_consent_banner();
    return ob_get_clean();
}
```

## `should_display_banner`

Determines whether the banner should be displayed. Uses the `display_banner` setting defined in the admin but can be filtered by using the [`altis.consent.should_display_banner`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentshould_display_banner) filter.

* Uses [`Settings\get_consent_option`](#settingsget_consent_option)
* See [`altis.consent.should_display_banner`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentshould_display_banner)

### Return

_(bool)_ Whether the banner should be displayed.

### Example
```php
function load_consent_banner() {
    // Check if we need to load the banner.
    if ( should_display_banner() ) {
            load_template( plugin_dir_path( __DIR__ ) . 'tmpl/consent-banner.php' );
    }
}
```

## `cookie_prefix`

Returns the default consent cookie prefix.

* See [`altis.consent.cookie_prefix`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentcookie_prefix)

### Return

_(string)_ The consent cookie prefix. Defaults to `altis_consent`.

### Example
```php
wp_localize_script( 'altis-consent', 'altisConsent', [
    'cookiePrefix' => cookie_prefix(),
] );
```

## `consent_types`

Returns the active consent types. 

* See [`altis.consent.types`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsenttypes)

### Return

_(array)_ The list of currently allowed consent types. Defaults are `optin` and `optout`.

### Example
```php
wp_localize_script( 'altis-consent', 'altisConsent', [
    'consentTypes' => consent_types(),
] );
```

## `consent_categories`

Returns a list of active consent categories.

* See [`altis.consent.categories`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentcategories)

### Return

_(array)_ The list of currently allowed consent categories. Defaults are `functional`, `preferences`, `statistics`, `statistics-anonymous`, and `marketing`.

### Example
```php
wp_localize_script( 'altis-consent', 'altisConsent', [
    'categories' => consent_categories(),
] );
```

## `consent_values`

Returns a list of active possible consent values.

* See [`altis.consent.values`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentvalues)

### Return

_(array)_ A list of possible consent values. Defaults are `allow` and `deny`.

### Example
```php
wp_localize_script( 'altis-consent', 'altisConsent', [
    'values' => consent_values(),
] );
```

## `validate_consent_item`

Validates a consent item (either a consent _type_, _category_ or _value_).

### Parameters

**`$item`** _(string)_ The value to validate.
**`$item_type`** _(string)_ The type of value to validate. Possible options are `types` (consent types, see [`consent_types`](#consent_types)), `categories` (consent categories, see [`consent_categories`](#consent_categories)), or `values` (consent values, see [`consent_values`](#consent_values)).

### Return

_(string|bool)_ The validated string or `false` if unable to validate. Triggers a warning if either the `$item_type` or the `$item` is invalid.

### Example
```php
if ( ! Consent\validate_consent_item( $category, 'category' ) ) {
	// Do something.
}
```

## `get_cookie_policy_url`

Retrieves the URL to the cookie policy page. Can be filtered by the [`altis.consent.cookie_policy_url`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentcookie_policy_url) filter.

* Uses [`Settings\get_consent_option`](#settingsget_consent_option)
* Uses [`get_post_type`](http://developer.wordpress.org/reference/functions/get_post_type/)
* Uses [`get_page_uri`](http://developer.wordpress.org/reference/functions/get_page_uri/)

### Return

_(string)_ The cookie policy page URL.

### Example
```php
<div class="cookie-consent-policy">
    <a href="<?php echo esc_url( Altis\Consent\get_cookie_policy_url() ); ?>">
        <?php esc_html__( 'Read our cookie policy', 'altis-consent' ) ); ?>
    </a>
</div>
```

## `Settings\get_consent_option`

**Note:** Defined in the `Altis\Consent\Settings` namespace.

Get a specific consent option, if one exists. If no parameters are passed, returns all the saved consent option values.

* Uses [`get_option`](https://developer.wordpress.org/reference/functions/get_option/)

### Parameters

**`$option`** _(mixed)_ (Optional) A consent option name. The option must exist in the `cookie_consent_options` group. Default is an empty string. If no value is passed, all the saved `cookie_consent_options` option values will be returned.

**`$default`** _(mixed)_ (Optional) A default value to return if no option for that value has been set. Default is an empty string. Requires an `$option` parameter to be passed.

### Return

_(mixed)_ The value for the requested option, or an array of all `cookie_consent_options` if nothing was passed.

### Example
```php
$cookie_expiration = Altis\Consent\Settings\get_consent_option( 'cookie_expiration', 30 );
```

## `Settings\get_default_banner_message`

**Note:** Defined in the `Altis\Consent\Settings` namespace.

Gets the default banner message. Filterable with the [`altis.consent.default_banner_message`](https://github.com/humanmade/altis-consent/wiki/Altis-Consent-Filter-Reference#altisconsentdefault_banner_message) filter.

### Return

_(string)_ The default cookie consent banner message.

### Example
```php
use Altis\Consent\Settings;

if ( ! Settings\get_consent_option( 'banner_message' ) ) {
    echo wp_kses_post( Settings\get_default_banner_message() );
}
```

## `Settings\render_secondary_button`

**Note:** Defined in the Altis\Consent\Settings namespace.

Display a secondary button.

Used to create the Create Policy Page buttons, but can be filtered and used for other things.

### Parameters

**`$button_text`** _(string)_ The text to display in the button.

**`$value`** _(string)_ The button value. On the settings page, this is used to determine the type of policy page the buttons create.

**`$type`** _(string)_ The html button type. The default value is `'submit'`, and valid values are `'submit'`, `'reset'`, and `'button'`. Invalid values revert to `'submit'`.

### Example
```php
Settings\render_secondary_button( __( 'Create Cookie Policy Page', 'altis-consent' ), 'cookie_policy' );
```