<?php

namespace WebStream\IO\Test;

require_once dirname(__FILE__) . '/../File.php';
require_once dirname(__FILE__) . '/../InputStream.php';
require_once dirname(__FILE__) . '/../OutputStream.php';
require_once dirname(__FILE__) . '/../FileInputStream.php';
require_once dirname(__FILE__) . '/../FileOutputStream.php';
require_once dirname(__FILE__) . '/../ConsoleOutputStream.php';
require_once dirname(__FILE__) . '/../Reader/InputStreamReader.php';
require_once dirname(__FILE__) . '/../Reader/FileReader.php';
require_once dirname(__FILE__) . '/../Writer/OutputStreamWriter.php';
require_once dirname(__FILE__) . '/../Test/Providers/OutputStreamWriterProvider.php';
require_once dirname(__FILE__) . '/../Test/Modules/IOException.php';
require_once dirname(__FILE__) . '/../Test/Modules/InvalidArgumentException.php';

use PHPUnit\Framework\TestCase;
use WebStream\IO\ConsoleOutputStream;
use WebStream\IO\File;
use WebStream\IO\FileOutputStream;
use WebStream\IO\Reader\FileReader;
use WebStream\IO\Test\Providers\OutputStreamWriterProvider;
use WebStream\IO\Writer\OutputStreamWriter;

/**
 * OutputStreamWriterTest
 * @author Ryuichi TANAKA.
 * @since 2016/08/19
 * @version 0.7
 */
class OutputStreamWriterTest extends TestCase
{
    use OutputStreamWriterProvider;

    /**
     * 正常系
     * ファイルに書き込みができること
     * @test
     * @dataProvider writeProvider
     * @param $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function okFileWriteFromFilePath($filePath)
    {
        @unlink($filePath);
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->write("test");
        $writer->flush();
        $writer->close();

        $reader = new FileReader($filePath);
        $this->assertEquals("testtest", $reader->read());
    }

    /**
     * 正常系
     * ファイルに書き込みができること
     * ファイルオブジェクトを指定
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function okFileWriteFromFileObject(string $filePath)
    {
        @unlink($filePath);
        $stream = new FileOutputStream(new File($filePath));
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->write("test");
        $writer->flush();
        $writer->close();

        $reader = new FileReader($filePath);
        $this->assertEquals("testtest", $reader->read());
    }

    /**
     * 正常系
     * Offset指定でファイルに書き込みができること
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function okFileWriteOffset(string $filePath)
    {
        unlink($filePath);
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("123");
        $writer->write("123456", 3);
        $writer->flush();
        $writer->close();

        $reader = new FileReader($filePath);
        $this->assertEquals("123456", $reader->read());
    }

    /**
     * 正常系
     * Length指定でファイルに書き込みができること
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function okFileWriteLength(string $filePath)
    {
        @unlink($filePath);
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("123");
        $writer->write("123456789", null, 3);
        $writer->flush();
        $writer->close();

        $reader = new FileReader($filePath);
        $this->assertEquals("123123", $reader->read());
    }

    /**
     * 正常系
     * Offset,Length指定でファイルに書き込みができること
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function okFileWriteOffsetLength(string $filePath)
    {
        @unlink($filePath);
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("123");
        $writer->write("123456789", 3, 3);
        $writer->flush();
        $writer->close();

        $reader = new FileReader($filePath);
        $this->assertEquals("123456", $reader->read());
    }

    /**
     * 正常系
     * ファイルに追記できること
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function okFileWriteCharAppend(string $filePath)
    {
        @unlink($filePath);
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->flush();
        $writer->close();

        $stream = new FileOutputStream($filePath, true);
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->flush();
        $writer->close();

        $reader = new FileReader($filePath);
        $this->assertEquals("testtest", $reader->read());
    }

    /**
     * 正常系
     * コンソールに書き込みができること
     * @test
     */
    public function okConsoleWrite()
    {
        $stream = new ConsoleOutputStream();
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->write("test");
        $writer->flush();
        $writer->close();

        $this->expectOutputString("testtest");
    }

    /**
     * 正常系
     * offset指定でコンソールに書き込めること
     * @test
     */
    public function okConsoleWriteOffset()
    {
        $stream = new ConsoleOutputStream();
        $writer = new OutputStreamWriter($stream);
        $writer->write("123");
        $writer->write("123456", 3);
        $writer->flush();
        $writer->close();

        $this->expectOutputString("123456");
    }

    /**
     * 正常系
     * Length指定でコンソールに書き込みができること
     * @test
     */
    public function okConsoleWriteLength()
    {
        $stream = new ConsoleOutputStream();
        $writer = new OutputStreamWriter($stream);
        $writer->write("123");
        $writer->write("123456789", null, 3);
        $writer->flush();
        $writer->close();

        $this->expectOutputString("123123");
    }

    /**
     * 正常系
     * Offset,Length指定でコンソールに書き込みができること
     * @test
     */
    public function okConsoleWriteOffsetLength()
    {
        $stream = new ConsoleOutputStream();
        $writer = new OutputStreamWriter($stream);
        $writer->write("123");
        $writer->write("123456789", 3, 3);
        $writer->flush();
        $writer->close();

        $this->expectOutputString("123456");
    }

    /**
     * 異常系
     * ファイルオブジェクト、ファイルパス以外を指定した場合、例外が発生すること
     * @test
     * @throws \WebStream\Exception\Extend\IOException
     */
    public function ngInvalidFileType()
    {
        $this->expectException(\WebStream\Exception\Extend\InvalidArgumentException::class);

        $stream = new FileOutputStream(1);
        $this->fail();
    }

    /**
     * 異常系
     * ファイルがロックされている状態でストリームオブジェクトを作成した場合、例外が発生すること
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function ngAlreadyFileLocked(string $filePath)
    {
        $this->expectException(\WebStream\Exception\Extend\IOException::class);

        @unlink($filePath);
        // ファイルを作る
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->flush();
        $writer->close();

        // ロックをかける
        $resource = fopen($filePath, 'wb');
        flock($resource, LOCK_EX);

        new FileOutputStream($filePath);
        $this->fail();
    }

    /**
     * 異常系
     * flush済みの状態でflushすると例外が発生すること
     * @test
     * @dataProvider writeProvider
     * @param string $filePath
     * @throws \WebStream\Exception\Extend\IOException
     * @throws \WebStream\Exception\Extend\InvalidArgumentException
     */
    public function ngInvalidFlush(string $filePath)
    {
        $this->expectException(\WebStream\Exception\Extend\IOException::class);

        @unlink($filePath);
        $stream = new FileOutputStream($filePath);
        $writer = new OutputStreamWriter($stream);
        $writer->write("test");
        $writer->flush();
        $writer->close();

        $writer->flush();
        $this->fail();
    }
}
