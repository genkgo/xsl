<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Exception\ReadOnlyStreamException;
use Genkgo\Xsl\Exception\StreamException;
use Genkgo\Xsl\Stream;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\Util\TransformerCollection;

final class StreamTest extends AbstractTestCase
{
    public function testStreamOpenRead(): void
    {
        $stream = new Stream();
        $stream->context = $this->createContext();
        $stream->stream_open(Stream::PROTOCOL . Stream::HOST . Stream::ROOT);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', \trim($stream->stream_read(9999)));
    }

    public function testStreamOpenWithoutContext(): void
    {
        $this->expectException(StreamException::class);

        $stream = new Stream();
        $stream->context = \stream_context_create();
        $stream->stream_open(Stream::PROTOCOL . Stream::HOST . Stream::ROOT);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', \trim($stream->stream_read(9999)));
    }

    public function testStreamWrite(): void
    {
        $this->expectException(ReadOnlyStreamException::class);

        $stream = new Stream();
        $stream->stream_write();
    }

    public function testFullPath(): void
    {
        $unregister = false;
        if (\in_array('gxsl', \stream_get_wrappers()) === false) {
            \stream_wrapper_register('gxsl', Stream::class);
            $unregister = true;
        }

        $this->assertEquals(
            \file_get_contents('Stubs/include2.xsl'),
            \file_get_contents('gxsl://localhost/' . \urlencode(\getcwd() . '/Stubs/include2.xsl'))
        );

        if ($unregister) {
            \stream_wrapper_unregister('gxsl');
        }
    }

    /**
     * @return resource
     */
    private function createContext()
    {
        return \stream_context_create([
            'gxsl' => [
                'transpiler' => new Transpiler(
                    new TransformationContext(new DOMDocument('1.0', 'UTF-8'), new TransformerCollection(), new FunctionCollection()),
                    new NullCache()
                )
            ]
        ]);
    }
}
