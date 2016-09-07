<?php
namespace GodsDev\Tools;

class Tools
{
    public function __construct()
    {
    }
    
    public function h($s)
    {
        return htmlspecialchars($s, ENT_QUOTES);
    }
    
    public function ifempty($a, $b)
    { //for just two arguments use: $a?:$b;
        foreach (func_get_args() as $arg) {
            if ($arg) {
                return $arg;
            }
        }
        return $a;
    }
    
    public function ifnull($a, $b)
    {
        foreach (func_get_args() as $arg) {
            if (!is_null($arg)) {
                return $arg;
            }
        }
        return $a;
    }
    
    public function setifnull(&$a, $b)
    {
        return $a = is_null($a) ? $b : $a;
    }
    
    public function setifempty(&$a, $b)
    {
        return $a = ($a ? $a : $b);
    }

    public function wrap($n, $prefix, $postfix = '', $else = '')
    {
        if ($n) {
            return $prefix . $n . $postfix;
        }
        return $else;
    } 

    // return true if $n is among given parameters (more than one can be given)
    public function among($n, $m)
    {
        $a = func_get_args();
        array_shift($a);
        return array_search($n, $a) !== false;
    }
    
    /** Return or show session message variables as HTML <div>s and unset them.
     * @param bool echo the messages?
     */
    public function showMessages($echo = true)
    {
        $result = '';
        foreach (array('msg', 'wrn', 'err') as $i) {
            if (@$_SESSION[$i]) {
                $result .= '<div class="' . $i . '"><a href="javascript:;" onclick="this.parentNode.style.display=\'none\';" class="hide_message"><span> </span></a>' . @$_SESSION[$i] . '</div>' . PHP_EOL; 
                unset($_SESSION[$i]);
            }
        }
        if ($echo) {
            echo $result;
        } else {
            return $result;
        }
    }

    public function addMessage($type, $message, $show = false) // $message should already be in well-formatted HTML 
    {
        if ($show) {
            echo'<div class="' . self::h($type) . '">' . $message . '</div>';
        } else {
            @$_SESSION[$type] .= $message;
        }
    }

    /** HTML notation for <option> filled with given parameters
     * @param mixed value
     * @param string text
     * @param mixed default
     * @param bool disabled */
    public function htmlOption($value, $text, $default = null, $disabled = false)
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
    public function htmlSelect($name, $input, $value, $options = array())
    {
        $result = '<select name="' . self::safeJs($name) . '"';
        foreach ($options as $k => $v) {
            if (!self::among($k, 'name', 'prepend', 'append')) {
                $result .= ' ' . $k . '="' . self::safeJs($v) . '"';
            }
        }
        $result .= '>' . PHP_EOL . self::htmlSelectAppend(@$options['prepend'], $value);
        foreach ((array)$input as $k=>$v) {
            $result .= htmlOption($k, $v, $value);
        } 
        return $result . htmlSelectAppend(@$options['append'], $value) . '</select>' . PHP_EOL;
    }

