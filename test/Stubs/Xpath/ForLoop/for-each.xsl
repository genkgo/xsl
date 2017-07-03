<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <xsl:for-each select="1 to 10">
            <xsl:value-of select="." />
        </xsl:for-each>
    </xsl:template>

</xsl:stylesheet>