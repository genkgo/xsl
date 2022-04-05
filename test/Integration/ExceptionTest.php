<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Exception\TransformationException;
use Genkgo\Xsl\XsltProcessor;

final class ExceptionTest extends AbstractTestCase
{
    public function testException(): void
    {
        $this->expectException(TransformationException::class);

        $xslDoc = new DOMDocument();
        $xslDoc->loadXML((string)\file_get_contents('Stubs/invalid-stylesheet.xsl'));

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpiler->transformToXML($xmlDoc);
    }
}
