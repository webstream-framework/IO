<?php

namespace WebStream\IO\Reader;

use WebStream\Exception\Extend\InvalidArgumentException;
use WebStream\Exception\Extend\IOException;
use WebStream\IO\FileInputStream;

/**
 * FileReader
 * @author Ryuichi TANAKA.
 * @since 2016/02/05
 * @version 0.7
 */
class FileReader extends InputStreamReader
{
    /**
     * @var int バッファリングサイズ
     */
    private int $bufferSize;

    /**
     * constructor
     * @param mixed $file ファイルオブジェクトまたはファイルパス
     * @param int $bufferSize バッファリングサイズ
     * @throws InvalidArgumentException
     * @throws IOException
     */
    public function __construct($file, int $bufferSize = 8192)
    {
        parent::__construct(new FileInputStream($file));
        $this->bufferSize = $bufferSize;
    }

    /**
     * ファイルを読み込む
     * @return string ファイル内容
     * @throws IOException
     */
    public function read()
    {
        $out = "";
        while (($data = $this->stream->read($this->bufferSize)) !== null) {
            $out .= $data;
        }

        return $out;
    }
}
