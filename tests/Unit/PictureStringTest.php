<?php
namespace Genkgo\Xsl\Unit;

use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

class PictureStringTest extends AbstractTestCase
{
    public function testComponentOnly()
    {
        $pictureString = new PictureString('[Y]');
        $this->assertEquals('Y', $pictureString->getComponentSpecifier());
    }

    public function testComponentAndPresentationModifier()
    {
        $pictureString = new PictureString('[FN]');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('N', $pictureString->getPresentationModifier());
    }

    public function testComponentAndPresentationModifierAndMinWidth()
    {
        $pictureString = new PictureString('[FN,1]');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('N', $pictureString->getPresentationModifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
    }

    public function testComponentAndMinWidth()
    {
        $pictureString = new PictureString('[F,1]');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
    }

    public function testComponentAndPresentationModifierAndMinMaxWidth()
    {
        $pictureString = new PictureString('[FN,1-2]');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('N', $pictureString->getPresentationModifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
        $this->assertEquals('2', $pictureString->getMaxWidth());
    }

    public function testComponentAndMinMaxWidth()
    {
        $pictureString = new PictureString('[F,1-2]');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
        $this->assertEquals('2', $pictureString->getMaxWidth());
    }

    public function testLongerPresentationModifier()
    {
        $pictureString = new PictureString('[FNn,1-2]');
        $this->assertEquals('F', $pictureString->getComponentSpecifier());
        $this->assertEquals('Nn', $pictureString->getPresentationModifier());
        $this->assertEquals('1', $pictureString->getMinWidth());
        $this->assertEquals('2', $pictureString->getMaxWidth());
    }

    public function testUnlimitedMinWidth()
    {
        $pictureString = new PictureString('[FNn,*-2]');
        $this->assertNull($pictureString->getMinWidth());
    }

    public function testUnlimitedMaxWidth()
    {
        $pictureString = new PictureString('[FNn,1-*]');
        $this->assertNull($pictureString->getMaxWidth());
    }

    public function testInvalidComponent()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new PictureString('[G]');
    }
}
