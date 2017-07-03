<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection" expand-text="yes">
        <xsl:for-each select="cd" expand-text="no">
            {artist}
        </xsl:for-each>
    </xsl:template>

</xsl:stylesheet>