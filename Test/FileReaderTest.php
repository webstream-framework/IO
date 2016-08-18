<?php
namespace WebStream\IO\Test;

require_once dirname(__FILE__) . '/../InputStreamReader.php';
require_once dirname(__FILE__) . '/../Reader/FileReader.php';
require_once dirname(__FILE__) . '/../Test/Modules/IOException.php';

/**
 * FileReaderTest
 * @author Ryuichi TANAKA.
 * @since 2016/08/18
 * @version 0.7
 */
class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function okRead()
    {
        $this->assertEquals(1, 1);
    }
}
