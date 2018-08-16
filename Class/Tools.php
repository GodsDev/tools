<?php
/**
 * A class with additional miscelaneous, general-purpose methods.
 */

namespace GodsDev\Tools;

class Tools
{
    /** var array locale setting for few methods in the class */
    static public $LOCALE = array(
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

    /**
     * Converts ", ', &, <, > in $string to &quot; &#039/&apos; &lt;, &gt; respectively
     *
     * @param string $string unescaped string 
     * @return string escaped string to use in HTML 
     */
    public static function h($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }

    /**
     * If called with just one parameter, returns given variable if it is set and non-zero, false otherwise.
     * If called with two parameters, assign the 2nd parameter to the 1st if the 1st variable is not set or not non-zero 
     *
     * @example unset($a); echo Tools::set($a); // false
     * @example $a = 0; echo Tools::set($a); // false
     * @example $a = 5; echo Tools::set($a); // 5
     * @example unset($a); echo Tools::set($a, 5); // returns 5, $a = 5
     * @example $a = 0; echo Tools::set($a, 5); // returns 5, $a = 5
     * @example $a = 4; echo Tools::set($a, 5); // returns 4, $a = 4
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
     * Return first non-zero parameter passed to this function, or the 1st parameter if all are empty
     *
     * @param mixed $a tested value
     * @param mixed $b option(s)
     * @return mixed
     * For just two arguments use: $a ?: $b;
     */
    public static function ifempty($a, $b)
    {
        foreach (func_get_args() as $arg) {
            if ($arg) {
                return $arg;
            }
        }
        return $a;
    }

    /**
     * Return first non-null parameter passed to this function, or null
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
     * Shortcut for isset($a) && $a == $b, esp. useful for long variables
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
     * Shortcut for isset($a) && !empty($a), esp. useful for long variables
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function nonempty(&$a)
    {
        return isset($a) && !empty($a);
    }

    /**
     * Shortcut for isset($a) && $a, esp. useful for long variables
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function nonzero(&$a)
    {
        return isset($a) && $a;
    }

    /**
     * Shortcut for isset($a) ? $a : $b
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
     * Shortcut for if (isset($a) && is_null($a)) $a = $b; useful for long variables
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
     * Shortcut for if (isset($a) && !$a) $a = $b; useful for long variables
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
     * Shortcut for isset($a) && is_scalar($a)
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function setscalar(&$a)
    {
        return isset($a) && is_scalar($a);
    }

    /**
     * Shortcut for isset($a) && is_array($a)
     *
     * @param mixed &$a tested variable
     * @return bool
     */
    public static function setarray(&$a)
    {
        return isset($a) && is_array($a);
    }

    /**
     * If $text is set and non-zero, return it with prefix and postfix around, return $else otherwise
     *
     * @param mixed $text value to be wrapped or replaced by $else
     * @param mixed $prefix
     * @param mixed $postfix (optional)
     * @param mixed $else (optional) value to be returned if $text is zero
     * @return string
     */
    public static function wrap($text, $prefix, $postfix = '', $else = '')
    {
        if (isset($text) && $text) {
            return $prefix . $text . $postfix;
        }
        return $else;
    }

    /**
     * Return true if $n is among given parameters (more than one can be given)
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
     * Return true if any of given argument variables are set (ie. pass isset())
     * Version for PHP below 5.6 is limited to 100 arguments.
     *
     * @example Tools::anyset($_GET['article'], $_GET['category'])
     * @param mixed &$n variable(s)
     * @return bool true if any (at least one) variable pass isset(), false otherwise
     */
    public static function anyset(&$n0, &$n1 = 0, &$n2 = 0, &$n3 = 0, &$n4 = 0, &$n5 = 0, &$n6 = 0, &$n7 = 0, &$n8 = 0, &$n9 = 0,
        &$n10 = 0, &$n11 = 0, &$n12 = 0, &$n13 = 0, &$n14 = 0, &$n15 = 0, &$n16 = 0, &$n17 = 0, &$n18 = 0, &$n19 = 0,
        &$n20 = 0, &$n21 = 0, &$n22 = 0, &$n23 = 0, &$n24 = 0, &$n25 = 0, &$n26 = 0, &$n27 = 0, &$n28 = 0, &$n29 = 0,
        &$n30 = 0, &$n31 = 0, &$n32 = 0, &$n33 = 0, &$n34 = 0, &$n35 = 0, &$n36 = 0, &$n37 = 0, &$n38 = 0, &$n39 = 0,
        &$n40 = 0, &$n41 = 0, &$n42 = 0, &$n43 = 0, &$n44 = 0, &$n45 = 0, &$n46 = 0, &$n47 = 0, &$n48 = 0, &$n49 = 0,
        &$n50 = 0, &$n51 = 0, &$n52 = 0, &$n53 = 0, &$n54 = 0, &$n55 = 0, &$n56 = 0, &$n57 = 0, &$n58 = 0, &$n59 = 0,
        &$n60 = 0, &$n61 = 0, &$n62 = 0, &$n63 = 0, &$n64 = 0, &$n65 = 0, &$n66 = 0, &$n67 = 0, &$n68 = 0, &$n69 = 0,
        &$n70 = 0, &$n71 = 0, &$n72 = 0, &$n73 = 0, &$n74 = 0, &$n75 = 0, &$n76 = 0, &$n77 = 0, &$n78 = 0, &$n79 = 0,
        &$n80 = 0, &$n81 = 0, &$n82 = 0, &$n83 = 0, &$n84 = 0, &$n85 = 0, &$n86 = 0, &$n87 = 0, &$n88 = 0, &$n89 = 0,
        &$n90 = 0, &$n91 = 0, &$n92 = 0, &$n93 = 0, &$n94 = 0, &$n95 = 0, &$n96 = 0, &$n97 = 0, &$n98 = 0, &$n99 = 0)
    {
        for ($i = 0, $len = func_num_args(); $i < $len; $i++) {
            $var = "n$i";
            if (isset($$var)) {
                return true;
            }
        }
        return false;
    }
    /* version with "..." requires PHP 5.6+
    public static function anyset(&...$args)
    {
        foreach ($args as $arg) {
            if (isset($arg)) {
                return true;
            }
        }
        return false;
    }
    */

    /**
     * Return true if $text begins with $beginning
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
        if (!is_array($beginning)) {
            $beginning = array($beginning);
        }
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
     * Return true if $text ends with $ending
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
        if (!is_array($ending)) {
            $ending = array($ending);
        }
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
     * Add a session message (i.e. a result of an data-changing operation).
     *
     * @param string $type type one of 'success', 'info', 'danger', 'warning'
     * @param string $message message itself, in well-formatted HTML
     * @param bool $show (optional) true --> then call showMessages()
     * @return void
     */
    public static function addMessage($type, $message, $show = false)
    {
        $_SESSION['messages'] = self::setarray($_SESSION['messages']) ? $_SESSION['messages'] : array();
        $_SESSION['messages'] []= array($type == 'error' ? 'danger' : $type, $message);
        if ($show) {
            self::showMessages();
        }
    }

    /**
     * Return or show session message variables as HTML <div>s and unset them. Bootstrap styling is used.
     *
     * @param bool $echo (optional) echo the messages immediately?
     * @return void or array with session messages or void if $echo == true
     */
    public static function showMessages($echo = true)
    {
        $ICONS = array(
            'success' => 'glyphicon glyphicon-ok-sign fa fa-check-circle',
            'danger' => 'glyphicon glyphicon-exclamation-sign fa fa-times-circle',
            'warning' => 'glyphicon glyphicon-remove-sign fa fa-exclamation-circle',
            'info' => 'glyphicon glyphicon-info-sign fa fa-info-circle'
        );
        $_SESSION['messages'] = isset($_SESSION['messages']) && is_array($_SESSION['messages']) ? $_SESSION['messages'] : array();
        $result = '';
        foreach ((array)$_SESSION['messages'] as $key => $message) {
            if (isset($message[0], $message[1])) {
                $result .= '<div class="alert alert-dismissible alert-' . self::h($message[0]) . '" role="alert">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    . '<i class="' . $ICONS[$message[0]] . ' mr-1"></i> ' . $message[1] . '</div>' . PHP_EOL;
            }
            unset($_SESSION['messages'][$key]);
        }
        if ($echo) {
            echo $result;
        } else {
            return $result;
        }
    }

    /**
     * HTML notation for <option> filled with given parameters
     *
     * @param mixed $value
     * @param string $text
     * @param mixed $default (optional)
     * @param bool $disabled (optional)
     * @return string
     */
    public static function htmlOption($value, $text, $default = null, $disabled = false)
    {
        return '<option' . ($disabled ? '' : ' value="' . self::h($value) . '"')
            . ($value === $default ? ' selected="selected"' : '')
            . ($disabled ? ' disabled="disabled"' : '')
            . '>' . self::h($text) . '</option>' . PHP_EOL;
    }

    /**
     * HTML notation for <select>, options given either as an array or a SQL query.
     * @example htmlSelect('agree', ['Y'=>'Yes', 'N'=>'No'], 'N', ['class'=>'form-control']) -->
     *          <select name="agree" class="form-control"><option value="Y">Yes</option><option value="N" selected="selected">No</option></select>
     *
     * @param string $name
     * @param array $values
     * @param string $default value
     * @param mixed[] options (optional)
     *  [prepend] array of options to prepend before $values
     *  [append] array of options to append after $values
     *  [class], [id], [onchange], ... optional HTML attributes to add to the <select> notation
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

    // used in ::htmlSelect()
    protected static function htmlSelectAppend($array, $default)
    {
        if (!is_array($array)) {
            return'';
        }
        $result = '';
        foreach ($array as $key => $value) {
            $option = is_string($value) ? explode("\0", $value) : array_values($value); 
            $result .= self::htmlOption($option[0], $option[1], $default);
        }
        return $result;
    }

    /**
     * HTML notation for one or more <input type=radio> element(s) filled with given parameters
     *
     * @param string $name name attribute of the element
     * @param array $input associative array of value=>label pairs
     * @param scalar $value value that should be checked
     * @param mixed[] $options (optional)
     *     [separator] - between items,
     *     [offset] - start index for the "id" attributes (0 by default),
     *     [radio-class] - optional class for <input type=radio>,
     *     [label-class] - optional class for <label>,
     * @return string HTML code
     */
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

    /** 
     * HTML notation for the <textarea> tag. See html_textinput() for more info.
     *
     * @param string $name
     * @param string $content
     * @param int $cols (optional)
     * @param int $rows (optional)
     * @param mixed[] $options (optional) See self::htmlTextInput() for more info
     */
    public static function htmlTextarea($name, $content, $cols = 60, $rows = 5, $options = array())
    {
        $label = @$options['label'];
        unset($options['label']);
        $options = array_merge($options, array('cols' => $cols, 'rows' => $rows));
        return self::htmlTextInput($name, $label, $content, $options);
    }

    /**
     * HTML notation for the <input> tag. See self::htmlTextinput() for more info.
     */
    public static function htmlInput($name, $label, $value, $options = array())
    {
        return self::htmlTextInput($name, $label, $value, $options);
    }

    /**
     * HTML notation for the <input> or <textarea> tag. Used by self::htmlInput() and self::htmlTextarea()
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
            $options['id'] = Tools::set($options['id'], 'input') . '-' . rand(1e8, 1e9-1);
        }
        if (!isset($options['rows']) and !self::nonempty($options['type'])) {
            $options['type'] = 'text';
        }
        if (self::nonempty($options['table'])) {
            foreach (array('before' => '<tr><td>', 'between' => '</td><td>', 'after' => '</td></tr>') as $k=>$v) {
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
                && !self::among($k, 'before', 'between', 'after', 'table', 'random-id', 'label-after', 'label-html', 'label-class', 'value')) {
                $result .= ' ' . $k . ($v === true ? '' : '="' . (mb_substr($k, 0, 2)=='on' ? self::h($v) : self::h($v)) . '"');
            }
        }
        $result .= isset($options['rows']) ? '>' . self::h($value) . '</textarea>' : ' value="' . self::h($value) . '"/>';
        $label = $label === '' || $label === false || $label === null ? '' : '<label' . self::wrap(self::h(self::set($options['id'])), ' for="', '"') . self::wrap(self::h(self::set($options['label-class'])), ' class="', '"') . '>' . $label . '</label>';
        return $result = self::setifempty($options['before']) . (self::nonempty($options['label-after']) ? $result : $label)
            . self::setifempty($options['between']) . (self::nonempty($options['label-after']) ? $label : $result) . self::setifempty($options['after']);
    }

    /**
     * String conversion: diacritics --> ASCII, everything else than a-z, A-Z, 0-9, "_", "-" --> "-", then "--" --> "-" and "-" at the ends get trimmed 
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

    /**
     * Shorten a string to given $limit of characters (with $ellipsis concatenated at the end), shorter strings are returned the same.
     *
     * @param string $string
     * @param int $limit
     * @param string $ellipsis (optional) string to signify ellipsis
     * @return string
     */
    public static function shortify($string, $limit, $ellipsis = '…')
    {
        if (mb_strlen($string) > $limit) {
            return mb_substr($string, 0, $limit) . $ellipsis;
        }
        return $string;
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
        return addslashes($input);
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
                $result .= ',"' . self::escapeSQL($item) . '"';
            }
            return substr($result, 1);
        }
        preg_match_all('~([-\+]?(0x[0-9a-f]+|(0|[1-9][0-9]*)(\.[0-9]+)?(e[-\+]?[0-9]+)?)|\'(\.|[^\'])*\'|"(\.|[^"])*")~i', $input, $matches);
        return implode(',', $matches[0]);
    }

    /**
     * Escape a string to use in <script type="text/javascript">
     *
     * @param string $string
     * @return string escaped string
     */
    public static function escapeJS($string)
    {
        return strtr($string, array(']]>' => ']]\x3E', '/' => '\/', "\\" => '\\', '"' => '\"', "'" => '\''));
    }

    /**
     * Perform a HTTP redirection to a given URL.
     *
     * @param string $url (optional) URL to redirect to. Default value "" means the current URL
     * @return void
     */
    public static function redir($url = '')
    {
        $url = self::wrap($url,
            ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'],
            '',
            '?' . $_SERVER['QUERY_STRING']
        );
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        header("Location: $url", true, 303);
        header('Connection: close');
        die('<script type="text/javascript">window.location=' . json_encode($url) . ";</script>\n"
            . '<a href=' . urlencode($url) . '>&rarr;</a>'
        );
    }

    /**
     * Like implode() but with more options.
     * @example: arrayListed(array("Levi's", "Procter & Gamble"), 1, ", ", "<b>", "</b>") --> <b>Levi's</b>, <b>Procter &amp; Gamble</b>
     *
     * @param mixed[] $array
     * @param int $flags (optional) set of the following bits
     *      +1=htmlentities, +2=escape string, +4=escapeJs, +8=intval, +16=(float), +32=ignore empty +64=` -> ``, +128=array_keys
     *      special combinations: +2+8 = quote LIKE, +2+16 = preg_quote
     * @param string $glue (optional)
     * @param string $before (optional)
     * @param string $after (optional)
     * @return string
     */
    const ARRL_HTML = 1;
    const ARRL_ESC = 2;
    const ARRL_JS = 4;
    const ARRL_INT = 8;
    const ARRL_FLOAT = 16;
    const ARRL_EMPTY = 32;
    const ARRL_DB_ID = 64;
    const ARRL_KEYS = 128;
    const ARRL_LIKE = ARRL_ESC | ARRL_INT;
    const ARRL_PREGQ = ARRL_ESC | ARRL_FLOAT;
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
                        $v = strtr($v, array('"' => '\"', "'" => "\\'", "\\" => "\\\\", '%' => '%%', '_' => '\_')); //like
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
                    $result .= "$glue$before$v$after";
                }
            }
        }
        return mb_substr($result, mb_strlen($glue));
    }

    /**
     * Walk through given array and extract only selected keys
     * @example $employees = [[name=>John, age=>43], [name=>Lucy, age=>28]]
     *          arrayConfineKeys($employees, 'age') --> array(43, 28)
     *
     * @param mixed[] $array Array to walk through
     * @param mixed $keys key or array of keys to extract
     * @return array
     */
    public static function arrayConfineKeys($array, $keys)
    {
        $keys = is_array($keys) ? $keys : array($keys);
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

    /**
     * Extract a string separated by a given separator on a given position 
     * @example exploded('-', '1996-07-30', 2) -> '30'
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
     * Make a cURL call and return its response. Supposes running cURL extension.
     *
     * @param string $url URL to call
     * @param mixed[] options (optional) changing CURL options
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
        curl_close($ch);
        return curl_errno($ch) ? null : $response;
    }

    /**
     * Return actual QUERY_STRING changed by suggested amendments.
     *
     * @param array $changes parameters to add/modify, null as a value signifies omitting the key:value pair
     * @param bool $htmlspecialchars (optional) apply htmlspecialchars()?
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

    /**
     * How much time ago $datetime was according to the current time (Czech)
     *
     * @param mixed $datetime elapsed time as a string or an integer timestamp
     * @param string $language (optional) language code as key to Tools::LOCALE
     * @return string text representation
     */
    public static function relativeTime($datetime, $language = 'en')
    {
        $diff = new DateTime($datetime);
        $diff = $diff->diff(new DateTime());
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
        $result = array_slice($result, 1, 2) ?: array(self::$LOCALE[$language]['time ago']['moment']);
        return ($diff->invert ? self::$LOCALE[$language]['time ago']['in'] . ' ' : '') . implode(', ', $result) . ($diff->invert ? '' : ' ' . self::$LOCALE[$language]['time ago']['ago']);
    }

    /**
     * Date (and time) locally.
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
     * Plural form of a string according to a suplied number.
     *
     * @example plural(1, 'child', false, 'children') --> 'child'
     * @example plural(2, 'Jahr', 'Jahre', 'Jahren') --> 'Jahre'
     *
     * @param int $amount amount
     * @param string $form1 form for amount of 1
     * @param string $form234 form for amount of 2, 3, or 4 (if false is given, $form5plus will be used)
     * @param string $form5plus form for amount of 5+
     * @param string $form0 (optional) form for amount of 0 (omit it or submit false to use $form5plus instead)
     * @return string result form
     */
    public static function plural($amount, $form1, $form234, $form5plus, $form0 = false)
    {
        $amount = abs((int)$amount);
        $form234 = $form234 !== false ? $form234 : $form5plus;
        $form0 = $form0 !== false ? $form0 : $form5plus;
        return $amount >= 5 ? $form5plus : ($amount == 1 ? $form1 : $form234);
    }

    /**
     * Add an message according to given result
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
     * Take a hash of arrays and rebase its keys to the first item of each array's array.
     * If resulting items have only one item, get rid of array() 
     *
     * @example $a = [[id=>5, name=>John, surname=>Doe], [id=>6, name=>Jane, surname=>Dean]]
     *          $b = [[id=>5, name=>John], [id=>6, name=>Jane]]
     *          arrayReindex($a, 'id') --> [5=>[name=>John, surname=>Doe], 6=>[name=>Jane, surname=>Dean]]
     *          arrayReindex($b, 'id') --> [5=>John, 6=>Jane]
     *
     * @param array $array
     * @param mixed $index (optional)
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

    /**
     * Subtract items from given array.
     *
     * @example $a = ['Apple', 'Pear', 'Kiwi']
     *      Tools::arrayRemoveItems($a, ['Apple', 'Pear']) -> ['Kiwi'];
     *      Tools::arrayRemoveItems($a, 'Apple', 'Pear', 'Orange') -> ['Kiwi'];
     *
     * @param array $array1 array to remove items from
     * @param mixed $array2 either array containing values that are keys to be removed
     *              or key(s) to be removed
     * @return array with removed keys
     *
     * Note: this function can have more arguments - argument #3, 4.. are taken as further items to remove
     * Note: no error, warning or notice is thrown if item in array is not found.
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
                    while (($key = array_search($arg, $array1)) !== false) {
                        unset($array1[$key]);
                    }
                }
            }
        }
        return $array1;
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
        $result = array();
        foreach ($array as $key => $value) {
            $result[$value] = $value;
        }
        return $result;
    }

    /**
     * Generate random password of $length characters (letters, -, digits), 0O1lI are excluded.
     *
     * @param int $length (optional) length of the password
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
     * Strip specified attributes of a given HTML/XML or its fragment
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
        foreach ((is_array($attributes) ? $attributes : array($attributes)) as $attribute) {
            $items = $domx->query("//*[@$attribute]");
            foreach($items as $item) {
                $item->removeAttribute($attribute);
            }
        }
        return substr($domd->saveXML(), 26, -6); //@todo: suboptimal, version-sensitive
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
            $timeSlot += floor(time() / 30);
        }
        $data = str_pad(pack('N', $timeSlot), 8, "\0", STR_PAD_LEFT);
        $hash = hash_hmac('sha1', $data, $secret, true);
        $unpacked = unpack('N', substr($hash, ord(substr($hash, -1)) & 0xF, 4));
        return ($unpacked[1] & 0x7FFFFFFF) % 1000000;
    }

    /**
     * Inverse to str_getcsv() - return comma-separated-values out of given array
     * For parameter details see PHP's fputcsv()
     * edited version from https://gist.github.com/johanmeiring/2894568
     *
     * @param array $fields
     * @param string $delimiter (optional)
     * @param string $enclosure (optional)
     * @param string $escape_char (optional)
     * @result string CSV of given arguments
     */
    public static function str_putcsv(array $fields, string $delimiter = ',', string $enclosure = '"', string $escape_char = "\\")
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
     * Short cut for finding a substring within a string and returning all before (or false on no match)
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive (default: false)
     * @return string substring before $needle or false is $needle wasn't found
     */
    public static function str_before($haystack, $needle, $caseSensitive = false, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $function = $caseSensitive ? 'mb_stripos' : 'mb_strpos';
        if (($pos = $function($haystack, $needle, 0, $encoding)) === false) {
            return false;
        }
        return mb_substr($haystack, 0, $pos, $encoding);
    }

    /**
     * Short cut for finding a substring within a string and returning what follows (or false on no match)
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive (default: false)
     * @return string substring after $needle or false is $needle wasn't found
     */
    public static function str_after($haystack, $needle, $caseSensitive = false, $encoding = null)
    {
        $encoding = $encoding ?: mb_internal_encoding();
        $function = $caseSensitive ? 'mb_stripos' : 'mb_strpos';
        if (($pos = $function($haystack, $needle, 0, $encoding)) === false) {
            return false;
        }
        return substr($haystack, $pos + strlen($needle));
    }

    /**
     * Multibyte version of ucfirst()
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
     * Case-insensitive version of array_search()
     *
     * @param string $needle
     * @param array $haystack
     * @param bool $strict (optional)
     * @param mixed $encoding (optional)
     * @result mixed found key or false if needle not found
     */
    public static function array_search_i($needle, array $haystack, bool $strict = false, $encoding = null)
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
     * Case-insensitive version of in_array()
     *
     * @param string $needle
     * @param array $haystack
     * @param bool $strict (optional)
     * @param mixed $encoding (optional)
     * @result bool true/false whether the needle was found
     */
    public static function in_array_i($needle, array $haystack, bool $strict = false, $encoding = null)
    {
        $key = self::array_search_i($needle, $haystack, $strict, $encoding);
        return $key !== false && isset($haystack[$key]);
    }

    /**
     * Shortcut for checking value of given variable against given list and change it if it is not in it
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
     * Shortcut for checking value of given variable against given list and change if it is in it
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
     * Return a HTTP response split into headers and body
     *
     * @param string $response
     * @param array $options (optional)
     *        $options['JSON'] = non-zero - apply json_decode() on response body
     * @return array containing ['headers'] with HTTP headers and ['body'] with response body
     */
    public static function httpResponse($response, $options = array())
    {
        static $HEADERS_BODY_SEPARATOR = "\r\n\r\n";
        $result = array(
            'headers' => array(), 
            'body' => array()
        );
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
}
