## `wp_listen_for_consent_change`

Event to listen for consent changes.

### Example

```js
document.addEventListener( 'wp_listen_for_consent_change', function ( e ) {
  var changedConsentCategory = e.detail;
  for ( var key in changedConsentCategory ) {
    if ( e.detail.marketing && e.detail.marketing === 'allow' ) {
      if ( key === 'marketing' && changedConsentCategory[key] === 'allow' ) {
        console.log( 'just given consent, track user data' )
      }
    }
  }
});
```

**Note:** All Altis Consent JavaScript functions are in the `Altis.Consent` namespace.

## `Altis.Consent.has`

Check if a user has given consent for a specific category.

### Parameters

**`category`** _(string)_ The category to check consent against.

### Return

_(bool)_ Whether the user has given consent for the selected category.

## `Altis.Consent.set`

Set a new consent category value.

### Parameters

**`category`** _(string)_ The consent category to update. Must be a valid consent category.

**`value`** _(string)_ The value to update the consent category to. Must be a valid value.

## `Altis.Consent.setCookie`

Set a cookie by consent type.

### Parameters

**`name`** The cookie name to set.

**`value`** The cookie value to set.

## `Altis.Consent.getCookie`

Retrieve a cookie by name.

### Parameters

**`name`** The name of the cookie to get data from.

### Return

_(string)_ The cookie data or an empty string.

## `Altis.Consent.cookieSaved`

Check if a consent cookie has been saved on the client machine.

### Return

_(bool)_ True if consent has been given previously, false if consent has not yet been given.

## `Altis.Consent.getCategories`

Retrieve an array of all the categories that a user has consented to.

### Return

_(array)_ An array of allowed cookie categories.
