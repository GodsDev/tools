<?php
/**
 * A class with additional miscelaneous, general-purpose methods.
 *
 * Compatibility notes:
 * PHP 5.6+
 * ::relativeTime() creates new DateTime()
 * ::stripAttributes() creates new DOMDocument(), new DOMXPath() and uses libxml
 * ::str_putcsv() opens the "php://memory" stream
 */

namespace GodsDev\Tools;

class Tools
{
    /** @const constants used in ::arrayListed() */
    const ARRL_HTML = 1;
    const ARRL_ESC = 2;
    const ARRL_JS = 4;
    const ARRL_INT = 8;
    const ARRL_FLOAT = 16;
    const ARRL_EMPTY = 32;
    const ARRL_DB_ID = 64;
    const ARRL_KEYS = 128;
    const ARRL_PATTERN = 256;
    const ARRL_LIKE = self::ARRL_ESC | self::ARRL_INT;
    const ARRL_PREGQ = self::ARRL_ESC | self::ARRL_FLOAT;

    /** var array locale settings used in ::localeDate(), ::localeTime(), ::relativeTime() */
    static public $LOCALE = [
        'en' => [
            'date format' => 'jS F',
            'full date format' => 'jS F Y',
            'time format' => 'H:i:s',
            'time ago' => [
                'y' => ['year', 'years', 'years'],
                'm' => ['month', 'months', 'months'],
                'd' => ['day', 'days', 'days'],
                'h' => ['hour', 'hours', 'hours'],
                'i' => ['minute', 'minutes', 'minutes'],
                's' => ['second', 'seconds', 'seconds'],
                'ago' => 'ago',
                'in' => 'in',
                'moment' => 'a moment'
            ]
        ],
        'cs' => [
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
                'y' => ['rok', 'roky', 'let'],
                'm' => ['měsíc', 'měsíce', 'měsíců'],
                'd' => ['den', 'dny', 'dnů'],
                'h' => ['hodina', 'hodiny', 'hodin'],
                'i' => ['minuta', 'minuty', 'minut'],
                's' => ['vteřina', 'vteřiny', 'vteřin'],
                'ago' => 'zpátky',
                'in' => 'za',
                'moment' => 'okamžik'
            ]
        ]
    ];

    /** @var icons for each type of session messages used in ::showMessages() */
    static public $MESSAGE_ICONS = [
        'success' => '<i class="fa fa-check-circle mr-1"></i>',
        'danger' => '<i class="fa fa-times-circle mr-1"></i>',
        'warning' => '<i class="fa fa-exclamation-circle mr-1"></i>',
        'info' => '<i class="fa fa-info-circle mr-1"></i>'
    ];

    /** @var default options for ::curlCall() */
    static public $CURL_OPTIONS = [
        CURLOPT_HEADER => false,
        /* cannot be activated when in safe_mode or an open_basedir is set
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_AUTOREFERER => true,
         */
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false
    ];

    /** @var characters used in ::randomPassword() */
    static public $PASSWORD_CHARS = '-23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

    /**
     * Add or concatenate $delta to given $variable. If the variable is not set, set it.
     *
     * @param mixed &$variable
     * @param mixed $delta = 1, what to add. []= used for $variable being an array, otherwise += used for numeric $delta and .= otherwise
     * @return mixed variable after addition
     */
    public static function add(&$variable, $delta = 1)
    {
        if (isset($variable) && is_array($variable)) {
            $variable []= $delta;
        } elseif (is_numeric($delta)) {
            $variable = (isset($variable) ? $variable : 0) + $delta;
        } else {
            $variable = (isset($variable) ? $variable : '') . $delta; //$delta == true becomes '1'; false and null become ''
        }
        return $variable;
    }

    /**
     * Add a session message (e.g. a result of an data-changing operation).
     *
     * @param mixed $type type one of 'info', 'danger' (or 'error'), 'success' (or true), 'warning' (or false)
     * @param string $message message itself, in well-formatted HTML
     * @param bool $show (optional) true --> then call showMessages()
     * @return void
     */
    public static function addMessage($type, $message, $show = false)
    {
        $_SESSION['messages'] = self::setarray($_SESSION['messages']) ? $_SESSION['messages'] : [];
        $_SESSION['messages'] []= [is_bool($type) ? ($type ? 'success' : 'warning') : ($type == 'error' ? 'danger' : $type), $message];
        if ($show) {
            self::showMessages();
        }
    }

    /**
     * Return true if $n is among given parameters (more than one can be given).
     *
     * @param mixed $n value tested
     * @param mixed $m option(s)
     * @return bool
     */
    public static function among($n, $m)
    {
        $args = func_get_args();
        array_shift($args);
        return in_array($n, $args, true); // strict comparison
    }

    /**
     * Return true if any of given argument variables are set (ie. pass isset()).
     * To test if all arguments are set, simply use isset($var1, $var2, ...).
     *
     * @example Tools::anyset($_GET['article'], $_GET['category'])
     * @param mixed &$n variable(s)
     * @return bool true if any (at least one) variable pass isset(), false otherwise
     */
    public static function anyset(&...$args)
    {
        foreach ($args as $arg) {
            if (isset($arg)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Walk through given array and extract only selected keys.
     * @example $employees = [[name=>John, surname=>Doe, age=>43], [name=>Lucy, surname=>Smith, age=>28]]
     *          Tools::arrayConfineKeys($employees, 'age') --> [['age'=>43], ['age'=>28]]
     *
     * @param mixed[] $array Array to walk through
     * @param mixed $keys key or array of keys to extract
     * @return array
     */
    public static function arrayConfineKeys($array, $keys)
    {
        $keys = is_array($keys) ? $keys : [$keys];
        $result = [];
        if (is_array($array)) {
            foreach ($array as $arrayKey => $item) {
                $tmp = [];
                foreach ($keys as $key) {
                    if (isset($item[$key])) {
                        $tmp[$key] = $item[$key];
                    }
                }
                if ($tmp) {
                    $result[$arrayKey] = $tmp;
                }
            }
        }
        return $result;
    }

    /**
     * Return an array with keys same as its values. Doesn't solve duplicates.
     *
     * @example arrayKeysAsValues(['Apple', 'Pear', 'Kiwi']) --> ['Apple'=>'Apple', 'Pear'=>'Pear', 'Kiwi'=>'Kiwi']
     *
     * @param mixed[] $array
     * @return mixed[]
     */
    public static function arrayKeyAsValues($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$value] = $value;
        }
        return $result;
    }

    /**
     * Like implode() but with more options.
     *
     * @example: Tools::arrayListed(["Levi's", "Procter & Gamble"], 1, ", ", "<b>", "</b>") --> <b>Levi's</b>, <b>Procter &amp; Gamble</b>
     *
     * @param mixed[] $array
     * @param int $flags (optional) set of the following bits
     *      +1 = htmlentities
     *      +2 = escape string
     *      +4 = escapeJs
     *      +8 = intval
     *      +16 = (float)
     *      +32 = ignore empty
     *      +64 = ` -> ``
     *      +128 = operate with array_keys
     *      +256 = replace mode - $before becomes a pattern, $after becomes a symbol to replace
     *      special combinations: +2+8 = quote LIKE, +2+16 = preg_quote
     * @param string $glue (optional)
     * @param string $before (optional)
     * @param string $after (optional)
     * @return string
     * @example Tools::arrayListed(["<b>Apple</b>", "Levi's", "H&M"]) --> "<b>Apple</b>,Levi's,H&M"
     * @example Tools::arrayListed(['A', 'B', 0, '', false, null, 'C'], Tools::ARRL_EMPTY) --> "A,B,C"
     * @example Tools::arrayListed(['about', 'links'], Tools::ARRL_PATTERN, ' | ', '<a href="/en/#" title="#">#</a>', '#')
     *  --> '<a href="/en/about" title="about">about</a> | <a href="/en/links" title="links">links</a>'
     */
    public static function arrayListed($array, $flags = 0, $glue = ',', $before = '', $after = '')
    {
        $result = '';
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if ($flags & self::ARRL_KEYS) {
                    $v = $k;
                }
                if ($flags & self::ARRL_DB_ID) {
                    $v = str_replace('`', '``', $v);
                }
                if ($flags & self::ARRL_HTML) {
                    $v = htmlspecialchars($v, ENT_QUOTES);
                }
                if ($flags & self::ARRL_ESC) {
                    if ($flags & self::ARRL_INT) {
                        $v = strtr($v, ['"' => '\"', "'" => "\\'", "\\" => "\\\\", '%' => '%%', '_' => '\_']); //like
                    } elseif ($flags & self::ARRL_FLOAT) {
                        $v = preg_quote($v);
                    } else {
                        $v = self::escapeSQL($v);
                    }
                }
                if ($flags & self::ARRL_JS) {
                    $v = self::escapeJS($v);
                }
                if ($flags & self::ARRL_INT) {
                    $v = intval($v);
                }
                if ($flags & self::ARRL_FLOAT) {
                    $v = (float)$v;
                }
                if (!($flags & self::ARRL_EMPTY) || $v) {
                    if ($flags & self::ARRL_PATTERN) {
                        $result .= $glue . strtr($before, [$after => $v]);
                    } else {
                        $result .= $glue . $before . $v . $after;
                    }
                }
            }
        }
        return mb_substr($result, mb_strlen($glue));
    }

    /**
     * Take a hash of arrays and rebase its keys to the first item of each array's array.
     * If resulting items have only one item, get rid of [].
     *
     * @example $a = [[id=>5, name=>John, surname=>Doe], [id=>6, name=>Jane, surname=>Dean]]
     *          $b = [[id=>5, name=>John], [id=>6, name=>Jane]]
     *          arrayReindex($a, 'id') --> [5=>[name=>John, surname=>Doe], 6=>[name=>Jane, surname=>Dean]]
     *          arrayReindex($b, 'id') --> [5=>John, 6=>Jane]
     *
     * @param array $array
     * @param mixed $index (optional)
     */
    public static function arrayReindex(array $array, $index = 0)
    {
        $result = [];
        foreach ($array as $item) {
            if (isset($item[$index])) {
                $key = $item[$index];
                unset($item[$index]);
                if (count($item) == 1) {
                    $item = reset($item);
                }
                if (isset($result[$key])) {
                    $result[$key] = (array)$result[$key];
                    $result[$key] []= $item;
                } else {
                    $result[$key] = $item;
                }
            }
        }
        return $result;
    }

    /**
     * Subtract items from given array.
     *
     * @example $fruits = ['Apple', 'Pear', 2=>'Kiwi']
     *      Tools::arrayRemoveItems($fruits, ['Apple', 'Pear']) --> [2=>'Kiwi'];
     *      Tools::arrayRemoveItems($fruits, 'Apple', 'Pear', 'Orange') --> [2=>'Kiwi'];
     *      Tools::arrayRemoveItems($fruits, 'Apple', 'Pear', 'Kiwi') --> [];
     *
     * @param array $array1 array to remove items from
     * @param mixed $array2 either array containing values that are keys to be removed
     *              or key(s) to be removed
     * @return array with removed keys
     *
     * Note: this function can have more arguments - argument #3, 4.. are taken as further items to remove
     * Note: no error, warning or notice is thrown if item in array is not found.
     */
    public static function arrayRemoveItems(array $array, $remove)
    {
        if (is_array($remove)) {
            foreach ($remove as $item) {
                while (($key = array_search($item, $array)) !== false) {
                    unset($array[$key]);
                }
            }
        } else {
            foreach (func_get_args() as $index => $item) {
                if ($index) {
                    while (($key = array_search($item, $array)) !== false) {
                        unset($array[$key]);
                    }
                }
            }
        }
        return $array;
    }

    /**
     * Return the key of given array whose index .. equals ...
     * @example $array = [0=>['id'=>5,'name'=>'Joe'], 1=>['id'=>17,'name'=>'Irene']]; Tools::arraySearchAssoc(['name'=>'Irene'], $array) --> 1
     * Keys that don't exist are counted as non-matches.
     *
     * @param array $needles
     * @param array $haystack
     * @param array $options
     *      [strict] - non-zero -> strict comparison (default - false)
     *      [partial] - non-zero -> search for at least one match (default: all must match)
     * @return mixed key for the $needle or false if array item was not found
     */
    public static function arraySearchAssoc($needles, $haystack, $options = [])
    {
        if (!is_array($haystack) || !is_array($needles)) {
            return false;
        }
        Tools::set($options['strict'], false);
        Tools::set($options['partial'], false);
        foreach ($haystack as $key => $value) {
            $matched = 0;
            foreach ($needles as $needleKey => $needleValue) {
                if (isset($value[$needleKey]) && ($options['strict'] ? $value[$needleKey] === $needleValue : $value[$needleKey] == $needleValue)) {
                    $matched++;
                } elseif (!$options['partial']) {
                    break;
                }
            }
            if ($options['partial'] ? $matched > 0 : $matched == count($needles)) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Case-insensitive version of array_search().
     *
     * @param string $needle
     * @param array $haystack
     * @param bool $strict (optional)
     * @param mixed $encoding (optional)
     * @result mixed found key or false if needle not found
     */
    public static function array_search_i($needle, array $haystack, $strict = false, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $needle = mb_strtolower($needle, $encoding);
        foreach ($haystack as $key => $value) {
            if ($needle == mb_strtolower($value, $encoding)) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Return true if $text begins with $beginning.
     *
     * @param string $text text to test
     * @param mixed $beginning string (for one) or array (for more) beginnings to test against
     * @param bool $caseSensitive (optional) case sensitive searching?
     * @param string $encoding (optional) internal encoding
     * @return bool
     */
    public static function begins($text, $beginning, $caseSensitive = true, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $beginning = is_array($beginning) ? $beginning : [$beginning];
        if ($caseSensitive) {
            foreach ($beginning as $value) {
                if (mb_substr($text, 0, mb_strlen($value, $encoding), $encoding) === $value) {
                    return true;
                }
            }
        } else {
            foreach ($beginning as $value) {
                if (mb_strtolower(mb_substr($text, 0, mb_strlen($value, $encoding)), $encoding) === mb_strtolower($value)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Shortcut for checking value of given variable against given list and change if it is in it.
     *
     * @example $word = 'vitamins'; Tools::blacklist($product, ['violence', 'sex'], null); //$word remains 'vitamins'
     * @example $word = 'violence'; Tools::blacklist($product, ['violence', 'sex'], null); //$word set to null
     * @param mixed &$value
     * @param array $list
     * @param mixed $else
     * @return bool if the value was in the list
     */
    public static function blacklist(&$value, array $list, $else)
    {
        if (in_array($value, $list)) {
            $value = $else;
            return false;
        }
        return true;
    }

    /**
     * Return alphabetical column name (like A, B, C ... Z, AA, AB, ...) from integer index
     * @example Tools::columnName(0) --> A, Tools::columnName(25) --> Z, Tools::columnName(26) --> AA
     *
     * @param int $columnIndex Column index (base 0)
     * @return string column name or empty string if the index is < 0
     */
    public static function columnName($columnIndex = 0)
    {

        if ($columnIndex < 26) {
            return $columnIndex < 0 ? '' : chr(65 + $columnIndex);
        }
        return self::columnName((int)($columnIndex / 26) - 1) . chr(65 + $columnIndex % 26); //@todo intdiv() for PHP7
    }

    /**
     * Make a cURL call and return its response. Requires running cURL extension with certain defaults (see below).
     *
     * @param string $url URL to call
     * @param mixed[] options (optional) changing CURL options
     * @return string response or null if curl_errno() is non-zero
     */
    public static function curlCall($url, $options = [], &$error = null)
    {
        $ch = curl_init();
        $options[CURLOPT_URL] = $url;
        curl_setopt_array($ch, $options + self::$CURL_OPTIONS);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        return $errno ? null : $response;
    }

    /**
     * Cut a given string from the beginning to the first occurence of a substring.
     *
     * @param string &$haystack
     * @param string $needle
     * @return void; If substring is found, $haystack will be modified.
     */
    public static function cutTill(&$haystack, $needle)
    {
        if (($p = strpos($haystack, $needle)) !== false) {
            $haystack = substr($haystack, 0, $p);
        }
    }

    /**
     * Shortcut for echo'<pre>'; var_dump(); echo'</pre>';
     *
     * @param mixed $args variables or expressions to be dumped
     * @return void
     */
    public static function dump($args)
    {
        echo '<pre>';
        foreach (func_get_args() as &$arg) {
            var_dump($arg);
        }
        echo '</pre>';
    }

    /**
     * Return true if $text ends with $ending.
     *
     * @param string $text text to test
     * @param mixed $ending string (for one) or array (for more) endings to test against
     * @param bool $caseSensitive (optional) case sensitive searching?
     * @param string $encoding (optional) internal encoding
     * @return bool
     */
    public static function ends($text, $ending, $caseSensitive = true, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $ending = is_array($ending) ? $ending : [$ending];
        if ($caseSensitive) {
            foreach ($ending as $value) {
                if (mb_substr($text, -mb_strlen($value, $encoding), null, $encoding) === $value) {
                    return true;
                }
            }
        } else {
            foreach ($ending as $value) {
                if (mb_strtolower(mb_substr($text, -mb_strlen($value, $encoding), null, $encoding)) === mb_strtolower($value)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Shortcut for isset($a) && $a == $b; useful for long variables.
     *
     * @param mixed &$a tested variable
     * @param mixed $b tested value
     * @return bool
     */
    public static function equal(&$a, $b)
    {
        return isset($a) && $a === $b;
    }

    /**
     * Escape an identifier (column, table, database, ..) in MySQL (or compatible). Database-specific.
     *
     * @param string $id identifier
     * @return string properly escaped identifier
     */
    public static function escapeDbIdentifier($id)
    {
        return '`' . str_replace('`', '``', $id) . '`';
    }

    /**
     * Escape an enumeration of values to use in MySQL (or compatible) in the IN() clause. Database-specific.
     *
     * @param mixed $input string array of values
     * @return string
     */
    public static function escapeIn($input)
    {
        if (is_array($input)) {
            $result = '';
            foreach ($input as $item) {
                if (is_null($item)) {
                    $result .= ',NULL';
                } elseif (is_numeric($item) || is_bool($item)) {
                    $result .= "," . (float)$item;
                } else {
                    $result .= ',"' . self::escapeSQL($item) . '"';
                }
            }
            return substr($result, 1);
        }
        preg_match_all('~([-\+]?(0x[0-9a-f]+|(0|[1-9][0-9]*)(\.[0-9]+)?(e[-\+]?[0-9]+)?)|\'(\.|[^\'])*\'|"(\.|[^"])*")~i', $input, $matches);
        return implode(',', $matches[0]);
    }

    /**
     * Escape a string to use in <script type="text/javascript"> blocks.
     *
     * @param string $string
     * @return string escaped string
     */
    public static function escapeJS($string)
    {
        return strtr($string, [']]>' => ']]\x3E', '/' => '\/', "\\" => '\\', '"' => '\"', "'" => "\\'"]);
    }

    /**
     * Basic escaping of a string for use in MySQL. Database-specific.
     * Outdated and discouraged to use. Use mysqli_real_escape_string() instead.
     *
     * @param string $input
     * @return string
     */
    public static function escapeSQL($input)
    {
        //TODO: zavést logování, kde je použito a pak po opravě instancí zrušit
        return addslashes($input);
    }

    /**
     * Extract a string separated by a given separator on a given position.
     * @example Tools::exploded('-', '1996-07-30', 2) --> '30'
     *
     * @param string $separator
     * @param string $string to extract from
     * @param int $index (optional)
     * @return string or null if given position does not exist
     */
    public static function exploded($separator, $string, $index = 0)
    {
        $result = explode($separator, $string);
        return isset($result[$index]) ? $result[$index] : null;
    }

    /**
     * Get GoogleAuthenticator hash of given secret and timeslot.
     * https://en.wikipedia.org/wiki/Google_Authenticator
     *
     * @param string $secret 6-8 chars secret
     * @param int $timeSlot delta to time slot of floor(time() / 30)
     * @return int
     */
    public function GoogleAuthenticatorCode($secret, $timeSlot = 0)
    {
        if ($timeSlot < 1000) {
            $timeSlot += floor(time() / 30); // @todo intdiv() for PHP7
        }
        $data = str_pad(pack('N', $timeSlot), 8, "\0", STR_PAD_LEFT);
        $hash = hash_hmac('sha1', $data, $secret, true);
        $unpacked = unpack('N', substr($hash, ord(substr($hash, -1)) & 0xF, 4));
        return ($unpacked[1] & 0x7FFFFFFF) % 1000000;
    }

    /**
     * Converts ", ', &, <, > in $string to &quot; &#039/&apos; &lt;, &gt; respectively.
     *
     * @param string $string unescaped string
     * @return string escaped string to use in HTML
     */
    public static function h($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }

    /**
     * HTML notation for the <input> tag. See self::htmlTextinput() for more info.
     */
    public static function htmlInput($name, $label, $value, $options = [])
    {
        return self::htmlTextInput($name, $label, $value, $options);
    }

    /**
     * HTML notation for <option> filled with given parameters.
     *
     * @param mixed $value
     * @param string $text
     * @param mixed $default (optional)
     * @param bool $disabled (optional)
     * @return string HTML code
     */
    public static function htmlOption($value, $text, $default = null, $disabled = false)
    {
        return '<option' . ($disabled ? '' : ' value="' . self::h($value) . '"')
            . ($value === $default ? ' selected="selected"' : '')
            . ($disabled ? ' disabled="disabled"' : '')
            . '>' . self::h($text) . '</option>' . PHP_EOL;
    }

    /**
     * HTML notation for one or more <input type=radio> element(s) filled with given parameters.
     *
     * @param string $name name attribute of the element
     * @param mixed $input associative array of value=>label pairs or one value (in case of one item)
     * @param scalar $value value that should be checked
     * @param mixed[] $options (optional)
     *     [separator] - between items,
     *     [radio-class] - optional class for <input type=radio>,
     *     [label-class] - optional class for <label>,
     *     [between] - what is between the input and its label (in raw HTML; default is " ")
     * @return string HTML code
     */
    public static function htmlRadio($name, $input, $value = null, $options = [])
    {
        $result = '';
        $input = is_array($input) ? $input : [$input => $input];
        if (is_array($value) && isset($value[$name])) {
            $value = $value[$name];
        }
        $name = self::h($name);
        foreach (['offset', 'separator' ,'label-class' ,'radio-class'] as $key) {
            self::set($options[$key], '');
        }
        $i = (int)$options['offset'];
        self::set($options['between'], ' ');
        self::set($_SESSION['fill'][$name], '');
        foreach ($input as $inputKey => $inputValue) {
            $result .= $options['separator']
                . ($inputValue !== '' ? '<label' . self::wrap(trim(($_SESSION['fill'][$name] ? 'highlight' : '') . ' ' . $options['label-class'], ' '), ' class="', '"') . '>' : '')
                . '<input type="radio" name="' . $name . '" value="' . self::h($inputKey) . '"'
                . ($inputKey === $value ? ' checked="checked"' : '')
                . self::wrap($options['radio-class'], ' class="', '"');
            foreach ($options as $optionKey => $optionValue) {
                if (!in_array($optionKey, ['separator', 'offset', 'radio-class', 'label-class', 'checked', 'id', 'name', 'value', 'between']) && !is_null($optionValue)) {
                    $result .= ' ' . $optionKey . ($optionValue === true ? '' : '="' . self::escapeJS($optionValue) . '"');
                }
            }
            $result .= '/>' . ($inputValue !== '' ? $options['between'] . Tools::h($inputValue) . '</label>' : '');
            $i++;
        }
        return mb_substr($result, mb_strlen($options['separator']));
    }

    /**
     * HTML notation for <select>, options given either as an array or a SQL query.
     * @example Tools::htmlSelect('agree', ['Y'=>'Yes', 'N'=>'No'], 'N', ['class'=>'form-control']) -->
     *          <select name="agree" class="form-control">
     *          <option value="Y">Yes</option>
     *          <option value="N" selected="selected">No</option>
     *          </select>
     *
     * @param string $name
     * @param array $values
     * @param string $default value
     * @param mixed[] options (optional)
     *  [prepend] array of options to prepend before $values
     *  [append] array of options to append after $values
     *  [class], [id], [onchange], ... optional HTML attributes to add to the <select> notation
     * @return string HTML code
     */
    public static function htmlSelect($name, array $values, $default, $options = [])
    {
        $result = '<select name="' . self::h($name) . '"';
        foreach ($options as $key => $value) {
            if (!self::among($key, 'name', 'prepend', 'append')) {
                $result .= ' ' . $key . '="' . self::h($value) . '"';
            }
        }
        $result .= '>' . PHP_EOL . self::htmlSelectAppend(self::set($options['prepend'], []), $default);
        foreach ($values as $key => $value) {
            $result .= self::htmlOption($key, $value, $default);
        }
        return $result . self::htmlSelectAppend(Tools::set($options['append'], []), $default)
            . '</select>' . PHP_EOL;
    }

    /**
     * Used in ::htmlSelect().
     *
     * @param array $array key:value pairs to be converted to <option>s
     * @param mixed $default
     * @return string
     */
    protected static function htmlSelectAppend(array $array, $default)
    {
        $result = '';
        foreach ($array as $key => $value) {
            $option = is_string($value) ? explode("\0", $value) : array_values($value);
            $result .= self::htmlOption($option[0], $option[1], $default);
        }
        return $result;
    }

    /**
     * HTML notation for the <textarea> tag. See html_textinput() for more info.
     *
     * @param string $name
     * @param string $content
     * @param int $cols (optional)
     * @param int $rows (optional)
     * @param mixed[] $options (optional) See self::htmlTextInput() for more info
     * @return string HTML code
     */
    public static function htmlTextarea($name, $content, $cols = 60, $rows = 5, $options = [])
    {
        $label = self::set($options['label'], '');
        unset($options['label']);
        $options = array_merge($options, ['cols' => $cols, 'rows' => $rows]);
        return self::htmlTextInput($name, $label, $content, $options);
    }

    /**
     * HTML notation for the <input> or <textarea> tag. Used by self::htmlInput() and self::htmlTextarea().
     *
     * @param string $name element name
     * @param string $label label, omitted if empty, translated if true
     * @param mixed $value value, either given directly or as an array in the [name] index
     * @param mixed $options (optional) options. Either an associative array or string containing type
     *     [type] - input's type, 'text' by default
     *     [before], [between], [after] - HTML to insert before/between/after <label> and <input>
     *     [table] - refill [before], [between], [after] to make a table row
     *     [random-id] - append random number to the id attribute
     *     [label-after] - <tag> goes first, <label> after
     *     [label-html] - label given as raw HTML, don't escape HTML entities
     *     [label-class] - class(es) for label
     *     other tag's attributes - will be specified (except for NULLs)
     * @return string HTML code
     */
    protected static function htmlTextInput($name, $label, $value, $options = [])
    {
        $result = '';
        if (is_array($value)) {
            $value = self::set($value[$name], '');
        }
        if (is_string($options)) {
            $options = ['type' => $options];
        }
        if ($label && !self::nonempty($options['id'])) {
            $options['id'] = 'input-' . self::webalize($name);
        }
        if (self::nonempty($options['random-id'])) {
            $options['id'] = self::set($options['id'], 'input') . '-' . rand(1e8, 1e9-1);
        }
        if (!isset($options['rows']) and !self::nonempty($options['type'])) {
            $options['type'] = 'text';
        }
        if (self::nonempty($options['table'])) {
            foreach (['before' => '<tr><td>', 'between' => '</td><td>', 'after' => '</td></tr>'] as $k=>$v) {
                self::setifempty($options[$k], $v);
            }
        }
        if (!self::nonempty($options['label-html'])) {
            $label = self::h($label);
        }
        self::setifempty($options['type'], 'text');
        if (isset($options['rows'], $options['type'])) {
            $options['type'] = null; // don't specify type="..." in <textarea>
        }
        if ($options['type'] == 'disabled') {
            $result .= '<input type="hidden" name="' . self::h($name) . '" value="' . self::h($value) . '"/>';
            $options['type'] = 'text';
            $options['disabled'] = 'disabled';
        }
        $result .= '<' . (isset($options['rows']) ? 'textarea' : 'input');
        $options = array_merge($options, ['name' => self::h($name), 'value' => $value]);
        foreach ($options as $k => $v) {
            if (is_string($k) && !is_null($v)
                && !self::among($k, 'before', 'between', 'after', 'table', 'random-id', 'label-after', 'label-html', 'label-class', 'value')) {
                $result .= ' ' . $k . ($v === true ? '' : '="' . (mb_substr($k, 0, 2)=='on' ? self::h($v) : self::h($v)) . '"');
            }
        }
        $result .= isset($options['rows']) ? '>' . self::h($value) . '</textarea>' : ' value="' . self::h($value) . '"/>';
        $label = self::among($label, '', false, null) ? '' : '<label' . self::wrap(self::h(self::set($options['id'])), ' for="', '"')
            . self::wrap(self::h(self::set($options['label-class'], '')), ' class="', '"') . '>' . $label . '</label>';
        return self::setifempty($options['before']) . (self::nonempty($options['label-after']) ? $result : $label)
            . self::setifempty($options['between']) . (self::nonempty($options['label-after']) ? $label : $result) . self::setifempty($options['after']);
    }

    /**
     * Return a HTTP response split into headers and body.
     *
     * @param string $response
     * @param array $options (optional)
     *        $options['JSON'] = non-zero - apply json_decode() on response body
     * @return array containing ['headers'] with HTTP headers and ['body'] with response body
     */
    public static function httpResponse($response, array $options = [])
    {
        static $HEADERS_BODY_SEPARATOR = "\r\n\r\n";
        $result = [
            'headers' => [],
            'body' => []
        ];
        if ($pos = strpos($response, $HEADERS_BODY_SEPARATOR)) {
            foreach (explode("\n", substr($response, 0, $pos)) as $key => $value) {
                $value = trim($value, "\t\r\n ");
                if ($value && ($p = strpos($value, ':'))) {
                    $result['headers'][trim(substr($value, 0, $p), ' ')] = trim(substr($value, $p + 1), ' ');
                }
            }
        }
        $result['body'] = substr($response, $pos + strlen($HEADERS_BODY_SEPARATOR));
        if (self::set($options['JSON'])) {
            $result['body'] = json_decode($result['body'], true);
        }
        return $result;
    }

    /**
     * Return first non-zero parameter passed to this function, or the 1st parameter if all are non-zero.
     *
     * @param mixed $a tested value(s)
     * @return mixed
     * For just two arguments use the ternary operator
     */
    public static function ifempty($a)
    {
        foreach (func_get_args() as $arg) {
            if ($arg) {
                return $arg;
            }
        }
        return $a;
    }

    /**
     * Return first non-null parameter passed to this function, or null.
     *
     * @param mixed $a tested value
     * @param mixed $b value returned if parameter #1 is null
     * @return mixed
     */
    public static function ifnull($a)
    {
        foreach (func_get_args() as $arg) {
            if (!is_null($arg)) {
                return $arg;
            }
        }
        return $a;
    }

    /**
     * Shortcut for isset($a) ? $a : $b;
     *
     * @param mixed &$a tested variable
     * @param mixed $b optional variable in case $a is not set
     * @return mixed
     */
    public static function ifset(&$a, $b = null)
    {
        return isset($a) ? $a : $b;
    }

    /**
     * Case-insensitive version of in_array().
     *
     * @param string $needle
     * @param array $haystack
     * @param bool $strict (optional) default false
     * @param mixed $encoding (optional)
     * @result bool true/false whether the needle was found
     */
    public static function in_array_i($needle, array $haystack, $strict = false, $encoding = null)
    {
        $key = self::array_search_i($needle, $haystack, $strict, $encoding);
        return $key !== false && isset($haystack[$key]);
    }

    /**
     * Date (and time) locally. Uses self::$LOCALE.
     *
     * @param mixed $datetime date/time as a string or integer
     * @param string $language (optional) language code as key to Tools::LOCALE
     * @param bool $includeTime (optional) return also time?
     * @return string
     */
    public static function localeDate($datetime, $language = 'en', $includeTime = true)
    {
        if (is_string($datetime)) {
            $datetime = strtotime($datetime);
        }
        $language = isset(self::$LOCALE[$language]) ? $language : 'en';
        $format = date('Y', $datetime) == date('Y') ? self::$LOCALE[$language]['date format'] : self::$LOCALE[$language]['full date format'];
        $date = date($format, +$datetime);
        if ($language != 'en') {
            $date = strtr($date, self::$LOCALE[$language]['months']);
        }
        if (!$includeTime) {
            return $date;
        }
        return $date . ' ' . date(self::$LOCALE[$language]['time format'], +$datetime);
    }

    /**
     * Return given date using self::$LOCALE.
     *
     * @param mixed $datetime a date/time as a string or integer
     * @param string $language (optional) language code as key to Tools::LOCALE
     * @return string
     */
    public static function localeTime($datetime, $language = 'en')
    {
        if (is_string($datetime)) {
            $datetime = strtotime($datetime);
        }
        $language = isset(self::$LOCALE[$language]) ? $language : 'en';
        return date(self::$LOCALE[$language]['time format'], $datetime);
    }

    /**
     * Multibyte version of low-case for first character of given string.
     *
     * @param string $string
     * @param string $encoding (optional)
     * @result string
     */
    public static function mb_lcfirst($string, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        return mb_strtolower(mb_substr($string, 0, 1, $encoding), $encoding) . mb_substr($string, 1, null, $encoding);
    }

    /**
     * Multibyte version of ucfirst().
     *
     * @param string $string
     * @param string $encoding (optional)
     * @result string
     */
    public static function mb_ucfirst($string, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding) . mb_substr($string, 1, null, $encoding);
    }

    /**
     * Shortcut for isset($a) && !empty($a); useful for long variables.
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function nonempty(&$a)
    {
        return isset($a) && !empty($a);
    }

    /**
     * Shortcut for isset($a) && $a; useful for long variables.
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function nonzero(&$a)
    {
        return isset($a) && $a;
    }

    /**
     * Plural form of a string according to a suplied number.
     *
     * @example Tools::plural(1, 'child', false, 'children') --> 'child'
     * @example Tools::plural(2, 'Jahr', 'Jahre', 'Jahren') --> 'Jahre'
     *
     * @param int $amount amount
     * @param string $form1 form for amount of 1
     * @param string $form234 form for amount of 2, 3, or 4 (if false is given, $form5plus will be used)
     * @param string $form5plus form for amount of 5+
     * @param string $form0 = false (optional) form for amount of 0 (omit it or submit false to use $form5plus instead)
     * @param bool $mod100 = false get modulo of 100 from $amount
     * @return string result form
     */
    public static function plural($amount, $form1, $form234, $form5plus, $form0 = false, $mod100 = false)
    {
        $amount = abs((int)$amount);
        $amount = $mod100 ? $amount % 100 : $amount;
        $form234 = $form234 !== false ? $form234 : $form5plus;
        $form0 = $form0 === false ? $form5plus : $form0;
        return $amount >= 5 ? $form5plus : ($amount == 1 ? $form1 : $form234);
    }

    /**
     * Return RegEx pattern for an numberic range from zero to given number (included)
     *
     * @example Tools::preg_max(255) --> "(0|[1-9][0-9]?|1[0-9]{2}|2[0-4][0-9]|25[0-5])"
     * @param int $max maximum
     * @return string RegEx pattern
     */
    public static function preg_max($max) {
        if (($len = strlen($max = (int)$max)) == 1) {
            return ($max ? "[0-$max]" : 0);
        }
        $result = '0|[1-9]' . ($len > 2 ? ($len == 3 ? '[0-9]?' : '[0-9]{0,' . ($len - 2) . '}') : '');
        for ($i = 0; $i < $len; $i++) {
            $digit = substr($max, $i, 1);
            if ($i == 0 && $digit == '1') {
                continue;
            } elseif ($digit == '0') {
                if ($i == $len - 1) {
                    $result .= "|$max";
                }
                continue;
            }
            $digit += $i == $len - 1 ? 0 : -1;
            $m = $i ? 0 : 1;
            $result .= '|' . substr($max, 0, $i) . ($digit > $m ? '[' . $m . ($digit - $m > 1 ? '-' : '') . $digit . ']' : $digit)
                . ($len - $i > 1 ? '[0-9]' . ($len - $i > 2 ? '{' . ($len - $i - 1) . '}': '') : '');
        }
        return "($result)";
    }

    /**
     * Generate random password of $length characters (letters, -, digits), 0O1lI are excluded.
     *
     * @param int $length (optional) length of the password
     * @return string password
     */
    public static function randomPassword($length = 8)
    {
        $rand = function_exists('random_int') ? 'random_int' : 'rand';
        $charsmax = strlen(self::$PASSWORD_CHARS) - 1;
        for ($i = 0, $result = ''; $i < $length; $i++) {
            $result .= substr(self::$PASSWORD_CHARS, $rand(0, $charsmax), 1);
        }
        return $result;
    }

    /**
     * Perform a HTTP redirection to a given URL.
     *
     * @param string $url (optional) URL to redirect to. Default value "" means the current URL
     * @param int $HTTPCode (optional) HTTP code used in header. Should be between 300 and 399, default is 303.
     * @return void
     */
    public static function redir($url = '', $HTTPCode = 303)
    {
        $url = parse_url($url);
        $url2 = (Tools::set($url['scheme']) ? $url['scheme'] . '://' : (Tools::set($_SERVER['HTTPS']) == 'on' ? 'https://' : 'http://'))
            . (Tools::set($url['host']) ? $url['host'] : $_SERVER['HTTP_HOST'])
            . (Tools::set($url['path']) ? $url['path'] : $_SERVER['SCRIPT_NAME'])
            . Tools::wrap(Tools::set($url['query']) ? $url['query'] : $_SERVER['QUERY_STRING'], '?')
            . Tools::wrap(Tools::set($url['fragment']), '#');
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        header("Location: $url2", true, $HTTPCode);
        header('Connection: close');
        die('<script type="text/javascript">window.location=' . json_encode($url2) . ";</script>\n"
            . '<a href="' . $url2 . '">&rarr;</a>' //yes, without escaping
        );
    }

    /**
     * How much time ago $datetime was according to the current time. Uses self::$LOCALE.
     *
     * @param mixed $datetime elapsed time as a string or an integer timestamp
     * @param string $language (optional) language code as key to Tools::LOCALE
     * @return string text representation
     */
    public static function relativeTime($datetime, $language = 'en')
    {
        if (is_numeric($datetime)) {
            $datetime = date("Y-m-d\TH:i:sP", $datetime);
        }
        $diff = new \DateTime($datetime);
        $diff = $diff->diff(new \DateTime());
        $language = isset(self::$LOCALE[$language]) ? $language : 'en';
        $result = '';
        foreach (explode(' ', 'y m d h i s') as $part) {
            $result .= ($diff->{$part} ? ',' . $diff->{$part} . ' '
                . self::plural($diff->$part,
                    self::$LOCALE[$language]['time ago'][$part][0],
                    self::$LOCALE[$language]['time ago'][$part][1],
                    self::$LOCALE[$language]['time ago'][$part][2])
                : '');
        }
        $result = explode(',', $result);
        $result = array_slice($result, 1, 2) ?: [self::$LOCALE[$language]['time ago']['moment']];
        return ($diff->invert ? self::$LOCALE[$language]['time ago']['in'] . ' ' : '')
            . implode(', ', $result) . ($diff->invert ? '' : ' ' . self::$LOCALE[$language]['time ago']['ago']);
    }

    /**
     * Add an message according to given result.
     *
     * @param bool $success was the operation successful?
     * @param string $successMessage
     * @param string $errorMessage
     * @return void
     */
    public static function resolve($success, $successMessage, $errorMessage)
    {
        if ($success) {
            self::addMessage('success', $successMessage);
        } else {
            self::addMessage('error', $errorMessage);
        }
    }

    /**
     * If called with just one parameter, returns given variable if it is set and non-zero, false otherwise.
     * If called with two parameters, assign the 2nd parameter to the 1st if the 1st variable is not set or not non-zero.
     * For PHP7+ use the ?? operator.
     *
     * @example unset($a); Tools::set($a); --> false
     * @example $a = 0; Tools::set($a); --> false
     * @example $a = 5; Tools::set($a); --> 5
     * @example unset($a); Tools::set($a, 5); --> 5 ($a = 5)
     * @example $a = 0; Tools::set($a, 5); --> 5 ($a = 5)
     * @example $a = 4; Tools::set($a, 5); --> 4 ($a = 4)
     *
     * @param mixed &$a tested variable
     * @param mixed $b (optional) value to assign to the first variable
     * @return mixed
     */
    public static function set(&$a, $b = null)
    {
        if (func_num_args() == 1) {
            return isset($a) && $a ? $a : false;
        }
        return $a = (isset($a) && $a ? $a : $b);
    }

    /**
     * Shortcut for isset($a) && is_array($a);
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function setarray(&$a)
    {
        return isset($a) && is_array($a);
    }

    /**
     * Shortcut for if (isset($a) && !$a) $a = $b; useful for long variables.
     *
     * @param mixed &$a tested variable
     * @param mixed $b (optional) value in case $a is not set or empty
     * @return mixed
     */
    public static function setifempty(&$a, $b = null)
    {
        return $a = isset($a) ? ($a ?: $b) : $b;
    }

    /**
     * Shortcut for $a = isset($a) ? $a : $b;
     *
     * @param mixed &$a tested variable
     * @param mixed $b (optional) value in case $a is set
     * @return mixed
     */
    public static function setifnotset(&$a, $b = null)
    {
        $a = isset($a) ? $a : $b;
    }

    /**
     * Shortcut for if (isset($a) && is_null($a)) $a = $b; useful for long variables.
     *
     * @param mixed &$a tested variable
     * @param mixed $b (optional) value in case $a is not set or null
     * @return mixed
     */
    public static function setifnull(&$a, $b = null)
    {
        return $a = isset($a) ? (is_null($a) ? $b : $a) : $b;
    }

    /**
     * Shortcut for isset($a) && is_scalar($a);
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function setscalar(&$a)
    {
        return isset($a) && is_scalar($a);
    }

    /**
     * Shorten a string to given $limit of characters (with $ellipsis concatenated at the end), shorter strings are returned the same.
     *
     * @param string $string
     * @param int $limit
     * @param string $ellipsis (optional) string to signify ellipsis
     * @return string
     */
    public static function shortify($string, $limit, $ellipsis = '…', $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        if (mb_strlen($string, $encoding) > $limit) {
            return mb_substr($string, 0, $limit, $encoding) . $ellipsis;
        }
        return $string;
    }

    /**
     * Return or show session message variables as HTML <div>s and unset them. Bootstrap styling is used.
     *
     * @param bool $echo (optional) echo the messages immediately?
     * @return void or array with session messages or void if $echo == true
     */
    public static function showMessages($echo = true)
    {
        $_SESSION['messages'] = isset($_SESSION['messages']) && is_array($_SESSION['messages']) ? $_SESSION['messages'] : [];
        $result = '';
        foreach ((array)$_SESSION['messages'] as $key => $message) {
            if (isset($message[0], $message[1])) {
                $result .= '<div class="alert alert-dismissible alert-' . self::h($message[0]) . '" role="alert">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    . self::$MESSAGE_ICONS[$message[0]] . ' ' . $message[1] . '</div>' . PHP_EOL;
            }
            unset($_SESSION['messages'][$key]);
        }
        if (!$echo) {
            return $result;
        }
        echo $result;
    }

    /**
     * Strip specified attributes of a given HTML/XML or its fragment.
     * Requires DOMDocument, DOMXPath, DOMElement, DOMElementList classes
     *
     * @param string $html HTML/XML - whole or partial
     * @param mixed $attributes attribute(s) to strip from elements - either string (for one) or array
     * @return string HTML/XML stripped of attributes
     */
    public static function stripAttributes($html, $attributes)
    {
        $domd = new \DOMDocument();
        libxml_use_internal_errors(true);
        $domd->loadXML("<x>$html</x>");
        libxml_use_internal_errors(false);
        $domx = new \DOMXPath($domd);
        foreach ((is_array($attributes) ? $attributes : [$attributes]) as $attribute) {
            $items = $domx->query("//*[@$attribute]");
            foreach($items as $item) {
                $item->removeAttribute($attribute);
            }
        }
        $result = $domd->saveXML();
        //strip "<"."?xml version="1.0"?".">\n<x>" from beginning and "</x>\n" @todo: version-sensitive
        return substr($result, ($pos = strpos($result, '?' . ">") + 3) + ($result[$pos] == "\n" ? 1 : 0) + 3, substr($result,-1) == "\n" ? -5 : -4);
    }

    /**
     * Short cut for finding a substring within a string and returning what follows (or false on no match).
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseInsensitive (optional) default: false
     * @param string $encoding (optional)
     * @return string substring after $needle or false is $needle wasn't found
     */
    public static function str_after($haystack, $needle, $caseInsensitive = false, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $function = $caseInsensitive ? 'mb_stripos' : 'mb_strpos';
        if (($pos = $function($haystack, $needle, 0, $encoding)) === false) {
            return false;
        }
        return mb_substr($haystack, $pos + mb_strlen($needle));
    }

    /**
     * Short cut for finding a substring within a string and returning all before (or false on no match).
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseInsensitive (optional) default: false
     * @param string $encoding (optional)
     * @return string substring before $needle or false is $needle wasn't found
     */
    public static function str_before($haystack, $needle, $caseInsensitive = false, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $function = $caseInsensitive ? 'mb_stripos' : 'mb_strpos';
        if (($pos = $function($haystack, $needle, 0, $encoding)) === false) {
            return false;
        }
        return mb_substr($haystack, 0, $pos, $encoding);
    }

    /**
     * Inverse to str_getcsv() - return comma-separated-values out of given array.
     * For parameter details see PHP's fputcsv()
     * edited version from https://gist.github.com/johanmeiring/2894568
     *
     * @param array $fields
     * @param string $delimiter (optional) default ','
     * @param string $enclosure (optional) default '"'
     * @param string $escape_char (optional) default "\\"
     * @result string CSV of given arguments
     */
    public static function str_putcsv(array $fields, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
    {
        $fp = fopen('php://memory', 'r+b');
        fputcsv($fp, $fields, $delimiter, $enclosure, $escape_char);
        $size = ftell($fp);
        rewind($fp);
        $data = fread($fp, $size);
        fclose($fp);
        return $data;
    }

    /**
     * Return actual QUERY_STRING changed by suggested amendments.
     *
     * @param array $changes parameters to add/modify, null as a value signifies omitting the key:value pair
     * @param bool $htmlspecialchars (optional) apply htmlspecialchars()?
     * @return string URL-encoded string
     */
    public static function urlChange(array $changes, $htmlspecialchars = false)
    {
        parse_str(self::set($_SERVER['QUERY_STRING'], ''), $parameters);
        foreach ($changes as $key => $value) {
            if (is_null($value)) {
                unset($parameters[$key]);
            } else {
                $parameters[$key] = $value;
            }
        }
        $result = http_build_query($parameters);
        if ($htmlspecialchars) {
            $result = self::h($result);
        }
        return $result;
    }

    /**
     * String conversion: diacritics --> ASCII, everything else than a-z, A-Z, 0-9, "_", "-" --> "-", then "--" --> "-" and "-" at the ends get trimmed.
     *
     * @param $string string to webalize
     * @param $charlist (optional) string of chars to be used
     * @param $lower bool (optional) convert to lower-case?
     * @return string converted text
     * @author Daniel Grudl (Nette)
     */
    public static function webalize($string, $charlist = null, $lower = true)
    {
        $string = strtr($string, '`\'"^~', '-----');
        if (ICONV_IMPL === 'glibc') {
            $string = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $string); // intentionally @
            $string = strtr($string, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
                "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
        } else {
            $string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string); // intentionally @
        }
        $string = str_replace(['`', "'", '"', '^', '~'], '', $string);
        if ($lower === -1) {
            $string = strtoupper($string);
        } elseif ($lower) {
            $string = strtolower($string);
        }
        $string = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $string);
        $string = trim($string, '-');
        return $string;
    }

    /**
     * Shortcut for checking value of given variable against given list and change it if it is not in it.
     *
     * @example $os = 'Windows'; Tools::whitelist($os, ['Windows', 'Unix'], 'unsupported'); //$os remains 'Windows'
     * @example $os = 'Solaris'; Tools::whitelist($os, ['Windows', 'Unix'], 'unsupported'); //$os set to 'unsupported'
     * @param mixed &$value
     * @param array $list
     * @param mixed $else
     * @return bool if the value was in the list
     */
    public static function whitelist(&$value, array $list, $else)
    {
        if (!in_array($value, $list)) {
            $value = $else;
            return false;
        }
        return true;
    }

    /**
     * If $text is set and non-zero, return it with prefix and postfix around, return $else otherwise.
     *
     * @param mixed $text value to be wrapped or replaced by $else
     * @param mixed $prefix
     * @param mixed $postfix (optional)
     * @param mixed $else (optional) value to be returned if $text is zero
     * @return string
     */
    public static function wrap($text, $prefix, $postfix = '', $else = '')
    {
        if ($text) {
            return $prefix . $text . $postfix;
        }
        return $else;
    }

    /**
     * Cipher a text with a key using xor operator
     *
     * @param string text to cipher
     * @param string key
     * @return string
     * @copyright Jakub Vrána, https://php.vrana.cz/
     */
    public static function xorCipher($text, $key) {
        $text2 = strlen($text) . ':' . str_pad($text, 17);
        $repeat = ceil(strlen($text2) / strlen($key));
        return $text2 ^ str_repeat($key, $repeat);
    }

    /**
     * Decipher a text with a key using xor operator
     *
     * @param string ciphered data
     * @param string key
     * @return string
     * @copyright Jakub Vrána, https://php.vrana.cz/
     */
    public static function xorDecipher($cipher, $key) {
        $repeat = ceil(strlen($cipher) / strlen($key));
        $text2 = $cipher ^ str_repeat($key, $repeat);
        list($length, $text) = explode(':', $text2, 2);
        return substr($text, 0, $length);
    }

}
