# Tools
Třída s několika pomocnými funkcemi.

# Deployment

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require godsdev/tools:dev-develop
```

Then, be sure to include the autoloader:

```php
require_once '/path/to/your-project/vendor/autoload.php';
```

Finally, if you need to use `Tools::method` statement you MUST add
```php
use \GodsDev\Tools\Tools;
```
operator into the **file** where the `Tools::method` statement is to be used.

# konfigurace

# administrace

# Continuous integration

# Testing

# Methods

@todo name only public methods:

* h, set, ifemtpy, ifnull, equal, nonempty, nonzero, setifnull, setifempty, wrap, among
* showMessages, addMessage
* htmlOption, htmlSelect, htmlSelectAppend, htmlRadio, htmlTextarea, htmlInput, htmlTextInput
* webalize, safeIn, safeJs, redir
* arrayListed, exploded, cutTill
* curlCall
* set, equal, nonempty, nonzero, begins, ends
* escapeSQL, escapeJS
* arrayConfineKeys, arrayReindex, arrayRemoveItems, arrayKeyAsValues
* urlChange
* relativeTime, localeDate, localeTime
* plural, resolve
* randomPassword
