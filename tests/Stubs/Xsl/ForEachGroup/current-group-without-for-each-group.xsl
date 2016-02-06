<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="packages">

        <xsl:value-of select="current-group()" />
        <xsl:value-of select="current-grouping-key()" />

    </xsl:template>

</xsl:stylesheet>