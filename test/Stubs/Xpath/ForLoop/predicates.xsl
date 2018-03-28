<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <xsl:variable name="min" select="min(cd/year)" />
        <xsl:for-each select="cd[year = $min to 1997]">
            <xsl:value-of select="year" />
        </xsl:for-each>
    </xsl:template>

</xsl:stylesheet>