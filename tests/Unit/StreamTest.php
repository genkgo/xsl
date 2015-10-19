<?php
namespace Genkgo\Xsl\Unit;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Exception\ReadOnlyStreamException;
use Genkgo\Xsl\Exception\StreamException;
use Genkgo\Xsl\Stream;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;

class StreamTest extends AbstractTestCase
{
    public function testStreamOpenRead()
    {
        $stream = new Stream();
        $stream->context = stream_context_create([
            'gxsl' => [
                'transpiler' => new Transpiler(
                    new TransformationContext(new DOMDocument('1.0', 'UTF-8'), new TransformerCollection(), new FunctionMap())
                )
            ]
        ]);
        $stream->stream_open('gxsl://~~');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', trim($stream->stream_read(9999)));
    }

    public function testStreamOpenWithoutContext()
    {
        $this->setExpectedException(StreamException::class);

        $stream = new Stream();
        $stream->context = stream_context_create();
        $stream->stream_open('gxsl://~');
    }

    public function testStreamWrite()
    {
        $this->setExpectedException(ReadOnlyStreamException::class);

        $stream = new Stream();
        $stream->stream_write();
    }
}
