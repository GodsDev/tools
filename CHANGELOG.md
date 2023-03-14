# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### `Added` for new features

### `Changed` for changes in existing functionality

### `Deprecated` for soon-to-be removed features

### `Removed` for now removed features

### `Fixed` for any bugfixes
- automatic tests on GitHub (now based on MyCMS reusable tests)

### `Security` in case of vulnerabilities

## [0.3.8] - 2021-04-11
Precise type hints, Throw \Exception if error

Fixed:
* PHPSTAN level max shows Error Zero for PHP versions '7.1', '7.2', '7.3', '7.4' (i.e. not the PHPStan in super-linter but installed for each PHP version)
    * All method parmeters and return have proper type hint
    * Typecasting to (int) to avoid `false` possible return in case of error
* Let's PHPUnit for all following PHP versions '5.6', '7.0', '7.1', '7.2', '7.3', '7.4'
* Xdebug turned off as in PHP 5.6,7.0 it would change (format) var_dump output
* phpstan-ignore-next-line where relevant

* Tools::begins() int casting to fix Parameter 3 $length of function mb_substr expects int|null, int|false given.
* Tools::colorChange() int casting to fix Parameter 1 $num of function dechex expects int, float|int given.
* Tools::exploded() fix explode: Cannot access offset int on array<int, string>|false.
* Tools::GoogleAuthenticatorCode() throws \Exception if unpack fails
* Tools::htmlSelect() etc. typehint: default may also be bool, so it is mixed not int|string + refactoring
* Tools::localeDate() & Tools::localeTime() throws \Exception if Parameter 1 $datetime has unreasonable type + refactoring
* Tools::plural() should return string but returns string|true; added working option for amount equal to zero
* Tools::preg_max() should return string but returned int|string. Fix by properly adding RegEx for 0
* Tools::redir()
    * $urlString parameter 1 handles port
    * new $sessionWriteClose parameter 3 enables turning off `session_write_close();` if session info needs to be passed on to next page, so that e.g. Tracy gets info about redirect.
    * throws \Exception when URL is seriously malformed and deobfuscation `self::set($url['path']) -> (isset($url['path']) && $url['path'])` so that PHPSTAN understands it
* Tools::stripAttributes() (fix by throwing Exceptions when error occurs):
    * Argument of an invalid type DOMNodeList|false supplied for foreach, only iterables are supported.
    * Cannot access property $length on DOMNamedNodeMap|null.
* Tools::str_putcsv() throws \Exception if fopen, ftell or fread fail to work wiht php://memory
* Method Tools::webalize() (fix by type casting or throwing \Exception when error occurs):
    * Parameter 1 $str of function strtr expects string, string|false given.
    * Parameter 3 $subject of function str_replace expects array|string, string|false given.
    * Parameter 1 $str of function preg_quote expects string, string|null given.
* Tools::xorCipher & Tools::xorDecipher - typecasting float to int as proper argument for str_repeat()

## [0.3.7] - 2020-11-04
Parameter type fixes
      * by reference arguments should be in PHPDoc without `&` prefix
