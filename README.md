# Tools
A class with useful all-purpose functions (methods). The methods are static; thus usage is `Tools::method()`.
Some methods for HTML-output adopt classes used in the `Bootstrap` library.

# Deployment

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require godsdev/tools:dev-develop
```

The `composer.json` file should then contain current version of the class - something similar to:
```json
{
    ...,
    "require": {
        ...,
        "godsdev/tools": "^0.3.3",
        ...
    },
    ...
}
```

Then be sure to include the autoloader:

```php
require_once '/path/to/your-project/vendor/autoload.php';
```

Finally, include this line
```php
use \GodsDev\Tools\Tools;
```
to the **file** where you want to use Tools' methods. Then you can address all its function like this - `Tools::method()`.

## Compatibility
* PHP 5.6+, 7+
* several string-processing functions call `mb_XXXX` functions that require the `ext-mbstring` extension
* `relativeTime()` creates `new DateTime()`
* `stripAttributes()` uses `libxml` and creates `new DOMDocument()`, `new DOMXPath()`
* `str_putcsv()` opens the `"php://memory"` stream
* `curlCall()` uses cURL extension
* `webalize()` tests for `ext-iconv` extension and `iconv()` function
Causion:
* `escapeSQL()` is obsolete and should not be used

### PHP Extensions
The `"require"` item in `composer.json` should really be:
```json
"require": {
    "php": "^5.6 || ^7.0",
    "ext-curl": "*",
    "ext-date": "*",
    "ext-dom": "*",
    "ext-iconv": "*",
    "ext-json": "*",
    "ext-mbstring": "*",
    "ext-pcre": "*",
    "ext-session": "*",
    "ext-SimpleXML": "*"
},
...
```
But since not all methods are used by every project, all those requirements are not stated there.
This might trigger some error messages in testing. See chapter *Troubleshooting* for more.

# Configuration
## Class Constants
* The `$LOCALE[]` array contains configuration for locale date/time formats. Example for Czech language:
```php
Tools::LOCALE['cs'] => [
    'date format' => 'j. F',
    'full date format' => 'j. F Y',
    'time format' => 'H:i:s',
    'weekdays' => [
        'Sunday' => 'neděle',
        'Monday' => 'pondělí',
        'Tuesday' => 'úterý',
        'Wednesday' => 'středa',
        'Thursday' => 'čtvrtek',
        'Friday' => 'pátek',
        'Saturday' => 'sobota'
    ],
    'months' => [
        'January' => 'leden',
        'February' => 'únor',
        'March' => 'březen',
        'April' => 'duben',
        'May' => 'květen',
        'June' => 'červen',
        'July' => 'červenec',
        'August' => 'srpen',
        'September' => 'září',
        'October' => 'říjen',
        'November' => 'listopad',
        'December' => 'prosinec'
    ],
    'time ago' => [
        'y' => ['rok', 'roky', 'let'], //enclination for "1 year" form, "2-4 years" form, and "5+ years" form
        'm' => ['měsíc', 'měsíce', 'měsíců'],
        'd' => ['den', 'dny', 'dnů'],
        'h' => ['hodina', 'hodiny', 'hodin'],
        'i' => ['minuta', 'minuty', 'minut'],
        's' => ['vteřina', 'vteřiny', 'vteřin'],
        'ago' => 'zpátky',
        'in' => 'za',
        'moment' => 'okamžik'
    ]
];
```
* The `$MESSAGE_ICONS` array contains (HTML-coded) icons that accompany each type of message. The keys (and the message types) are: `success`, `danger`, `warning` and `info`.
* The `$PASSWORD_CHARS` constant is a string containing "safe" characters to generate password. It is used by the `randomPassword()` method.

# Administration
...
# Continuous Integration
...
# Testing

Testing is implemented using `phpunit` in projects folder `vendor/phpunit/phpunit`. The testing class is in `test/ToolsTest.php` (methods there are tested in alphabetical order). If you add a new method, don't forget to add its testing to `ToolsTest.php` and run:

```sh
vendor/phpunit/phpunit/phpunit
```

*Note: The `redir()` method (which performs HTTP redirection) is not included in unit testing.*

## Troubleshooting
After running `phpunit` you might get error messages saying that certain PHP extension is not available. (See chapter *PHP Extensions* for more). If your project does not require said extension(s), it will run without error messages of this kind. If it does, it's up to You to provide enabling of this/these extension(s).

# Methods
Note: some methods are just shortcuts (wraps) of standard php's functions with fixed parameters (e.g. `h()`).

Variable testing and setting
* `set`, `ifemtpy`, `ifnull`, `equal`, `nonempty`, `nonzero`, `setifnull`, `setifempty`, `setarray`, `setscalar`, `anyset`

HTML output
* `h`, `htmlOption`, `htmlSelect`, `htmlSelectAppend`, `htmlRadio`, `htmlTextarea`, `htmlInput`, `htmlTextInput`
* `stripAttributes`
* `dump`

HTTP
* `redir`, `curlCall`, `urlChange`
* `httpResponse`

Messages
* `showMessages`, `addMessage`, `outputMessage`, `resolve`

Strings
* `begins`, `ends`, `exploded`, `cutTill`, `str_before`, `str_after`
* `randomPassword`
* `mb_ucfirst`, `mb_lcfirst`
* `str_putcsv`
* `str_before`, `str_after`

Conversion
* `escapeSQL`, `escapeJS`, `escapeDbIdentifier`, `escapeIn`
* `webalize`, `shortify`
* `xorCipher`, `xorDecipher`

Variables
* `among`
* `whitelist`, `blacklist`

Arrays
* `arrayConfineKeys`, `arrayReindex`, `arrayRemoveItems`, `arrayKeyAsValues`, `arrayListed`, `arraySearchAssoc`
* `array_search_i`, `in_array_i`

Locale
* `relativeTime`, `localeDate`, `localeTime`
* `plural`

Specific
* `preg_max`
* `GoogleAuthenticatorCode`
* `columnName`
