<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Genkgo\Xsl\Exception\ReadOnlyStreamException;
use Genkgo\Xsl\Exception\StreamException;
use Genkgo\Xsl\Exception\TransformationException;

final class Stream
{
    const PROTOCOL = 'gxsl://';
    
    const HOST = 'localhost';
    
    const ROOT = '#root';

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var resource
     */
    public $context;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $path
     * @return bool
     * @throws StreamException
     */
    public function stream_open($path)
    {
        $path = $this->uriToPath($path);

        $streamContext = \stream_context_get_options($this->context);

        if (isset($streamContext['gxsl']['transpiler'])) {
            /** @var Transpiler $transpiler */
            $transpiler = $streamContext['gxsl']['transpiler'];

            try {
                if ($this->isRoot($path)) {
                    $this->content = $transpiler->transpileRoot();
                } else {
                    $this->content = $transpiler->transpileFile($path);
                }
            } catch (\Throwable $e) {
                throw new TransformationException(
                    'Cannot transpile ' . $path . '. ' . $e->getMessage(),
                    0,
                    $e
                );
            }
        } else {
            if ($this->isRoot($path)) {
                throw new StreamException(
                    $path . ' does not exists without stream'
                );
            }
            $this->content = (string)\file_get_contents($path);
        }

        return true;
    }
    
    public function stream_close()
    {
        $this->content = '';
    }

    /**
     * @return array
     */
    public function stream_stat()
    {
        return [];
    }

    /**
     * @param int $count
     * @return string
     */
    public function stream_read($count)
    {
        $bytes = \substr($this->content, $this->position, $count);
        $this->position += $count;

        return $bytes;
    }

    /**
     * @throws ReadOnlyStreamException
     */
    public function stream_write()
    {
        throw new ReadOnlyStreamException();
    }

    /**
     * @param string $path
     * @return array
     */
    public function url_stat($path)
    {
        $filename = $this->uriToPath($path);

        if ($this->isRoot($filename)) {
            return [];
        }

        $result = \stat($filename);
        if ($result === false) {
            return [];
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function stream_eof()
    {
        return true;
    }

    /**
     * @param string $filename
     * @return bool
     */
    private function isRoot($filename)
    {
        return \substr($filename, \strlen(self::ROOT) * -1) === self::ROOT;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function uriToPath($uri)
    {
        $filename = \urldecode(\str_replace(self::PROTOCOL . self::HOST, '', $uri));
        // @codeCoverageIgnoreStart
        if (PHP_OS === 'WINNT') {
            return \ltrim($filename, '/');
        }
        // @codeCoverageIgnoreEnd

        return $filename;
    }
}
