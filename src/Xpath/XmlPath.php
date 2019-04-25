<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;

final class XmlPath implements XmlNamespaceInterface
{
    public const URI = '';

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
