<p align="left"><a href="https://www.itsecurity.id/"><img height="150" title="Xcod3bughunt3r" src="face.png"/></a></p>

# `Xcod3 - EasyCSRF`
EasyCSRF is a simple, standalone CSRF protection library written in PHP. It can be used to
protect your forms from [Cross Site Request Forgery](http://en.wikipedia.org/wiki/Cross-site_request_forgery) attacks.

## Requirements
* PHP 7.3+

## Install
Anda juga bisa menginstallnya melalui composer.
```php
require 'vendor/autoload.php';
$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
```

## Usage
To use EasyCSRF first you need to generate a token:
```php
$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
$token = $easyCSRF->generate('my_token');
```

You then include this token with any forms you create:

```html
<form>
    ...
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    ...
</form>
```

Then before you do any data processing, you check the token is valid:

```php
use EasyCSRF\Exceptions\InvalidCsrfTokenException;

try {
    $easyCSRF->check('my_token', $_POST['token']);
} catch(InvalidCsrfTokenException $e) {
    echo $e->getMessage();
}
```

## Token Expiration
You can set a time limit on tokens by passing a timespan (in seconds) to the check method. Tokens older than the timespan will not be valid.
```php
// Example 1 hour expiration
$easyCSRF->check('my_token', $_POST['token'], 60 * 60);
```

## Reusable Tokens
Tokens can be made reusable and not one-time only (useful for ajax-heavy requests).
```php
// Make token reusable
$easyCSRF->check('my_token', $_POST['token'], null, true);
```

## Custom SessionProvider
Your app might use a third party library for managing sessions, or you may want to store tokens somewhere other
than $_SESSION (as the `NativeSessionProvider` does). In this case you can create a custom `SessionProvider`
and use that when instantiating EasyCSRF.

```php
<?php
use EasyCSRF\Interfaces\SessionProvider;
class CustomSessionProvider implements SessionProvider
{
    /**
     * Get a session value.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        // Return your stored data
    }
    /**
     * Set a session value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        // Store your data
    }
}
```

```php
$sessionProvider = new CustomSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
```

## `EasyCSRF was created by` *[Xcod3bughunt3r](https://github.com/Xcod3bughunt3r/Xcod3bughunt3r)*
#### ``MIT`` *[LICENSE](LICENSE)*
