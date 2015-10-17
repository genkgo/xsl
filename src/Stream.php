<?php
namespace Genkgo\Xsl;

use DOMDocument;
use Genkgo\Cache\CallbackCacheInterface;
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
        if (!isset($streamContext['gxsl']['transpiler']) || $streamContext['gxsl']['transpiler'] instanceof Transpiler === false) {
            throw new StreamException('A gxsl stream should have a transpiler, no transpiler given');
        }

        /** @var Transpiler $transpiler */
        $transpiler = $streamContext['gxsl']['transpiler'];
        $specialDocument = substr($filename, -1, 1);

        if ($specialDocument === '~') {
            $this->template = $this->documentToTemplate($transpiler, $transpiler->context->getDocument());
            return true;
        }

        if (isset($streamContext['gxsl']['cache']) && $streamContext['gxsl']['cache'] instanceof CallbackCacheInterface === true) {
            /** @var CallbackCacheInterface $cacheAdapter */
            $cacheAdapter = $streamContext['gxsl']['cache'];

            $this->template = $cacheAdapter->get($filename, function () use ($transpiler, $filename) {
                return $this->pathToTemplate($transpiler, $filename);
            });

            return true;
        }

        $this->template = $this->pathToTemplate($transpiler, $filename);

        return true;
    }

    private function pathToTemplate (Transpiler $transpiler, $path) {
        $specialDocument = substr($path, -1, 1);
        if ($specialDocument === '_') {
            $document = $transpiler->context->getDocument();
        } else {
            $document = new DOMDocument();
            $document->load($path);
        }

        return $this->documentToTemplate($transpiler, $document);
    }


    private function documentToTemplate (Transpiler $transpiler, DOMDocument $document) {
        return $transpiler->transpile($document)->saveXML();
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
        if (in_array(substr($filename, -1, 1), ['~', '_'])) {
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
