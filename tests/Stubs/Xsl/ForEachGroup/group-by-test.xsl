<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="packages">

        <xsl:for-each-group select="package" group-by="author">
            <xsl:if test="current-group()//name = 'CAMT'">
                <xsl:value-of select="concat(count(current-group()[.//name = 'CAMT']),  ' CAMT packages')" />
            </xsl:if>
        </xsl:for-each-group>

    </xsl:template>

</xsl:stylesheet>