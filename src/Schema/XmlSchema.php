<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;

final class XmlSchema implements XmlNamespaceInterface
{
    const URI = 'http://www.w3.org/2001/XMLSchema';

    /**
     * @param TransformerCollection $transformers
     * @param FunctionCollection $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionCollection $functions): void
    {
        $functions->attach(self::URI, new FunctionMap(self::URI));
    }
}
