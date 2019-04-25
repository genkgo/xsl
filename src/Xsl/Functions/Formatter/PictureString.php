<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\Formatter;

final class PictureString
{
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
     * @throws \InvalidArgumentException
     */
    public function __construct($picture)
    {
        $matches = \preg_match_all('/([YMDdWwFHhmsfZzPCE])\\s*(.*)/', $picture, $groups);
        if ($matches === 0) {
            throw new \InvalidArgumentException('No valid components found', 1340);
        }

        $this->componentSpecifier = $groups[1][0];
        $modifier = $groups[2][0];

        if (($comma = \strpos($modifier, ',')) !== false) {
            $this->presentationModifier = \substr($modifier, 0, $comma);

            if (($dash = \strpos($modifier, '-')) !== false) {
                $widthModifier = \substr($modifier, $comma + 1);
                [$minWidth, $maxWidth] = \explode('-', $widthModifier);
                if ($maxWidth !== '*') {
                    $this->maxWidth = (int)$maxWidth;
                }

                if ($minWidth !== '*') {
                    $this->minWidth = (int)$minWidth;
                }
            } else {
                $this->minWidth = (int) \substr($modifier, $comma + 1);
            }
        } else {
            $this->presentationModifier = $modifier;
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
     * @return int|null
     */
    public function getMaxWidth():? int
    {
        return $this->maxWidth;
    }

    /**
     * @return int|null
     */
    public function getMinWidth():? int
    {
        return $this->minWidth;
    }
}
