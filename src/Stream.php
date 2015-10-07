<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Exception\ReadOnlyStreamException;
use Genkgo\Xsl\Exception\StreamException;

/**
 * Class Stream
 * @package Genkgo\Xsl
 */
class Stream
{
    /**
     * @var string
     */
    private $template;
    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var resource
     */
    public $context;

    /**
     * @param $path
     * @param $mode
     * @param $options
     * @param $opened_path
     * @return bool
     * @throws StreamException
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $filename = str_replace('gxsl://', '', $path);

        $streamContext = stream_context_get_options($this->context);
        if (!isset($streamContext['gxsl']['documentContext']) || $streamContext['gxsl']['documentContext'] instanceof Context === false) {
            throw new StreamException('A gxsl stream should have a document context, no document context given');
        }

        /** @var Context $documentContext */
        $documentContext = $streamContext['gxsl']['documentContext'];

        if (substr($filename, -1, 1) === '~') {
            $this->template = $documentContext->getDocument()->saveXML();
        } else {
            $this->template = file_get_contents($filename);
        }

        return true;
    }

    /**
     *
     */
    public function stream_close()
    {
        $this->template = null;
    }

    /**
     * @param $count
     * @return string
     */
    public function stream_read($count)
    {
        $bytes = substr($this->template, $this->position, $count);
        $this->position += $count;
        return $bytes;
    }

    /**
     * @param $data
     * @return int
     * @throws ReadOnlyStreamException
     */
    public function stream_write($data)
    {
        throw new ReadOnlyStreamException();
    }

    /**
     * @param $path
     * @param $flags
     * @return array
     */
    public function url_stat($path, $flags)
    {
        $filename = str_replace('gxsl://', '', $path);
        if (substr($filename, -1, 1) === '~') {
            return [];
        }

        return stat($filename);
    }

    /**
     * @return bool
     */
    public function stream_eof()
    {
        return true;
    }
}
