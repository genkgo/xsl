<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="param1" />
    <xsl:param name="param2" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="count(tokenize('xsl2.is.transpiled.by.genkgo/xsl', '.', 'q'))" />
    </xsl:template>

</xsl:stylesheet>
