<?php

namespace WebStream\IO\Writer;

use WebStream\Exception\Extend\IOException;
use WebStream\IO\File;

/**
 * SimpleFileWriter
 * OutputStreamWriterを継承しているが、write以外の機能を無効にする
 * @author Ryuichi TANAKA.
 * @since 2016/02/25
 * @version 0.7
 */
class SimpleFileWriter extends OutputStreamWriter
{
    /**
     * @var string ファイルパス
     */
    private string $filepath;

    /**
     * @var int バッファリングサイズ
     */
    private int $bufferSize;

    /**
     * @var string 書き込みモード
     */
    private string $mode;

    /**
     * constructor
     * @param string $filepath ファイルパス
     * @param int $bufferSize バッファリングサイズ
     * @throws IOException
     */
    public function __construct(string $filepath, int $bufferSize = 0)
    {
        $dirname = dirname($filepath);
        $dir = new File($dirname);
        if (!$dir->isWritable()) {
            throw new IOException("Cannot writable: " . $filepath);
        }

        $this->filepath = $filepath;
        $this->bufferSize = $bufferSize;
        $this->mode = file_exists($this->filepath) ? 'ab' : 'wb';
    }

    /**
     * ファイルに書き込む
     * ファイルが存在する場合、常に追記モード
     * @param mixed $data 書き込みデータ
     * @throws IOException
     */
    public function write($data)
    {
        $stream = fopen($this->filepath, $this->mode);

        if ($this->bufferSize > 0 && stream_set_write_buffer($stream, $this->bufferSize) !== 0) {
            throw new IOException("Failed to change the buffer size.");
        }

        if (!is_resource($stream) || $stream === false) {
            throw new IOException("Unable open " . $this->filepath);
        }

        if (!flock($stream, LOCK_EX | LOCK_NB)) {
            throw new IOException("Cannot lock file: " . $this->filepath);
        }

        if (fwrite($stream, $data) === false) {
            flock($stream, LOCK_UN);
            fclose($stream);
            throw new IOException("Failed to write stream.");
        }

        fflush($stream);
        flock($stream, LOCK_UN);
        fclose($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        // Nothing to do
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        // Nothing to do
    }

    /**
     * {@inheritdoc}
     * @throws IOException
     */
    public function newLine()
    {
        $this->write(PHP_EOL);
    }
}
