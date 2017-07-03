<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="param1" />
    <xsl:param name="param2" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:for-each select="in-scope-prefixes(//cd[1])">
            <xsl:value-of select="concat('|', ., '|')" />
        </xsl:for-each>
    </xsl:template>

</xsl:stylesheet>