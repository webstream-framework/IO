<?php
namespace WebStream\Test\UnitTest;

use WebStream\IO\InputStream;
use WebStream\IO\StringInputStream;
use WebStream\IO\Reader\InputStreamReader;
use WebStream\Test\UnitTest\DataProvider\InputStreamReaderProvider;

require_once dirname(__FILE__) . '/TestBase.php';
require_once dirname(__FILE__) . '/DataProvider/InputStreamReaderProvider.php';

/**
 * InputStreamReaderTest
 * @author Ryuichi TANAKA.
 * @since 2016/01/10
 * @version 0.7
 */
class InputStreamReaderTest extends \PHPUnit_Framework_TestCase
{
    use InputStreamReaderProvider;

    /**
     * 正常系
     * バイト単位で読み込みできること
     * @test
     * @dataProvider charReadProvider
     */
    public function okCharRead($str, $byteLength)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $out = "";
        for ($i = 0; $i < $byteLength; $i++) {
            $out .= $isr->read();
        }

        $this->assertEquals($str, $out);
    }

    /**
     * 正常系
     * バイト単位で読み込みできること
     * @test
     * @dataProvider overCharReadProvider
     */
    public function okOverCharRead($str, $byteLength)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $this->assertEquals($isr->read($byteLength), $str);
    }

    /**
     * 正常系
     * 行単位で読み込みできること
     * @test
     * @dataProvider readLineProvider
     */
    public function okReadLine($strLine, $str1, $str2)
    {
        $sis = new StringInputStream($strLine);
        $isr = new InputStreamReader($sis);

        $this->assertEquals($isr->readLine(), $str1);
        $this->assertEquals($isr->readLine(), $str2);
        $this->assertEquals($isr->readLine(), null);
    }

    /**
     * 正常系
     * 指定バイト数だけスキップできること
     * @test
     * @dataProvider skipProvider
     */
    public function okSkip($str, $pos, $result)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $isr->skip($pos);
        $this->assertEquals($isr->read(), $result);
    }

    /**
     * 正常系
     * 終端を越えたスキップをしたとき、
     * 1回目のreadは空文字を返し、2回目のreadはnullを返すこと
     * @test
     * @dataProvider overSkipAndReadProvider
     */
    public function okOverSkipAndRead($str, $skipNum)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $isr->skip($skipNum);
        $this->assertEquals($isr->read(1), "");
        $this->assertEquals($isr->read(1), null);
    }

    /**
     * 正常系
     * 終端を越えたスキップをしたとき、
     * 1回目のreadLineでnullを返すこと
     * @test
     * @dataProvider overSkipAndReadLineProvider
     */
    public function okOverSkipAndReadLine($str, $skipNum)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $isr->skip($skipNum);
        $this->assertEquals($isr->readLine(), null);
    }

    /**
     * 正常系
     * readLineにより終端に到達した後スキップさせ、その後readすると空文字を返すこと
     * @test
     * @dataProvider readLineAndOverSkipReadProvider
     */
    public function okReadLineAndOverSkipRead($str)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $isr->readLine();
        $isr->skip(1);
        $this->assertEquals($isr->read(), "");
        $this->assertEquals($isr->read(), null);
    }

    /**
     * 正常系
     * ポインタ位置が負になった場合、移動量は常に-1になること
     * @test
     * @dataProvider overFrontSkipProvider
     */
    public function okOverFrontSkip($str, $pos)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);

        $this->assertEquals($isr->skip($pos), -1);
    }

    /**
     * 正常系
     * リセットすると初期位置にポインタが移動すること
     * @test
     * @dataProvider resetProvider
     */
    public function okReset($str)
    {
        $sis = new StringInputStream($str);
        $isr = new InputStreamReader($sis);
        $isr->skip(10);
        $isr->reset();
        $this->assertEquals($str, $isr->read());
    }

    /**
     * 異常系
     * 読み込みサイズに不正値を渡した時、例外が発生すること
     * @test
     * @expectedException WebStream\Exception\Extend\InvalidArgumentException
     */
    public function ngInvalidLength()
    {
        $sis = new StringInputStream("abc");
        $isr = new InputStreamReader($sis);
        $isr->read("dummy");
        $this->assertTrue(false);
    }
}