    protected function htmlSelectAppend($array, $value)
    {
        if (!is_array($array)) {
            return'';
        }
        $result = '';
        foreach ($array as $k => $v) {
            $i = explode("\0", $v);
            $result .= self::htmlOption($i[0], $i[1], $value);
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
    public function htmlRadio($name, $input, $value = null, $options = array())
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
                    $result .= ' ' . $k2 . ($v2 === true ? '' : '="' . self::safeJs($v2) . '"');
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
    public function htmlTextarea($name, $content, $cols = 60, $rows = 5, $options = array())
    {
        $label = @$options['label'];
        unset($options['label']);
        $options = array_merge($options, array('cols' => $cols, 'rows' => $rows));
        return self::htmlTextInput($name, $label, $content, $options);
    }
    
    // HTML notation for the <input> tag. See html_textinput() for more info.
    public function htmlInput($name, $label, $value, $options = array())
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
    protected function htmlTextInput($name, $label, $value, $options = array())
    {
        $result = '';
        if (is_array($value)) {
            $value = @$value[$name];
        }
        if (is_string($options)) {
            $options = array('type' => $options);
        }
        if ($label && !@$options['id']) {
            $options['id'] = 'input-' . self::webalize($name);
        }
        if (@$options['random-id']) {
            $options['id'] .= '-' . rand(1e8, 1e9-1);
        }
        if (!isset($options['rows']) and !@$options['type']) {    
            $options['type'] = 'text';
        }
        if (@$options['table']) {
            foreach (array('before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>') as $k=>$v) {
                self::setifempty($options[$k], $v);
            }
        }
        if (!@$options['label-html']) {
            $label = self::h($label);
        }
        if (@$options['flag']) {
            $label = common('flag', $options['flag']) . ' ' . $label;
        }
        @self::setifempty($options['type'], 'text');
        if ($options['type'] == 'disabled') {
            $result .= '<input type="hidden" name="' . self::h($name) . '" value="' . self::h($value) . '"/>';
            $options['type'] = 'text';
            $options['disabled'] = 'disabled';
        }
        $result .= '<' . (isset($options['rows']) ? 'textarea' : 'input') . ' ';
        $options = array_merge($options, array('name' => self::h($name), 'value' => $value));
        foreach (@$options as $k=>$v) {
            if (is_string($k) && !is_null($v) 
                && !self::among($k, 'before', 'between', 'after', 'table', 'flag', 'random-id', 'label-html', 'label-after', 'value', 'required')) {
                $result .= ' ' . $k . '="' . (mb_substr($k, 0, 2)=='on' ? self::safeJs($v) : self::h($v)) . '"';
            }
        }
        $result .= isset($options['rows'])?'>' . self::h($value) . '</textarea>' : self::wrap(self::h($value), ' value="', '"') . '/>';
        $label = self::wrap($label, '<label for="' . self::h(@$options['id']) . '">', '</label>');
        if (@$options['required']) {
            $result .= '<span class="required">*</span>';
        }
        return $result = @$options['before'] . (@$options['label-after'] ? $result : $label)
            . @$options['between'] . (@$options['label-after']?$label:$result) . @$options['after'];
    }

    public function webalize($s, $charlist = null, $lower = true)
    { //credit: Daniel Grudl (Nette)
        $s = strtr($s, '`\'"^~', '-----');
        if (ICONV_IMPL === 'glibc') {
            $s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s); // intentionally @
            $s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
                "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
        } else { 
            $s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
        }
        $s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
        if ($lower === -1) {
            $s = strtoupper($s);
        } elseif ($lower) {
            $s = strtolower($s);
        }
        $s = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $s);
        $s = trim($s, '-');
        return $s;
    }
    
    public function safeIn($input)
    {
        preg_match_all('~([-\+]?(0x[0-9a-f]+|(0|[1-9][0-9]*)(\.[0-9]+)?(e[-\+]?[0-9]+)?)|\'(\.|[^\'])*\'|"(\.|[^"])*")~i', $input, $matches);
        return implode(',', $matches[0]);
    }

    public function safeJs($string)
    {
        return strtr($string, array(']]>' => ']]\x3E', '/' => '\/', "\\" => '\\', '"' => '\"', "'" => '\''));
    }

    public function redir($url = '')
    {        
        $url = self::wrap($url,
            ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'], 
            '', 
            '?' . $_SERVER['QUERY_STRING']);
        header("Location: $url", true, 303);
        header('Connection: close');
        die('<script type="text/javascript">window.location="' . json_encode($url) . '";</script>'.PHP_EOL.'<a href="'.urlencode($url).'">&rarr;</a>');
    }

    //example: array_listed(array("Levi's","Procter & Gamble"),1,", ","<b>","</b>") --> <b>Levi's</b>, <b>Procter &amp; Gamble</b>
    //flags: +1=htmlentities, +2=escape string, +4=safeJs, +8=intval, +16=(float), +32=ignore empty +64=` -> ``, +128=array_keys, +10 = quote LIKE, +18 = preg_quote 
    public function arrayListed($a, $flags = 0, $glue = ',', $before = '', $after = '')
    {
        $result = '';
        if (is_array($a)) {
            foreach ($a as $k => $v) {
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
                        $v = sql_escape($v);
                    }
                }
                if ($flags & 4) {
                    $v = self::safeJs($v);
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

    // e.g. exploded('|', 'apple|banana|kiwi', 1) -> banana
    public function exploded($separator, $string, $key = 0)
    {
        $result = explode($separator, $string);
        return isset($result[$key]) ? $result[$key] : null;
    }

    public function cutTill(&$haystack, $needle)
    {
        if (($p = strpos($haystack, $needle)) !== false) {
            $haystack = substr($haystack, 0, $p);
        }
    }

    // Make a cURL call and return its response. Supposes running cURL extension.
    // returns string of cURL's response or null if curl_errno() is non-zero
    public function curlCall($url, $options = array())
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
}
