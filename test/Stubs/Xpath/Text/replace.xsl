<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="param1" />
    <xsl:param name="param2" />
    <xsl:param name="param3" />
    <xsl:param name="param4" select="''" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="replace($param1, $param2, $param3, $param4)" />
    </xsl:template>

</xsl:stylesheet>