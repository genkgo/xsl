<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Exception\CastException;
use Genkgo\Xsl\Schema\DataTypeParser;
use Genkgo\Xsl\Schema\XmlSchema;

final class Arguments implements \Countable
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @var DataTypeParser
     */
    private $dataTypeParser;

    /**
     * @param DataTypeParser $dataTypeParser
     * @param array $arguments
     */
    public function __construct(DataTypeParser $dataTypeParser, array $arguments)
    {
        $this->arguments = $arguments;
        $this->dataTypeParser = $dataTypeParser;
    }

    /**
     * @param int $number
     * @return mixed
     */
    public function get(int $number)
    {
        if (isset($this->arguments[$number])) {
            return $this->arguments[$number];
        }

        throw new \InvalidArgumentException('Argument ' . $number . ' is not passed');
    }

    /**
     * @param int $number
     * @return mixed
     */
    public function castFromSchemaType(int $number)
    {
        $value = $this->get($number);
        if (\is_scalar($value)) {
            return $value;
        }

        if (\is_array($value) && \count($value) === 1) {
            return $this->convertFromSchemaTypeToPhpType($value[0]);
        }

        if (\is_array($value) && \count($value) === 0) {
            return '';
        }

        throw new CastException('Cannot convert list of elements to string');
    }

    /**
     * @param int $number
     * @return mixed
     */
    public function castAsSequence(int $number)
    {
        $value = $this->get($number);

        if (\is_scalar($value)) {
            throw new CastException('Cannot convert scalar to sequence');
        }

        if (\is_array($value)) {
            return \array_map(
                function ($deepValue) {
                    return $deepValue->textContent;
                },
                $value
            );
        }

        throw new CastException('Cannot convert list of elements to string');
    }

    /**
     * @return array
     */
    public function unpack(): array
    {
        return \array_map(
            function ($value) {
                if (\is_array($value)) {
                    return \array_map(
                        function ($deepValue) {
                            return $deepValue->textContent;
                        },
                        $value
                    );
                }

                return $value;
            },
            $this->arguments
        );
    }

    /**
     * @return array
     */
    public function unpackFromSchemaType(): array
    {
        return \array_map(
            function ($value) {
                if (\is_array($value) && \count($value) === 1) {
                    return $this->convertFromSchemaTypeToPhpType(\reset($value));
                }

                if (\is_array($value) && \count($value) === 0) {
                    return '';
                }

                return $value;
            },
            $this->arguments
        );
    }

    /**
     * @param int $number
     * @param string $name
     */
    public function assertSchemaType(int $number, string $name): void
    {
        $node = $this->get($number)[0] ?? '';

        if ($node instanceof \DOMDocument) {
            $node = $node->documentElement;
        }

        if ($node instanceof \DOMNode === false) {
            throw new \InvalidArgumentException("Expected a {$name} object, got scalar");
        }

        if ($node->namespaceURI !== XmlSchema::URI || $node->localName !== $name) {
            $nsSchema = XmlSchema::URI;
            throw new \InvalidArgumentException("Expected a {$nsSchema}:{$name} object, got {$node->nodeName}");
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->arguments);
    }

    /**
     * @param \DOMNode $node
     * @return mixed
     */
    private function convertFromSchemaTypeToPhpType(\DOMNode $node)
    {
        if ($node instanceof \DOMDocument) {
            $node = $node->documentElement;
        }

        if ($node->namespaceURI === XmlSchema::URI) {
            return $this->dataTypeParser->parse($node);
        }

        return $node->textContent;
    }
}
