<?php

namespace GodsDev\Tools;

class Tools
{
    static $LOCALE = array(
        'en' => array(
            'date format' => 'jS F',
            'full date format' => 'jS F Y',
            'time format' => 'H:i:s',
            'time ago' => array(
                'y' => array('year', 'years', 'years'),
                'm' => array('month', 'months', 'months'),
                'd' => array('day', 'days', 'days'),
                'h' => array('hour', 'hours', 'hours'),
                'i' => array('minute', 'minutes', 'minutes'),
                's' => array('second', 'seconds', 'seconds'),
                'ago' => 'ago',
                'in' => 'in',
                'moment' => 'a moment'
            )
        ),
        'cs' => array(
            'date format' => 'j. F',
            'full date format' => 'j. F Y',
            'time format' => 'H:i:s',
            'weekdays' => array(
                'Sunday' => 'neděle',
                'Monday' => 'pondělí',
                'Tuesday' => 'úterý',
                'Wednesday' => 'středa',
                'Thursday' => 'čtvrtek',
                'Friday' => 'pátek',
                'Saturday' => 'sobota'
            ),
            'months' => array(
                'January' => 'ledna',
                'February' => 'února',
                'March' => 'března',
                'April' => 'dubna',
                'May' => 'května',
                'June' => 'června',
                'July' => 'července',
                'August' => 'srpna',
                'September' => 'září',
                'October' => 'října',
                'November' => 'listopadu',
                'December' => 'prosince'
            ),
            'time ago' => array(
                'y' => array('rok', 'roky', 'let'),
                'm' => array('měsíc', 'měsíce', 'měsíců'),
                'd' => array('den', 'dny', 'dnů'),
                'h' => array('hodina', 'hodiny', 'hodin'),
                'i' => array('minuta', 'minuty', 'minut'),
                's' => array('vteřina', 'vteřiny', 'vteřin'),
                'ago' => 'zpátky',
                'in' => 'za',
                'moment' => 'okamžik'
            )
        )
    );

    /** Converts ", ', &, <, > in $string to &quot; &#039/&apos; &lt;, &gt; respectively
     * @return string
     */
    public static function h($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }

    // e.g. unset($a); echo Tools::set($a); // false
    // e.g. $a = 0; echo Tools::set($a); // false
    // e.g. $a = 5; echo Tools::set($a); // true
    // e.g. unset($a); echo Tools::set($a, 5); // returns 5, $a = 5
    // e.g. $a = 0; echo Tools::set($a, 5); // returns 5, $a = 5
    // e.g. $a = 5; echo Tools::set($a, 6); // returns 5, $a = 5
    public static function set(&$a, $b = null)
    {
        if (func_num_args() == 1) {
            return isset($a) && $a ? $a : false;
        }
        return $a = (isset($a) && $a ? $a : $b);
    }

    /** Return first non-zero parameter passed to this function, or parameter #1
     * @param mixed tested value
     * @return mixed
     * For just two arguments use: $a ?: $b;
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

    /** Return first non-null parameter passed to this function, or null
     * @param mixed value tested for being null
     * @param mixed value returned if parameter #1 is null
     * @param mixed ...
     * @return mixed
     */
    public static function ifnull($a, $b)
    {
        foreach (func_get_args() as $arg) {
            if (!is_null($arg)) {
                return $arg;
            }
        }
        return $a;
    }

    /** Shortcut for isset($a) && $a == $b, esp. useful for long variables
     * @return bool
     */
    public static function equal(&$a, $b)
    {
        return isset($a) && $a === $b;
    }

    /** Shortcut for isset($a) && !empty($a), esp. useful for long variables
     * @return bool
     */
    public static function nonempty(&$a)
    {
        return isset($a) && !empty($a);
    }

    /** Shortcut for isset($a) && $a, esp. useful for long variables
     * @return bool
     */
    public static function nonzero(&$a)
    {
        return isset($a) && $a;
    }

    /** Shortcut for isset($a) ? $a : $b
     * @return mixed
     */
    public static function ifset(&$a, $b = null)
    {
        return isset($a) ? $a : $b;
    }

