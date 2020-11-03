# Tools 
A class with useful all-purpose functions (methods). The methods are static.
Some methods for HTML-output adopt classes used in the [Bootstrap](http://getbootstrap.com) library.

[![Total Downloads](https://img.shields.io/packagist/dt/godsdev/tools.svg)](https://packagist.org/packages/godsdev/tools)
[![Latest Stable Version](https://img.shields.io/packagist/v/godsdev/tools.svg)](https://packagist.org/packages/godsdev/tools)
[![Lint Code Base](https://github.com/GodsDev/tools/workflows/Lint%20Code%20Base/badge.svg)](https://github.com/GodsDev/tools/actions?query=workflow%3A%22Lint+Code+Base%22)
[![PHP Composer + PHPUnit](https://github.com/GodsDev/tools/workflows/PHP%20Composer%20+%20PHPUnit/badge.svg)](https://github.com/GodsDev/tools/actions?query=workflow%3A%22PHP+Composer+%2B+PHPUnit%22)

## Deployment

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require godsdev/tools:dev-develop
```

The `composer.json` file should then contain current version of the class - something similar to:
```json
{
    "require": {
        "godsdev/tools": "^0.3.7"
    }
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

### Notes
* Methods handling messages use session (`$_SESSION["messages"]` variable) for storing messages.
* `escapeSQL()` is obsolete and should not be used
* `escapeIn()` and `escapeDbIdentifier()` are specific to MySQL/MariaDb DBMS.

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
}
```
But since not all methods are used by every project, all those requirements are not stated there.
This might trigger some error messages in testing. See chapter *Troubleshooting* for more.

## Configuration

### Class Constants
* The `::$LOCALE[]` array contains configuration for locale date/time formats. Example for Czech language:
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
* The `::$MESSAGE_ICONS` array contains (HTML-coded) icons that accompany each type of message. The keys (and the message types) are: `success`, `danger`, `warning` and `info`.
* The `::$PASSWORD_CHARS` constant is a string containing characters from which to generate passwords. Used by `randomPassword()`.

## Testing

Testing is implemented using `phpunit` in projects folder `vendor/phpunit/phpunit`. The testing class is in `test/ToolsTest.php` (methods there are tested in alphabetical order). If you add a new method, don't forget to add its testing to `ToolsTest.php` and run:

```sh
vendor/phpunit/phpunit/phpunit
```

* Note: The `redir()` method (which performs HTTP redirection) is not included in unit testing.*
* Note: PHP included in ubuntu-latest (for GitHub Actions) does not support iconv //TRANSLIT flag as iconv implementation is unknown, therefore PHPUnit group iconvtranslit is excluded

### Troubleshooting
After running `phpunit` you might get error messages saying that certain PHP extension is not available. (See chapter *PHP Extensions* for more). If your project does not require said extension(s), it will run without error messages of this kind. If it does, it's up to You to provide enabling of this/these extension(s).

## Methods

Variable testing and setting
* `anyset()` – is any of given variables set?
* `equal()` – comparison with `isset()`
* `ifempty()` – `empty()` with `isset()`
* `ifnull()` – `isnull()` with `isset()`
* `nonempty()` – `!empty()` with `isset()`
* `nonzero()` – non-zero test with `isset()`
* `set()` – test for `isset()` or set value to a variable
* `setarray()` – shortcut for `isset()` and `is_array()`
* `setifempty()` – shortcut for if `isset()` and `empty()` then set
* `setifnotset()` – shortcut for if `!isset()` then set
* `setifnull()` – shortcut for if `isset()` and `is_null()` then set
* `setscalar()` – shortcut for `isset()` and `is_scalar()`

HTML output
* `dump()` – shortcut for `var_dump()` in `<pre>`...`</pre>`
* `h()` – shortcut for `htmlspecialchars()` in UTF-8
* `htmlInput()` – output `<input>` of given type
* `htmlOption()` – output `<option>`
* `htmlRadio()` – output `<input type="radio">`
* `htmlSelect()` – output `<select>` with given options
* `htmlTextarea()` – output `<textarea>`
* `htmlTextInput()` – output `<input type="text">`
* `stripAttributes()` – strip attributes off a HTML code

HTTP
* `curlCall()` – call a URL, return result
* `httpResponse()` – HTTP response split into headers and body
* `redir()` – make an HTTP redirection
* `urlChange()` – add/delete/modify GET variables of an URL

Messages
* `addMessage()` – add a message to a session
* `outputMessage()` – output a message
* `resolve()` – add either a 'success' or 'error' message based on a result
* `showMessages()` – show messages from session

Strings
* `begins()` – does a string begin with given parameter?
* `cutTill()` – cut string to first occurence of given parameter
* `ends()` –  does a string end with given parameter?
* `exploded()` – `explode()` and return item of given index
* `mb_lcfirst()` – lower case first character (multi-byte version)
* `mb_ucfirst()` – upper case first character (multi-byte version)
* `randomPassword()` – return random password
* `str_after()` – return a part of a string after occurence of a parameter
* `str_before()` – return a part of a string before occurence of a parameter
* `str_delete()` – delete part of a byref variable
* `str_putcsv()` – inverse to `str_getcsv()`

Conversion
* `columnName()` – convert number to 26-base (alphabetical) system
* `escapeDbIdentifier()` – escape function for MySQL/MariaDb identifiers
* `escapeIn()` – escape values in SQL's `IN()` clause
* `escapeJS()` – escape for JavaScript
* `escapeSQL()` – basic escape for SQL (OBSOLETE)
* `shortify()` – limit long string to given length, add optional ellipsis
* `webalize()` – covert to URL-friendly string
* `xorCipher()` – basic ciphering
* `xorDecipher()` – basic deciphering

Variables
* `among()` – is given value among listed values
* `blacklist()` – set value to given default if on blacklist
* `whitelist()` – set value to given default if not on whitelist

Arrays
* `array_search_i()` – case-insensitive `array_search()`
* `arrayConfineKeys()` – extract and return selected keys of given array
* `arrayKeyAsValues()` – refill array's values by its keys
* `arrayListed()` – output list of array's items
* `arrayReindex()` – reindex array of arrays by given index
* `arrayRemoveItems()` – remove items from an array
* `arraySearchAssoc()` – search array for given key:value pair(s)
* `in_array_i()` – case-insensitive `in_array()`

Locale
* `localeDate()` – output date using ::`$LOCALE[]` settings
* `localeTime()` – output time using ::`$LOCALE[]` settings
* `plural()` – output appropriate singular/plural version of given word
* `relativeTime()` – output relative from `now()` using ::`$LOCALE[]` settings

Specific
* `GoogleAuthenticatorCode()` – GoogleAuthenticator hash
* `preg_max()` – return RegEx mask to match integer range from 0 to given maximum
