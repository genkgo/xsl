<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="packages">

        <xsl:for-each-group select="package" group-by="floor((position() - 1) div 2)">
            <xsl:value-of select="count(current-group())" />
        </xsl:for-each-group>

    </xsl:template>

</xsl:stylesheet>