    /** Shortcut for $a = isset($a) ? $a : $b;
     * @return mixed
     */
    public static function setifnotset(&$a, $b = null)
    {
        $a = isset($a) ? $a : $b;
    }

    /** Shortcut for if (isset($a) && is_null($a)) $a = $b; useful for long variables
     */
    public static function setifnull(&$a, $b = null)
    {
        return $a = isset($a) ? (is_null($a) ? $b : $a) : $b;
    }

    /** Shortcut for if (isset($a) && !$a) $a = $b; useful for long variables
     */
    public static function setifempty(&$a, $b = null)
    {
        return $a = isset($a) ? ($a ?: $b) : $b;
    }

    /** Shortcut for isset($a) && is_scalar($a)
     * @return bool
     */
    public static function setscalar(&$a)
    {
        return isset($a) && is_scalar($a);
    }

    /** Shortcut for isset($a) && is_array($a)
     * @return bool
     */
    public static function setarray(&$a)
    {
        return isset($a) && is_array($a);
    }

    // if $text is set and non-zero, return it with prefix and postfix, return $else otherwise
    public static function wrap($text, $prefix, $postfix = '', $else = '')
    {
        if (isset($text) && $text) {
            return $prefix . $text . $postfix;
        }
        return $else;
    }

    // return true if $n is among given parameters (more than one can be given)
    public static function among($n, $m)
    {
        $args = func_get_args();
        array_shift($args);
        return in_array($n, $args, true); // strict comparison
    }

    /** Return true if $text begins with $beginning
     * @param string $text text to test
     * @param mixed $beginning string (for one) or array (for more) beginnings to test against
     * @param bool $caseSensitive case sensitive searching?
     * @param string $encoding internal encoding
     */
    public static function begins($text, $beginning, $caseSensitive = true, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        if (!is_array($beginning)) {
            $beginning = array($beginning);
        }
        if ($caseSensitive) {
            foreach ($beginning as $value) {
                if (mb_substr($text, 0, mb_strlen($value), $encoding) === $value) {
                    return true;
                }
            }
        } else {
            foreach ($beginning as $value) {
                if (mb_strtolower(mb_substr($text, 0, mb_strlen($value)), $encoding) === mb_strtolower($value)) {
                    return true;
                }
            }
        }
        return false;
    }

    /** Return true if $text ends with $ending
     * @param string $text text to test
     * @param mixed $ending string (for one) or array (for more) endings to test against
     * @param bool $caseSensitive case sensitive searching?
     * @param string $encoding internal encoding
     */
    public static function ends($text, $ending, $caseSensitive = true, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        if (!is_array($ending)) {
            $ending = array($ending);
        }
        if ($caseSensitive) {
            foreach ($ending as $value) {
                if (mb_substr($text, -mb_strlen($value), null, $encoding) === $ending) {
                    return true;
                }
            }
        } else {
            foreach ($ending as $value) {
                if (mb_strtolower(mb_substr($text, -mb_strlen($ending), $encoding)) === mb_strtolower($ending)) {
                    return true;
                }
            }
        }
    }

    /** Add a session message (i.e. a result of an data-changing operation).
     * @param string type one of 'success', 'info', 'danger', 'warning'
     * @param string message itself, in well-formatted HTML
     * @param bool true --> then call showMessages()
     */
    public static function addMessage($type, $message, $show = false)
    {
        $_SESSION['messages'] = is_array($_SESSION['messages']) ? $_SESSION['messages'] : array();
        $_SESSION['messages'] []= array($type, $message);
        if ($show) {
            self::showMessages();
        }
    }

