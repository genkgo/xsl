<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="param1" />
    <xsl:param name="param2" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="upper-case('php')" />
    </xsl:template>

</xsl:stylesheet>