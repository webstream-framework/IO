<?php

namespace WebStream\IO\Writer;

use WebStream\Exception\Extend\InvalidArgumentException;
use WebStream\Exception\Extend\IOException;
use WebStream\IO\FileOutputStream;

/**
 * FileWriter
 * @author Ryuichi TANAKA.
 * @since 2016/02/24
 * @version 0.7
 */
class FileWriter extends OutputStreamWriter
{
    /**
     * constructor
     * @param mixed $file ファイルオブジェクトまたはファイルパス
     * @param bool $isAppend
     * @param int $bufferSize
     * @throws IOException
     * @throws InvalidArgumentException
     */
    public function __construct($file, bool $isAppend = false, int $bufferSize = 0)
    {
        parent::__construct(new FileOutputStream($file, $isAppend));

        // fwriteのデフォルトバッファリングサイズは8KBなので、指定無しの場合は8KBになる
        // また、同じストリームに対して出力を行うプロセスが複数ある場合、8KBごとに停止する
        // see: http://php.net/manual/ja/function.stream-set-write-buffer.php
        if ($bufferSize > 0 && stream_set_write_buffer($this->stream, $bufferSize) !== 0) {
            throw new IOException("Failed to change the buffer size.");
        }
    }

    /**
     * ファイルに書き込む
     * @param mixed $data 書き込みデータ
     */
    public function write($data)
    {
        $this->stream->write($data);
    }
}