    /** Return or show session message variables as HTML <div>s and unset them.
     * @param bool echo the messages immediately?
     * @return array with session messages or void if $echo == true
     */
    public static function showMessages($echo = true)
    {
        $tmp = ' href="#" onclick="event.preventDefault(); this.parentNode.style.display=\'none\'" aria-hidden="true"';
        $ICONS = array(
            'success' => '<a class="glyphicon glyphicon-ok-sign fa fa-check-circle"' . $tmp . '></a>',
            'danger' => '<a class="glyphicon glyphicon-exclamation-sign fa fa-times-circle"' . $tmp . '></a>',
            'warning' => '<a class="glyphicon glyphicon-remove-sign fa fa-exclamation-circle"' . $tmp . '></a>',
            'info' => '<a class="glyphicon glyphicon-info-sign fa fa-info-circle"' . $tmp . '></a>'
        );
        $_SESSION['messages'] = isset($_SESSION['messages']) && is_array($_SESSION['messages']) ? $_SESSION['messages'] : array();
        $result = '';
        foreach ((array)$_SESSION['messages'] as $key => $message) {
            if (isset($message[0], $message[1])) {
                if ($message[0] == 'error') {
                    $message[0] = 'danger';
                }
                $result .= '<div class="alert alert-dismissible alert-' . self::h($message[0]) . '">' . $ICONS[$message[0]] . ' ' . $message[1] . '</div>' . PHP_EOL;
            }
            unset($_SESSION['messages'][$key]);
        }
        if ($echo) {
            echo $result;
        } else {
            return $result;
        }
    }

    /** HTML notation for <option> filled with given parameters
     * @param mixed value
     * @param string text
     * @param mixed default
     * @param bool disabled */
    public static function htmlOption($value, $text, $default = null, $disabled = false)
    {
        return '<option' . ($disabled ? '' : ' value="' . self::h($value) . '"')
            . ($value === $default ? ' selected="selected"' : '')
            . ($disabled ? ' disabled="disabled"' : '')
            . '>' . self::h($text) . '</option>' . PHP_EOL;
    }

    /** HTML notation for <select>, options given either as an array or a SQL query.
     * @param string name
     * @param array values
     * @param string default value
     * @param array (optional) options
     *  [prepend], [append] arrays to prepend/append
     *  [class], [id], [onchange]... - to be added into the <select>
     */
    public static function htmlSelect($name, $values, $default, $options = array())
    {
        $result = '<select name="' . self::h($name) . '"';
        foreach ($options as $key => $value) {
            if (!self::among($key, 'name', 'prepend', 'append')) {
                $result .= ' ' . $key . '="' . self::h($value) . '"';
            }
        }
        $result .= '>' . PHP_EOL . self::htmlSelectAppend(@$options['prepend'], $default);
        foreach ((array)$values as $key => $value) {
            $result .= self::htmlOption($key, $value, $default);
        }
        return $result . self::htmlSelectAppend(@$options['append'], $default) . '</select>' . PHP_EOL;
    }

    protected static function htmlSelectAppend($array, $default)
    {
        if (!is_array($array)) {
            return'';
        }
        $result = '';
        foreach ($array as $key => $value) {
            $i = explode("\0", $value);
            $result .= self::htmlOption($i[0], $i[1], $default);
        }
        return $result;
    }

    /** HTML notation for one or more <input type=radio> element(s) filled with given parameters
     * @param string name attribute of the element
     * @param array associative array of value=>label pairs
     * @param scalar value that should be checked
     * @param array options
     *     [separator] - between items,
     *     [offset] - start index for the "id" attributes (0 by default),
     *     [radio-class] - optional class for <input type=radio>,
     *     [label-class] - optional class for <label>,
     * @return string HTML code */
    public static function htmlRadio($name, $input, $value = null, $options = array())
    {
        $result = '';
        if (!is_array($input)) {
            $input = array($input);
        }
        if (is_array($value)) {
            $value = @$value[$name];
        }
        $name = self::h($name);
        $i = +@$options['offset'];
        foreach ($input as $k => $v) {
            $result .= @$options['separator'] . '<input type="radio" name="' . $name . '" value="' . self::h($k) . '"'
                . ($k === $value ? ' checked="checked"' : '')
                . ' id="' . $name . '-' . $i . '"'
                . self::wrap(@$options['radio-class'], ' class="', '"');
            foreach ($options as $k2 => $v2) {
                if (!in_array($k2, array('separator', 'offset', 'radio-class', 'label-class', 'checked', 'id', 'name', 'value')) && !is_null($v2)) {
                    $result .= ' ' . $k2 . ($v2 === true ? '' : '="' . self::escapeJs($v2) . '"');
                }
            }
            $result .= '/> ';
            if ($v = self::h($v)) {
                $result .= '<label for="' . $name . '-' . $i . '" class="' . trim((@$_SESSION['fill'][$name] ? 'highlight' : '') . ' ' . @$options['label-class'], ' ') . '">' . $v . '</label>';
            }
            $i++;
        }
        return mb_substr($result, mb_strlen(@$options['separator']));
    }

