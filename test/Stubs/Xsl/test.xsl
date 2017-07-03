<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <xsl:apply-templates />
    </xsl:template>

    <xsl:template match="cd">
        <xsl:if test="substring-after(title, 'Electric ') = 'Ladyland'">
            <test><xsl:value-of select="title" /></test>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>