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
     * @return bool
     * @throws StreamException
     */
    public function stream_open($path)
    {
        $filename = str_replace('gxsl://', '', $path);
        $streamContext = stream_context_get_options($this->context);
        $this->verifyTranspiler($streamContext);

        /** @var Transpiler $transpiler */
        $transpiler = $streamContext['gxsl']['transpiler'];
        $rootDocument = substr($filename, -2)  === '~~';

        if ($rootDocument) {
            $this->template = $this->rootToTemplate($transpiler, $filename, $streamContext);
            return true;
        }

        $this->template = $this->cacheToTemplate($transpiler, $filename, $streamContext);
        return true;
    }

    /**
     * @param $streamContext
     * @throws StreamException
     */
    private function verifyTranspiler($streamContext)
    {
        if (!isset($streamContext['gxsl']['transpiler']) || $streamContext['gxsl']['transpiler'] instanceof Transpiler === false) {
            throw new StreamException('A gxsl stream should have a transpiler, no transpiler given');
        }
    }

    /**
     * @param Transpiler $transpiler
     * @param $filename
     * @param $streamContext
     * @return mixed|string
     */
    private function cacheToTemplate(Transpiler $transpiler, $filename, $streamContext)
    {
        if (isset($streamContext['gxsl']['cache']) && $streamContext['gxsl']['cache'] instanceof CallbackCacheInterface === true) {
            /** @var CallbackCacheInterface $cacheAdapter */
            $cacheAdapter = $streamContext['gxsl']['cache'];

            return $cacheAdapter->get($filename, function () use ($transpiler, $filename) {
                return $this->pathToTemplate($transpiler, $filename);
            });
        }

        return $this->pathToTemplate($transpiler, $filename);
    }

    /**
     * @param Transpiler $transpiler
     * @param $filename
     * @param $streamContext
     * @return mixed|string
     */
    private function rootToTemplate(Transpiler $transpiler, $filename, $streamContext)
    {
        $filename = substr($filename, 0, -2);

        if (is_file($filename) === false) {
            return $this->documentToTemplate($transpiler, $transpiler->transpileRoot());
        }

        return $this->cacheToTemplate($transpiler, $filename, $streamContext);
    }

    /**
     * @param Transpiler $transpiler
     * @param $path
     * @return string
     */
    private function pathToTemplate(Transpiler $transpiler, $path)
    {
        $document = new DOMDocument();
        $document->load($path);
        return $this->documentToTemplate($transpiler, $document);
    }


    /**
     * @param Transpiler $transpiler
     * @param DOMDocument $document
     * @return string
     */
    private function documentToTemplate(Transpiler $transpiler, DOMDocument $document)
    {
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
    public function stream_write()
    {
        throw new ReadOnlyStreamException();
    }

    /**
     * @param $path
     * @return array
     */
    public function url_stat($path)
    {
        $filename = str_replace('gxsl://', '', $path);
        if (substr($filename, -2, 2) === '~~') {
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