    // HTML notation for the <textarea> tag. See html_textinput() for more info.
    public static function htmlTextarea($name, $content, $cols = 60, $rows = 5, $options = array())
    {
        $label = @$options['label'];
        unset($options['label']);
        $options = array_merge($options, array('cols' => $cols, 'rows' => $rows));
        return self::htmlTextInput($name, $label, $content, $options);
    }

    // HTML notation for the <input> tag. See html_textinput() for more info.
    public static function htmlInput($name, $label, $value, $options = array())
    {
        return self::htmlTextInput($name, $label, $value, $options);
    }

    /** HTML notation for the <input> or <textarea> tag
     * @param string name
     * @param string label, omitted if empty, translated if true
     * @param mixed value, either given directly or as an array in the [name] index
     * @param array options (if a string -> assume "type" attribute)
     *     [type] - input's type, 'text' by default
     *     [before], [between], [after] - HTML to insert before/between/after <label> and <input>
     *     [table] - refill [before], [between], [after] to make a table row
     *     [flag] - <code> -> prefix the label with a flag icon of given code
     *     [random-id] - append random number to the id attribute
     *     [label-after] - <tag> goes first, <label> after
     *     [label-html] - label given as raw HTML, don't escape HTML entities
     *     other tag's attributes - will be specified (except for NULLs)
     */
    protected static function htmlTextInput($name, $label, $value, $options = array())
    {
        $result = '';
        if (is_array($value)) {
            $value = @$value[$name];
        }
        if (is_string($options)) {
            $options = array('type' => $options);
        }
        if ($label && !self::nonempty($options['id'])) {
            $options['id'] = 'input-' . self::webalize($name);
        }
        if (self::nonempty($options['random-id'])) {
            $options['id'] .= '-' . rand(1e8, 1e9-1);
        }
        if (!isset($options['rows']) and !self::nonempty($options['type'])) {
            $options['type'] = 'text';
        }
        if (self::nonempty($options['table'])) {
            foreach (array('before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>') as $k=>$v) {
                self::setifempty($options[$k], $v);
            }
        }
        if (!self::nonempty($options['label-html'])) {
            $label = self::h($label);
        }
        self::setifempty($options['type'], 'text');
        if ($options['type'] == 'disabled') {
            $result .= '<input type="hidden" name="' . self::h($name) . '" value="' . self::h($value) . '"/>';
            $options['type'] = 'text';
            $options['disabled'] = 'disabled';
        }
        $result .= '<' . (isset($options['rows']) ? 'textarea' : 'input') . ' ';
        $options = array_merge($options, array('name' => self::h($name), 'value' => $value));
        foreach ($options as $k => $v) {
            if (is_string($k) && !is_null($v)
                && !self::among($k, 'before', 'between', 'after', 'table', 'flag', 'random-id', 'label-after', 'label-html', 'value')) {
                $result .= ' ' . $k . ($v === true ? '' : '="' . (mb_substr($k, 0, 2)=='on' ? self::h($v) : self::h($v)) . '"');
            }
        }
        $result .= isset($options['rows'])?'>' . self::h($value) . '</textarea>' : self::wrap(self::h($value), ' value="', '"') . '/>';
        $label = self::wrap($label, '<label for="' . self::h(@$options['id']) . '">', '</label>');
        return $result = self::setifempty($options['before']) . (self::nonempty($options['label-after']) ? $result : $label)
            . self::setifempty($options['between']) . (self::nonempty($options['label-after']) ? $label : $result) . self::setifempty($options['after']);
    }

