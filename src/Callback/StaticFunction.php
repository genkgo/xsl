<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;

final class StaticFunction implements FunctionInterface, ReplaceFunctionInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ReplaceFunctionInterface
     */
    private $replacer;

    /**
     * @param string $name
     * @param ReplaceFunctionInterface $replacer
     */
    public function __construct(string $name, ReplaceFunctionInterface $replacer)
    {
        $this->name = $name;
        $this->replacer = $replacer;
    }

    /**
     * @param FunctionMap $functionMap
     */
    public function register(FunctionMap $functionMap): void
    {
        $functionMap->set($this->name, $this);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement): array
    {
        return $this->replacer->replace($lexer, $currentElement);
    }
}
