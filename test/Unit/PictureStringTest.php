<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

final class PictureStringTest extends AbstractTestCase
{
    public function testComponentOnly(): void
    {
        $pictureString = new PictureString('Y');
        $this->assertEquals('Y', $pictureString->getComponentSpecifier());
    }

    public function testComponentAndPresentationModifier(): void
    {
        $pictureString = new PictureString('FN');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('N', $pictureString->getPresentationModifier());
    }

    public function testComponentAndPresentationModifierAndMinWidth(): void
    {
        $pictureString = new PictureString('FN,1');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('N', $pictureString->getPresentationModifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
    }

    public function testComponentAndMinWidth(): void
    {
        $pictureString = new PictureString('F,1');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
    }

    public function testComponentAndPresentationModifierAndMinMaxWidth(): void
    {
        $pictureString = new PictureString('FN,1-2');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('N', $pictureString->getPresentationModifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
        $this->assertEquals('2', $pictureString->getMaxWidth());
    }

    public function testComponentAndMinMaxWidth(): void
    {
        $pictureString = new PictureString('F,1-2');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
        $this->assertEquals('2', $pictureString->getMaxWidth());
    }

    public function testLongerPresentationModifier(): void
    {
        $pictureString = new PictureString('FNn,1-2');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('Nn', $pictureString->getPresentationModifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
        $this->assertEquals('2', $pictureString->getMaxWidth());
    }

    public function testUnlimitedMinWidth(): void
    {
        $pictureString = new PictureString('FNn,*-2');
        $this->assertNull($pictureString->getMinWidth());
    }

    public function testUnlimitedMaxWidth(): void
    {
        $pictureString = new PictureString('FNn,1-*');
        $this->assertNull($pictureString->getMaxWidth());
    }

    public function testInvalidComponent(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new PictureString('G');
    }
}