* Tools::showMessages @return string of session messages or empty string if $echo == false (returning void was an error as it wouldn't be possible to print out the potential result)
* Tools::colorChange - proper argument types
* Tools::stripAttributes - Parameters of function rand expects int, float given.
* Tools::preg_max: Parameter #1 $string of function strlen expects string, int given. Parameter #1 $str of function substr expects string, int given. (ToolsTest.php: Parameter #2 $subject of function preg_match expects string, int given.)
* Tools::str_after,str_before: Change PHPDoc @return statement as the result can be string or false if $needle wasn't found
* Parameter #1 $prefix of function uniqid expects string, int given.
* Tools::htmlRadio $value parameter can be not just scalar but also array
* Tools::htmlSelect $default doesn't have to be a string
* Tools::plural: Default value of the parameter #5 $form0 (false) is incompatible with type string.
* Tools::preg_max variable $digit is defined as int

Code governance
* phpstan.neon (local and github) settings for PHPStan
* PHPDoc: Tools::webalize has a hidden feature: #3 parameter equals -1, then webalize to UPPER-CASE
* removed @ in front of inconv to unhide potential issues
* michaelw90/PHP-Lint@master moved to composer test yml so that ubuntu-latest is built once instead of twice
* PHPUnit tests added to composer test yml
* PHPUnit tests displays alse E_NOTICE
* PHP included in ubuntu-latest does not support iconv //TRANSLIT flag as iconv implementation is unknown, therefore PHPUnit group iconvtranslit is excluded (webalize, htmlInput that uses webalize) for testing at GitHub Actions environment
* Tools::wrap refactoring
* Tools::arraySearchAssoc: There is no need to test for arguments being of array type, as arguments are declared as array: PHP end with a `Catchable fatal error` in case of other type
* TestTools.php: not necessary to unset a variable at the beginning of scope
* TestTools.php: fix Method name "ToolsTest::testAll_A_E" is not in camel caps format
* composer.json: license=proprietary added; link to issues
* comments: todo OR ignore phpstan warning
* badges: packagist, Lint Code Base passing, PHP Composer + PHPUnit passing

Code style
* Markdown fix and 120 character on one line limit

## [0.3.6] - 2020-05-02
* umožněn update na PHPUnit 5.*
      * test related classes moved to autoload-dev section in order not to load them when used as library
* relax the PHPUnit test of the relativeTime method to work with PHP/7.3
Also after 0.3.5
* stripAttributes() case for * attributes
* str_delete() - simplify the $length parameter to only integer or null
* public static function colorChange incl. PHPUnit test
* `=== null` instead of `is_null()`

## [0.3.5] - 2019-01-25
- TODO describe

## [0.3.4] - 2019-01-25
- TODO describe

## [0.3.3] - 2019-01-25
::anyset() now uses "..." clause (PHP 5.6+)
      phpDocs
use of $MESSAGE_ICONS (in ::showMessages()), $CURL_OPTIONS (in ::curlCall()), $PASSWORD_CHARS (in ::randomPassword()) now as the class' public variables
::relativeTime() now converts $datetime if provided as int
::stripAttributes() a bit better final extraction of code
removed trailing spaces and whitespace

## [0.3.2] - 2019-01-01
- TODO describe

## [0.3.1] - 2018-09-19
- fix use of undefined constant

## [0.3.0] - 2018-09-19
* PHP >= 5.3.3 to PHP >= 5.6
      * PHPUnit test
* PHPDoc added
* removed method escapeSQL (security risk)
* new method anyset
* new method GoogleAuthenticatorCode
* new method str_putcsv
* new methods str_before, str_after
* new method mb_ucfirst
* new methods array_search_i, in_array_i
* new methods whitelist, blacklist
* new method columnName
* new method httpResponse

## [0.2.5] - 2018-08-20
- TODO describe

## [0.2.4] - 2018-02-04
- fix absence of $_SERVER['QUERY_STRING'] in PHPUnit CLI environment

## [0.2.3] - 2017-12-12
- fix methods addMessage, ends
- change method showMessage - clickable icon
- change methods set, among, begins, ends, h, equal, nonempty, nonzero
- new methods stripAttributes, setAndEqual, ifset, setifnotset, setscalar, setarray

## [0.2.2] - 2017-11-07
- +shortify() etc.

## [0.2.1] - 2017-09-09
- fix: namespace re-added

## [0.2.0] - 2017-09-09
Recomapp release

### Added
- set, equal, nonempty, nonzero, begins, ends
- escapeSQL, escapeJS
- arrayConfineKeys, arrayReindex, arrayRemoveItems, arrayKeyAsValues
- urlChange
- relativeTime, localeDate, localeTime
- plural, resolve
- randomPassword

## [0.1.0] - 2017-09-09
First methods

### Added
- h, ifemtpy, ifnull, setifnull, setifempty, wrap, among
- showMessages, addMessage
- htmlOption, htmlSelect, htmlSelectAppend, htmlRadio, htmlTextarea, htmlInput, htmlTextInput
- webalize, safeIn, safeJs, redir
- arrayListed, exploded, cutTill
- curlCall

[Unreleased]: https://github.com/GodsDev/tools/compare/v0.3.8...HEAD
[0.3.8]: https://github.com/GodsDev/tools/compare/v0.3.7...0.3.8
[0.3.7]: https://github.com/GodsDev/tools/compare/v0.3.6...0.3.7
[0.3.6]: https://github.com/GodsDev/tools/compare/v0.3.5...0.3.6
[0.3.5]: https://github.com/GodsDev/tools/compare/v0.3.4...0.3.5
[0.3.4]: https://github.com/GodsDev/tools/compare/v0.3.3...0.3.4
[0.3.3]: https://github.com/GodsDev/tools/compare/v0.3.2...0.3.3
[0.3.2]: https://github.com/GodsDev/tools/compare/v0.3.1...0.3.2
[0.3.1]: https://github.com/GodsDev/tools/compare/v0.3.0...0.3.1
[0.3.0]: https://github.com/GodsDev/tools/compare/v0.2.5...0.3.0
[0.2.5]: https://github.com/GodsDev/tools/compare/v0.2.4...0.2.5
[0.2.4]: https://github.com/GodsDev/tools/compare/v0.2.3...0.2.4
[0.2.3]: https://github.com/GodsDev/tools/compare/v0.2.2...0.2.3
[0.2.2]: https://github.com/GodsDev/tools/compare/v0.2.1...0.2.2
[0.2.1]: https://github.com/GodsDev/tools/compare/v0.2.0...0.2.1
[0.2.0]: https://github.com/GodsDev/tools/compare/v0.1.0...0.2.0
[0.1.0]: https://github.com/GodsDev/tools/releases/tag/v0.1.0
