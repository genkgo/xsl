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
     * @var string
     */
    private $widthModifier;

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
        $this->presentationModifier = substr($groups[2][0], 0, -1);
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
     * @return string
     */
    public function getWidthModifier()
    {
        return $this->widthModifier;
    }

}