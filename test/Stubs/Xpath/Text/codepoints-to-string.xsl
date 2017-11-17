<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="param1" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="codepoints-to-string((104, 101, 108, 108, 111, 32, 119, 111, 114, 108, 100))" />
        <xsl:value-of select="codepoints-to-string(32)" />
        <xsl:value-of select="codepoints-to-string(104)" />
        <xsl:value-of select="codepoints-to-string(101)" />
        <xsl:value-of select="codepoints-to-string(108)" />
        <xsl:value-of select="codepoints-to-string(108)" />
        <xsl:value-of select="codepoints-to-string(111)" />
        <xsl:value-of select="codepoints-to-string(32)" />
    </xsl:template>

</xsl:stylesheet>