<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="distinct-values((1, 2, 3, 4, 2, 4))" />
    </xsl:template>

</xsl:stylesheet>