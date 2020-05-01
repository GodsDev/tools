<?php

namespace GodsDev\Tools\Test;

use GodsDev\Tools\Tools;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-06-25 at 18:53:30.
 */
class ToolsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->tools = new Tools();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    public function testAll_A_E()
    {
        // add
        unset($a);
        $this->assertSame(1, Tools::add($a));
        $this->assertSame(2, Tools::add($a));
        $this->assertSame(4, Tools::add($a, 2));
        $this->assertSame('41', Tools::add($a, true));
        $this->assertSame('41', Tools::add($a, false));
        unset($a);
        $this->assertSame('a', Tools::add($a, 'a'));
        $this->assertSame('ab', Tools::add($a, 'b'));
        $this->assertSame('ab1', Tools::add($a, true));
        $this->assertSame('ab1', Tools::add($a, false));
        $this->assertSame('ab1', Tools::add($a, null));
        $a = ['Apple'];
        $this->assertSame(['Apple', 'Banana'], Tools::add($a, 'Banana'));
        $a = [];
        $this->assertSame(['Banana'], Tools::add($a, 'Banana'));
        // addMessage
        $_SESSION['messages'] = [];
        Tools::addMessage('info', 'One');
        Tools::addMessage('error', 'Two');
        Tools::addMessage(true, 'Three');
        Tools::addMessage(false, 'Four');
        $this->assertSame($_SESSION['messages'], [
            ['info', 'One'],
            ['danger', 'Two'],
            ['success', 'Three'],
            ['warning', 'Four'],
        ]);
        // among
        $a = 0;
        $this->assertSame(false, Tools::among($a, '0'));
        $a = false;
        $this->assertSame(false, Tools::among($a, 0));
        $a = null;
        $this->assertSame(false, Tools::among($a, 0, false, true));
        $this->assertSame(true, Tools::among($a, null));
        // anyset
        unset($_GET['a'], $a);
        $this->assertSame(false, Tools::anyset($_GET['a'], $_POST['abc'][1], $a));
        $a = 1;
        $this->assertSame(true, Tools::anyset($_GET['a'], $_POST['abc'][1], $a));
        $a = null;
        $this->assertSame(false, Tools::anyset($_GET['a'], $a));
        $this->assertSame(false, Tools::anyset($_GET['a'][2], $b, $a));
        // arrayConfineKeys
        $employees = [
            ['name' => 'John', 'age' => 43],
            ['name' => 'Lucy', 'age' => 28]
        ];
        $this->assertSame([
            ['age' => 43],
            ['age' => 28]
            ], Tools::arrayConfineKeys($employees, 'age'));
        // arrayKeyAsValues
        $fruits = ['Apple', 'Pear', 'Kiwi'];
        $this->assertSame(['Apple' => 'Apple', 'Pear' => 'Pear', 'Kiwi' => 'Kiwi'], Tools::arrayKeyAsValues($fruits));
        // arrayListed
        $fruits = ['<b>Apple</b>', 'Levi\'s', 'H&M'];
        $this->assertSame("<b>Apple</b>,Levi's,H&M", Tools::arrayListed($fruits));
        $this->assertSame("&lt;b&gt;Apple&lt;/b&gt;,Levi&#039;s,H&amp;M", Tools::arrayListed($fruits, Tools::ARRL_HTML));
        $this->assertSame("<b>Apple</b>,Levi\\'s,H&M", Tools::arrayListed($fruits, Tools::ARRL_ESC));
        $this->assertSame("A,B,C", Tools::arrayListed(['A', 'B', 0, '', false, null, 'C'], Tools::ARRL_EMPTY));
        $this->assertSame('<a href="/en/about" title="about">about</a> | <a href="/en/links" title="links">links</a>', Tools::arrayListed(['about', 'links'], Tools::ARRL_PATTERN, ' | ', '<a href="/en/#" title="#">#</a>', '#'));
        // arrayReindex
        $a = [
            ['id' => 5, 'name' => 'John', 'surname' => 'Doe'],
            ['id' => 6, 'name' => 'Jane', 'surname' => 'Dean']
        ];
        $b = [
            ['id' => 5, 'name' => 'John'],
            ['id' => 6, 'name' => 'Jane']
        ];
        $this->assertSame(
            [
                5 => ['name' => 'John', 'surname' => 'Doe'],
                6 => ['name' => 'Jane', 'surname' => 'Dean']
            ],
            Tools::arrayReindex($a, 'id')
        );
        $this->assertSame(
            [
                5 => 'John',
                6 => 'Jane'
            ],
            Tools::arrayReindex($b, 'id')
        );
        // arrayRemoveItems
        $fruits = ['Apple', 'Pear', 'Kiwi'];
        $this->assertSame([2 => 'Kiwi'], Tools::arrayRemoveItems($fruits, ['Apple', 'Pear']));
        $this->assertSame([2 => 'Kiwi'], Tools::arrayRemoveItems($fruits, 'Apple', 'Pear', 'Orange'));
        $this->assertSame([], Tools::arrayRemoveItems($fruits, 'Apple', 'Pear', 'Kiwi', 'Orange'));
        // arraySearchAssoc
        $a = [
            0 => ['id' => 5, 'name' => 'Joe', 'surname' => 'Doe', 'age' => 35],
            1 => ['id' => 17, 'name' => 'Irene', 'surname' => 'Smith', 'age' => 28]
        ];
        $this->assertSame(1, Tools::arraySearchAssoc(['name' => 'Irene'], $a));
        $this->assertSame(false, Tools::arraySearchAssoc(['name' => 'Mary'], $a));
        $this->assertSame(1, Tools::arraySearchAssoc(['name' => 'Irene', 'surname' => 'Smith'], $a));
        $this->assertSame(false, Tools::arraySearchAssoc(['name' => 'Irene', 'surname' => 'Miller'], $a));
        $this->assertSame(1, Tools::arraySearchAssoc(['name' => 'Irene', 'surname' => 'Miller'], $a, ['partial' => true]));
        $this->assertSame(false, Tools::arraySearchAssoc(['job' => 'accountant', 'age' => 35], $a));
        $this->assertSame(0, Tools::arraySearchAssoc(['job' => 'accountant', 'age' => 35], $a, ['partial' => true]));
        $this->assertSame(1, Tools::arraySearchAssoc(['age' => 28], $a));
        $this->assertSame(false, Tools::arraySearchAssoc(['age' => '28'], $a, ['strict' => true]));
        // array_search_i
        $fruits = [0 => 'Banana', 1 => 'Orange', 2 => 'Kiwi', 3 => 'ŠÍPEK', 'STRAWBERRY'];
        $this->assertSame(2, Tools::array_search_i('kiwi', $fruits));
        $this->assertSame(3, Tools::array_search_i('šípek', $fruits));
        // begins
        $palindrom = 'Příliš žluťoučký kůň úpěl ďábelské ódy!';
        $this->assertSame(true, Tools::begins($palindrom, 'Příliš'));
        $this->assertSame(true, Tools::begins($palindrom, 'pŘÍLIŠ', false));
        // blacklist
        $word = 'vitamins';
        Tools::blacklist($word, ['violence', 'sex'], '');
        $this->assertSame('vitamins', $word);
        $word = 'violence';
        Tools::blacklist($word, ['violence', 'sex'], '');
        $this->assertSame('', $word);
        // columnName
        $this->assertSame('', Tools::columnName(-1));
        $this->assertSame('A', Tools::columnName(0));
        $this->assertSame('B', Tools::columnName(1));
        $this->assertSame('Z', Tools::columnName(25));
        $this->assertSame('AA', Tools::columnName(26));
        $this->assertSame('AB', Tools::columnName(27));
        $this->assertSame('ZZ', Tools::columnName(701));
        $this->assertSame('AAA', Tools::columnName(702));
        // curlCall
//        $error = false;
//        $response = Tools::curlCall('example.com', [], $error);
//        $this->assertSame(true, Tools::begins($response, '<!doctype html>'));
        // cutTill
        $text = 'Mary had a little lamb with wool as white as snow.';
        Tools::cutTill($text, 'with');
        $this->assertSame('Mary had a little lamb ', $text);
        // dump
        ob_start();
        Tools::dump('a', 1, true, null);
        $a = ob_get_contents();
        ob_end_clean();
        $this->assertSame("<pre>string(1) \"a\"\n"
            . "int(1)\n"
            . "bool(true)\n"
            . "NULL\n"
            . "</pre>", $a);
        // ends
        $palindrom = 'Příliš žluťoučký kůň úpěl ďábelské ódy!';
        $this->assertSame(true, Tools::ends($palindrom, 'ódy!'));
        $this->assertSame(true, Tools::ends($palindrom, 'ÓDY!', false));
        // equal
        unset($a);
        $this->assertSame(false, Tools::equal($a, 5));
        $this->assertSame(false, Tools::equal($a, 0));
        $this->assertSame(false, Tools::equal($a, false));
        $this->assertSame(false, Tools::equal($a, null));
        $a = 0;
        $this->assertSame(false, Tools::equal($a, '0'));
        $this->assertSame(false, Tools::equal($a, 0.0));
        $this->assertSame(true, Tools::equal($a, 0));
        // escapeDbIdentifier
        $this->assertSame('`na``me`', Tools::escapeDbIdentifier('na`me'));
        // escapeIn
        $this->assertSame('0,1.5,"",0,1,NULL,"a\"b"', Tools::escapeIn([0, 1.5, '', false, true, null, 'a"b']));
        // escapeJS
        $this->assertSame("a\\'b\\\"c\/d", Tools::escapeJS('a\'b"c/d'));
        // escapeSQL
        $this->assertSame("<a href=\\\"#\\\" class=\'btn\'>#</a>", Tools::escapeSQL('<a href="#" class=\'btn\'>#</a>'));
        // exploded
        $this->assertSame('30', Tools::exploded('-', '1996-07-30', 2));
    }

    public function testGoogleAuthenticatorCode()
    {
        // GoogleAuthenticatorCode
        $this->assertRegExp('~^\d+$~', (string) $this->tools->GoogleAuthenticatorCode('abc'));
    }

    public function testAll_H_P()
    {
        // h
        $this->assertSame('a&amp;b&quot;c&apos;d&lt;e&gt;f&#0;g', Tools::h('a&b"c\'d<e>f' . "\0g"));
        // htmlInput
        $this->assertSame('<input type="text" name="info" value="a&apos;b&quot;c"/>',
            Tools::htmlInput('info', '', 'a\'b"c')
        );
        $this->assertSame('<label for="input-info">info:</label>'
            . '<input id="input-info" type="text" name="info" value="a&apos;b&quot;c"/>',
            Tools::htmlInput('info', 'info:', 'a\'b"c')
        );
        $this->assertSame('<input class="text-right" id="info1" type="text" name="info" value="a&apos;b&quot;c"/>' . "\n"
            . '<label for="info1" class="ml-1">info:</label>',
            Tools::htmlInput('info', 'info:', 'a\'b"c', ['class' => 'text-right', 'label-class' => 'ml-1', 'id' => 'info1', 'label-after' => true, 'between' => "\n"])
        );
        // htmlOption
        $this->assertSame('<option value="1">Android</option>' . PHP_EOL, Tools::htmlOption(1, 'Android'));
        // htmlRadio
        $platforms = ['Android', 'iOS'];
        $this->assertSame('<label><input type="radio" name="platform" value="0"/> Android</label>'
            . '<label><input type="radio" name="platform" value="1"/> iOS</label>', //should not be checked <==> strict comparison between 1 and '1'
            Tools::htmlRadio('platform', $platforms, '1', [])
        );
        $this->assertSame('<label class="ml-1"><input type="radio" name="platform" value="0" class="mr-1"/>…Android</label>,'
            . '<label class="ml-1"><input type="radio" name="platform" value="1" checked="checked" class="mr-1"/>…iOS</label>',
            Tools::htmlRadio('platform', $platforms, 1, ['label-class' => 'ml-1', 'radio-class' => 'mr-1', 'separator' => ',', 'between' => '…'])
        );
        $this->assertSame('<input type="radio" name="platform" value=""/>',
            Tools::htmlRadio('platform', '', 1)
        );
        // htmlSelect
        $platforms = ['Android', 'iOS'];
        $this->assertSame('<select name="platform">' . PHP_EOL
            . '<option value="0">Android</option>' . PHP_EOL
            . '<option value="1" selected="selected">iOS</option>' . PHP_EOL
            . '</select>' . PHP_EOL,
            Tools::htmlSelect('platform', $platforms, 1, [])
        );
        // htmlTextarea
        $this->assertSame('<textarea cols="60" rows="5" name="info">abc</textarea>',
            Tools::htmlTextarea('info', 'abc')
        );
        $this->assertSame('<textarea class="my-3" cols="61" rows="6" name="info">a&apos;b&quot;c</textarea>',
            Tools::htmlTextarea('info', 'a\'b"c', 61, 6, ['class' => 'my-3'])
        );
        // httpResponse
        $response = "content-type: text/html; charset=utf-8\r\npragma: no cache\r\n\r\n<p>Hello, world!</p>\n";
        $this->assertSame([
            'headers' => [
                'content-type' => 'text/html; charset=utf-8',
                'pragma' => 'no cache'
            ],
            'body' => "<p>Hello, world!</p>\n"
            ], Tools::httpResponse($response));
        $response = "pragma: no cache\r\n\r\n" . '["abc", 2, true, false, null, {"d": "e"}]';
        $this->assertSame([
            'headers' => ['pragma' => 'no cache'],
            'body' => ['abc', 2, true, false, null, ['d' => 'e']]
            ], Tools::httpResponse($response, ['JSON' => true]));
        // ifempty
        $a = 5;
        $this->assertSame(5, Tools::ifempty($a, 6));
        $a = 0;
        $this->assertSame(5, Tools::ifempty($a, 5));
        // ifnull
        $a = 0;
        $this->assertSame(0, Tools::ifnull($a));
        $a = 5;
        $this->assertSame(5, Tools::ifnull($a));
        $a = null;
        $this->assertSame(null, Tools::ifnull($a));
        // ifset
        unset($a);
        $this->assertSame(4, Tools::ifset($a, 4));
        $a = 5;
        $this->assertSame(5, Tools::ifset($a, 4));
        // in_array_i
        $fruits = ['Apple', 'Pear', 'Kiwi', 'Šípek'];
        $this->assertSame(false, Tools::in_array_i('kiwi2', $fruits));
        $this->assertSame(true, Tools::in_array_i('šípek', $fruits));
        // localeDate
        $this->assertSame('1st February 2018', Tools::localeDate(mktime(0, 0, 0, 2, 1, 2018), 'en', false));
        $this->assertSame('1. únor 2018', Tools::localeDate(mktime(0, 0, 0, 2, 1, 2018), 'cs', false));
        $this->assertSame('1. únor 2018 15:16:17', Tools::localeDate(mktime(15, 16, 17, 2, 1, 2018), 'cs', true));
        // localeTime
        $this->assertSame('15:16:17', Tools::localeTime(mktime(15, 16, 17)));
        // mb_lcfirst
        $this->assertSame('ďábelské ódy!', Tools::mb_lcfirst('Ďábelské ódy!'));
        // mb_ucfirst
        $this->assertSame('Ďábelské ódy!', Tools::mb_ucfirst('ďábelské ódy!'));
        // nonempty
        unset($a);
        $this->assertSame(5, Tools::setifempty($a, 5));
        $this->assertSame(5, Tools::setifempty($a, 6));
        // nonzero
        unset($a);
        $this->assertSame(false, Tools::nonzero($a));
        $a = 0;
        $this->assertSame(false, Tools::nonzero($a));
        $a = 5;
        $this->assertSame(true, Tools::nonzero($a));
        // plural
        $this->assertSame('child', Tools::plural(1, 'child', false, 'children'));
        $this->assertSame('children', Tools::plural(2, 'child', false, 'children'));
        $this->assertSame('Jahre', Tools::plural(2, 'Jahr', 'Jahre', 'Jahren'));
        $this->assertSame('child', Tools::plural(7601, 'child', false, 'children', false, true));
        // preg_max
        $pattern = '/^' . Tools::preg_max(255) . '$/';
        $this->assertSame(0, preg_match($pattern, '-1'));
        $this->assertSame(1, preg_match($pattern, 0));
        $this->assertSame(1, preg_match($pattern, 255));
        $this->assertSame(0, preg_match($pattern, 256));
        // randomPassword
        $this->assertSame(1, preg_match('/^[-2-9A-HJ-NP-Za-km-z]{10}$/', Tools::randomPassword(10)));
    }

    public function testRedir()
    {
        // redir
        $this->markTestSkipped();
    }

    public function testRelativeTime()
    {
        // relativeTime
        $this->assertRegExp('/(1 second ago|2 seconds ago)/', Tools::relativeTime(time() - 1)); //made more benevolent
        $this->assertSame('in 1 second', Tools::relativeTime(date('Y-m-d H:i:s', time() + 1)));
        $this->assertSame('1 vteřina zpátky', Tools::relativeTime(time() - 1, 'cs'));
        $this->assertSame('za 1 vteřina', Tools::relativeTime(time() + 1, 'cs'));
    }

    public function testResolve()
    {
        // resolve
        $_SESSION['messages'] = [];
        Tools::resolve(5 === 5, 'Equal', 'Not equal');
        Tools::resolve(5 === '5', 'Equal!', 'Not equal!');
        $this->assertSame($_SESSION['messages'], [
            ['success', 'Equal'],
            ['danger', 'Not equal!'],
        ]);
    }

    public function testAll_S_Z()
    {
        // set
        unset($a);
        $this->assertSame(false, Tools::set($a));
        $a = 0;
        $this->assertSame(false, Tools::set($a));
        $a = 5;
        $this->assertSame(5, Tools::set($a));
        unset($a);
        $this->assertSame(5, Tools::set($a, 5)); // $a = 5
        $this->assertSame(5, $a);
        $a = 0;
        $this->assertSame(5, Tools::set($a, 5)); // $a = 5
        $this->assertSame(5, $a);
        $this->assertSame(5, Tools::set($a, 6)); // $a = 5
        $this->assertSame(5, $a);
        // setarray
        unset($a);
        $this->assertSame(false, Tools::setarray($a));
        $a = true;
        $this->assertSame(false, Tools::setarray($a));
        $a = [];
        $this->assertSame(true, Tools::setarray($a));
        // setifempty
        $a = 4;
        $this->assertSame(4, Tools::setifempty($a, 5));
        unset($a);
        $this->assertSame(5, Tools::setifempty($a, 5));
        $a = '0';
        $this->assertSame(5, Tools::setifempty($a, 5));
        // setifnotset
        unset($a);
        Tools::setifnotset($a, 5);
        $this->assertSame(5, $a);
        // setifnull
        $a = null;
        Tools::setifnotset($a, 5);
        $this->assertSame(5, $a);
        unset($a);
        Tools::setifnotset($a, 5);
        $this->assertSame(5, $a);
        // setscalar
        unset($a);
        $this->assertSame(false, Tools::setscalar($a));
        $a = true;
        $this->assertSame(true, Tools::setscalar($a));
        $a = [];
        $this->assertSame(false, Tools::setscalar($a));
        // shortify
        $palindrom = 'Příliš žluťoučký kůň úpěl ďábelské ódy!';
        $this->assertSame('Příli…', Tools::shortify($palindrom, 5));
        // showMessages
        Tools::showMessages(false);
        $this->assertSame($_SESSION['messages'], []);
        // stripAttributes
        $html = 'ab c<b data-id="2">de f</b>g q<x>w</x>e <details><summary>afh</summary>jkdlg</details> r<i style="display:block;">t</i>h yug io<u>h</u>t';
        $this->assertSame('ab c<b data-id="2">de f</b>g q<x>w</x>e <details><summary>afh</summary>jkdlg</details> r<i>t</i>h yug io<u>h</u>t', Tools::stripAttributes($html, 'style'));
        $this->assertSame('ab c<b>de f</b>g q<x>w</x>e <details><summary>afh</summary>jkdlg</details> r<i>t</i>h yug io<u>h</u>t', Tools::stripAttributes($html, ['data-id', 'style']));
        $this->assertSame('ab c<b>de f</b>g q<x>w</x>e <details><summary>afh</summary>jkdlg</details> r<i>t</i>h yug io<u>h</u>t', Tools::stripAttributes($html, '*'));
        // str_after
        $palindrom = 'Příliš žluťoučký kůň úpěl ďábelské ódy!';
        $this->assertSame(' úpěl ďábelské ódy!', Tools::str_after($palindrom, 'žluťoučký kůň'));
        $this->assertSame(false, Tools::str_after($palindrom, 'PŘÍLIŠ ŽLUŤOUČKÝ KŮŇ'));
        $this->assertSame(' úpěl ďábelské ódy!', Tools::str_after($palindrom, 'ŽLUŤOUČKÝ KŮŇ', true));
        // str_before
        $palindrom = 'Příliš žluťoučký kůň úpěl ďábelské ódy!';
        $this->assertSame('Příliš žluťoučký kůň', Tools::str_before($palindrom, ' úpěl ďábelské ódy!'));
        $this->assertSame(false, Tools::str_before($palindrom, ' ÚPĚL ĎÁBELSKÉ ÓDY'));
        $this->assertSame('Příliš žluťoučký kůň', Tools::str_before($palindrom, ' ÚPĚL ĎÁBELSKÉ', true));
        // str_delete
        $a = 'žluťoučký kůň';
        $this->assertSame('žluký kůň', Tools::str_delete($a, 3, 4));
        $this->assertSame('žluký kůň', $a);
        $this->assertSame('žl', Tools::str_delete($a, 2));
        // str_putcsv
        $fields = [2, null, false, true, 'ab;c', 'žluťoučký kůň', 'say "Hello"'];
        $this->assertSame('2;;;1;"ab;c";"žluťoučký kůň";"say ""Hello"""' . "\n", Tools::str_putcsv($fields, ';'));
        // urlChange
        unset($_SERVER['QUERY_STRING']);
        $this->assertSame('a=1&color=b%26w', Tools::urlChange(['a' => 1, 'color' => 'b&w']));
        $this->assertSame('a=1', Tools::urlChange(['a' => 1, 'color' => ('black' == 'white' ? 'b&w' : null)]));
        $this->assertSame('array%5B0%5D=2&array%5B1%5D=3', Tools::urlChange(['array' => [2, 3]]));
        // webalize
        $this->assertSame('zlutoucky-kun', Tools::webalize('žluťoučký - kůň - '));
        // whitelist
        $os = 'Windows';
        Tools::whitelist($os, ['Windows', 'Unix'], 'unsupported');
        $this->assertSame('Windows', $os);
        $os = 'Solaris';
        Tools::whitelist($os, ['Windows', 'Unix'], 'unsupported');
        $this->assertSame('unsupported', $os);
        // wrap
        $this->assertSame('<b>Hello</b>', Tools::wrap('Hello', '<b>', '</b>'));
        $this->assertSame('N/A', Tools::wrap('', '<b>', '</b>', 'N/A'));
        $this->assertSame('N/A', Tools::wrap('0', '<b>', '</b>', 'N/A'));
        $this->assertSame('N/A', Tools::wrap([], '<b>', '</b>', 'N/A'));
        // xorCipher
        $this->assertSame('', Tools::xorCipher('abc', ''));
        $key = md5(uniqid(mt_rand(), true));
        $text = 'Mary had a little lamb.';
        $ciphered = Tools::xorCipher($text, $key);
        // xorDecipher
        $deciphered = Tools::xorDecipher($ciphered, $key);
        $this->assertSame(true, $text === $deciphered);
        $this->assertSame('', Tools::xorDecipher('abc', ''));
    }

}
