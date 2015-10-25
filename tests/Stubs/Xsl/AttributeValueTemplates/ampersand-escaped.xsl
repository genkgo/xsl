<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="collection">
        <xsl:apply-templates />
    </xsl:template>

    <xsl:template match="cd[substring-after(title, 'Electric ') = 'Ladyland']">
        <a href="link?x=y&amp;a=b#{{substring-after(title, 'Electric ')}}">matched</a>
    </xsl:template>

</xsl:stylesheet>