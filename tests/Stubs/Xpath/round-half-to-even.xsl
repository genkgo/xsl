<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />
    <xsl:param name="number" />
    <xsl:param name="precision" />

    <xsl:template match="/">
        <xsl:value-of select="round-half-to-even($number, $precision)" />
    </xsl:template>

</xsl:stylesheet>