<?php
namespace Genkgo\Xsl\Unit;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Context;
use Genkgo\Xsl\Exception\ReadOnlyStreamException;
use Genkgo\Xsl\Exception\StreamException;
use Genkgo\Xsl\Stream;
use Genkgo\Xsl\Transpiler;

class StreamTest extends AbstractTestCase
{
    public function testStreamOpenRead()
    {
        $openedPath = '';

        $stream = new Stream();
        $stream->context = stream_context_create([
            'gxsl' => [
                'transpiler' => new Transpiler(new Context(new DOMDocument('1.0', 'UTF-8')))
            ]
        ]);
        $stream->stream_open('gxsl://~', 'r', 0, $openedPath);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', trim($stream->stream_read(9999)));
    }

    public function testStreamOpenWithoutContext()
    {
        $this->setExpectedException(StreamException::class);
        $openedPath = '';

        $stream = new Stream();
        $stream->context = stream_context_create();
        $stream->stream_open('gxsl://~', 'r', 0, $openedPath);
    }

    public function testStreamWrite()
    {
        $this->setExpectedException(ReadOnlyStreamException::class);

        $stream = new Stream();
        $stream->stream_write('something');
    }
}