    public static function webalize($string, $charlist = null, $lower = true)
    { //credit: Daniel Grudl (Nette)
        $string = strtr($string, '`\'"^~', '-----');
        if (ICONV_IMPL === 'glibc') {
            $string = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $string); // intentionally @
            $string = strtr($string, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
                "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
        } else {
            $string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string); // intentionally @
        }
        $string = str_replace(array('`', "'", '"', '^', '~'), '', $string);
        if ($lower === -1) {
            $string = strtoupper($string);
        } elseif ($lower) {
            $string = strtolower($string);
        }
        $string = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $string);
        $string = trim($string, '-');
        return $string;
    }

    public static function shortify($string, $limit, $ellipsis = '…')
    {
        if (mb_strlen($string) > $limit) {
            return mb_substr($string, 0, $limit) . $ellipsis;
        }
        return $string;
    }

    public static function escapeSQL($input)
    {
        //return mysqli_real_escape_string(/* mysqli object */, $input);
        return addslashes($input);
    }

    /** Escape identifier (column, table, database, ..) in MySQL (or compatible)
     * @param string $id identifier
     * @return string properly escaped identifier
     */
    public static function escapeDbIdentifier($id)
    {
        return '`' . str_replace('`', '``', $id) . '`';
    }

    public static function escapeIn($input)
    {
        if (is_array($input)) {
            $result = '';
            foreach ($input as $item) {
                $result .= ',"' . self::escapeSQL($item) . '"';
            }
            return substr($result, 1);
        }
        preg_match_all('~([-\+]?(0x[0-9a-f]+|(0|[1-9][0-9]*)(\.[0-9]+)?(e[-\+]?[0-9]+)?)|\'(\.|[^\'])*\'|"(\.|[^"])*")~i', $input, $matches);
        return implode(',', $matches[0]);
    }

    public static function escapeJS($string)
    {
        return strtr($string, array(']]>' => ']]\x3E', '/' => '\/', "\\" => '\\', '"' => '\"', "'" => '\''));
    }

    public static function redir($url = '')
    {
        $url = self::wrap($url,
            ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'],
            '',
            '?' . $_SERVER['QUERY_STRING']
        );
        header("Location: $url", true, 303);
        header('Connection: close');
        die('<script type="text/javascript">window.location=' . json_encode($url) . ";</script>\n"
            . '<a href=' . urlencode($url) . '>&rarr;</a>'
        );
    }

    //example: arrayListed(array("Levi's","Procter & Gamble"),1,", ","<b>","</b>") --> <b>Levi's</b>, <b>Procter &amp; Gamble</b>
    //flags: +1=htmlentities, +2=escape string, +4=escapeJs, +8=intval, +16=(float), +32=ignore empty +64=` -> ``, +128=array_keys, +10 = quote LIKE, +18 = preg_quote
    public static function arrayListed($array, $flags = 0, $glue = ',', $before = '', $after = '')
    {
        $result = '';
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if ($flags & 128) {
                    $v = $k;
                }
                if ($flags & 64) {
                    $v = str_replace('`', '``', $v);
                }
                if ($flags & 1) {
                    $v = htmlspecialchars($v, ENT_QUOTES);
                }
                if ($flags & 2) {
                    if ($flags & 8) {
                        $v = strtr($v, array('"' => '\"', "'" => "\\'", "\\" => "\\\\", '%' => '%%', '_' => '\_')); //like
                    } elseif ($flags&16) {
                        $v = preg_quote($v);
                    } else {
                        $v = self::escapeSQL($v);
                    }
                }
                if ($flags & 4) {
                    $v = self::escapeJS($v);
                }
                if ($flags & 8) {
                    $v = intval($v);
                }
                if ($flags & 16) {
                    $v = (float)$v;
                }
                if (!($flags & 32) || $v) {
                    $result .= "$glue$before$v$after";
                }
            }
        }
        return mb_substr($result, mb_strlen($glue));
    }

    /** walk through given array and extract only selected keys
     * @param array array to walk through
     * @param mixed key(s) to extract
     * @return array
     * e.g. arrayConfineKeys(array(array('name'=>'John', 'age'=>43), array('name'=>'Lucy', 'age'=>28)), 'age') --> array(43, 28)
     */
    public static function arrayConfineKeys($array, $keys)
    {
        $keys = (array)$keys;
        $result = array();
        if (is_array($array)) {
            foreach ($array as $arrayKey => $item) {
                $tmp = array();
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

    // e.g. exploded('|', 'apple|banana|kiwi', 1) -> banana
    public static function exploded($separator, $string, $key = 0)
    {
        $result = explode($separator, $string);
        return isset($result[$key]) ? $result[$key] : null;
    }

    public static function cutTill(&$haystack, $needle)
    {
        if (($p = strpos($haystack, $needle)) !== false) {
            $haystack = substr($haystack, 0, $p);
        }
    }

    /** Make a cURL call and return its response. Supposes running cURL extension.
     * @param string URL to call
     * @param array changing CURL options
     * @return string response or null if curl_errno() is non-zero
     */
    public static function curlCall($url, $options = array())
    {
        $curlOptions = array(
            CURLOPT_URL => $url,
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
        );
        $ch = curl_init();
        foreach ($options as $key => $value) {
            $curlOptions[$key] = $value;
        }
        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        return curl_errno($ch) ? null : $response;
    }

    /** Return actual QUERY_STRING changed by suggested amendments.
     * @param array changes, parameters to add/modify, null as a value signifies omitting the key:value pair
     * @param bool htmlspecialchars - apply htmlspecialchars()?
     */
    public static function urlChange($changes, $htmlspecialchars = false)
    {
        parse_str($_SERVER['QUERY_STRING'], $parameters);
        foreach ((array)$changes as $key => $value) {
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

    /** How much time ago $datetime was according to the current time (Czech)
     * @param string or int date (and time) as string or as integer timestamp
     * @param string language code as key to Tools::LOCALE
     * @return string text representation
     */
    public static function relativeTime($datetime, $language = 'en')
    {
        $diff = new DateTime($datetime);
        $diff = $diff->diff(new DateTime());
        $result = '';
        foreach (explode(' ', 'y m d h i s') as $part) {
            $result .= ($diff->{$part} ? ',' . $diff->{$part} . ' '
                . self::plural($diff->$part, self::$LOCALE[$language]['time ago'][$part][0], self::$LOCALE[$language]['time ago'][$part][1], self::$LOCALE[$language]['time ago'][$part][2]) : '');
        }
        $result = explode(',', $result);
        $result = array_slice($result, 1, 2) ?: array(self::$LOCALE[$language]['time ago']['moment']);
        return ($diff->invert ? self::$LOCALE[$language]['time ago']['in'] . ' ' : '') . implode(', ', $result) . ($diff->invert ? '' : ' ' . self::$LOCALE[$language]['time ago']['ago']);
    }

    /** Date (and time) locally.
     * @param int date/time
     * @param string language code as key to Tools::LOCALE
     * @return string
     */
    public static function localeDate($datetime, $language = 'en', $includeTime = true)
    {
        if (is_string($datetime)) {
            $datetime = strtotime($datetime);
        }
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

    /** Date locally.
     * @param int date/time
     * @param string language code as key to Tools::LOCALE
     * @return string
     */
    public static function localeTime($datetime, $language = 'en', $includeTime = false)
    {
        if (is_string($datetime)) {
            $datetime = strtotime($datetime);
        }
        $year = date('Y', $datetime);
        $currentYear = date('Y');
        $result = date(self::$LOCALE[$language]['date format'], $datetime);
        if (isset(self::$LOCALE[$language]['months'])) {
            $result = strtr($result, self::$LOCALE[$language]['months']);
        }
        if ($year != $currentYear) {
            $result .= " $year";
        }
        if ($includeTime) {
            $result .= ' ' . date(self::$LOCALE[$language]['time format'], $datetime);
        }
        return $result;
    }

    /** Plural form according to number.
     * @param int number
     * @param string form for 1
     * @param string form for 2, 3, or 4
     * @param string form for 5+
     * @param string form for 0
     * @return string result form
     */
    public static function plural($number, $form1, $form234, $form5plus, $form0 = false)
    {
        $number = abs((int)$number);
        if ($form0 !== false && !$number) {
            return $form0;
        }
        return $number >= 5 ? $form5plus : ($number == 1 ? $form1 : $form234);
    }

    /** Add an message according to given result
     * @param bool success
     * @param string success message
     * @param string error message
     */
    public static function resolve($success, $successMessage, $errorMessage)
    {
        if ($success) {
            self::addMessage('success', $successMessage);
        } else {
            self::addMessage('error', $errorMessage);
        }
    }

    /** Take a hash of arrays and rebase its keys to first item of each array's array
     * @param array array
     * @param mixed index
     * [[0=>581, 1=>'Apple', 2=>'Fruits'], [0=>46, 1=>'Tomato', 2=>'Vegetables']] --> [581=>[1=>'Apple', 'Fruits'], 45=>[1=>'Tomato', 'Vegetables']]
     * [[0=>581, 1=>'Apple'], [0=>45, 1=>'Banana']] --> [581=>'Apple', 45=>'Banana']
     */
    public static function arrayReindex($array, $index = 0)
    {
        $result = array();
        if (is_array($array) && count($array)) {
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
        }
        return $result;
    }

    /** Subtract items from given array
     * @param array array to remove items from
     * @param mixed either array containing values that are keys to be removed
     *              or key(s) to be removed
     * Note: this function can have more arguments - argument #3, 4.. are taken as further items to remove
     * Note: no error, warning or notice is thrown if item in array is not found.
     * @return array with removed keys
     * e.g. $a = ['Apple', 'Pear', 'Kiwi']; Tools::arrayRemoveItems($a, ['Apple', 'Pear']) -> ['Kiwi'];
     *      Tools::arrayRemoveItems($a, 'Apple', 'Pear', 'Orange') -> ['Kiwi'];
     */
    public static function arrayRemoveItems($array1, $array2)
    {
        if (is_array($array2)) {
            foreach ($array2 as $i) {
                unset($array1[$i]);
            }
        } else {
            foreach (func_get_args() as $index => $arg) {
                if ($index) {
                    if (($key = array_search($arg, $array1)) !== false) {
                        unset($array1[$key]);
                    }
                }
            }
        }
        return $array1;
    }

    /** Return an array with keys same as its values. Doesn't solve duplicates.
     * @param array array
     * @return array result
     * E.g. arrayKeysAsValues(['Apple', 'Pear', 'Kiwi']) --> ['Apple'=>'Apple', 'Pear'=>'Pear', 'Kiwi'=>'Kiwi']
     */
    public static function arrayKeyAsValues($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$value] = $value;
        }
        return $result;
    }

    /** Generate random password of $length characters (letters, -, digits), 0O1lI are excluded.
     * @param int length of the password
     * @return string password
     */
    public static function randomPassword($length = 8)
    {
        static $chars = '23456789abcdefghijkmnopqrstuvwxyz-ABCDEFGHJKLMNPQRSTUVWXYZ';
        $rand = function_exists('random_int') ? 'random_int' : 'rand'; 
        $charsmax = strlen($chars) - 1;
        for ($i = 0, $result = ''; $i < $length; $i++) {
            $result .= substr($chars, $rand(0, $charsmax), 1);
        }
        return $result; 
    }

    public static function dump($args)
    {
        echo '<pre>';
        foreach (func_get_args() as &$arg) {
            var_dump($arg);
        }
        echo '</pre>';
    }

    /** Strip specified attributes of a given HTML/XML or its fragment
     * Requires DOMDocument, DOMXPath, DOMElement, DOMElementList classes
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
        foreach ((is_array($attributes) ? $attributes : array($attributes)) as $attribute) {
            $items = $domx->query("//*[@$attribute]");
            foreach($items as $item) {
                $item->removeAttribute($attribute);
            }
        }
        return substr($domd->saveXML(), 26, -6);
    }

}
