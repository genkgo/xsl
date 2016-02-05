<?php
namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;

/**
 * Class PictureString
 * @package Genkgo\Xsl\Xsl\Functions\Formatter
 */
final class PictureString {

    /**
     * @var string
     */
    private $componentSpecifier;

    /**
     * @var string
     */
    private $presentationModifier;

    /**
     * @var int
     */
    private $minWidth;
    /**
     * @var int
     */
    private $maxWidth;

    /**
     * @param string $picture
     * @throws InvalidArgumentException
     */
    public function __construct($picture)
    {
        $matches = preg_match_all('/([YMDdWwFHhmsfZzPCE])\\s*(.*)/', $picture, $groups);
        if ($matches === 0) {
            $exception = new InvalidArgumentException('No valid components found');
            $exception->setErrorCode('XTDE1340');
            throw $exception;
        }

        $this->componentSpecifier = $groups[1][0];
        $modifier = $groups[2][0];

        if (($comma = strpos($modifier, ',')) !== false) {
            $this->presentationModifier = substr($modifier, 0, $comma);

            if ($dash = strpos($modifier, '-') !== false) {
                $widthModifier = substr($modifier, $comma + 1, -1);
                list($this->minWidth, $this->maxWidth) = array_map('intval', explode('-', $widthModifier));
                if ($this->maxWidth === '*') {
                    $this->maxWidth = null;
                }
                if ($this->minWidth === '*') {
                    $this->minWidth = null;
                }
            } else {
                $this->minWidth = (int) substr($modifier, $dash, -1);
            }
        } else {
            $this->presentationModifier = substr($modifier, 0, -1);
        }
    }

    /**
     * @return string
     */
    public function getComponentSpecifier()
    {
        return $this->componentSpecifier;
    }

    /**
     * @return string
     */
    public function getPresentationModifier()
    {
        return $this->presentationModifier;
    }

    /**
     * @return int
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @return int
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

}