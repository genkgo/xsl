<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="packages">
        <xsl:for-each select="package">
            <xsl:apply-templates select="." />
        </xsl:for-each>
    </xsl:template>

    <xsl:template match="package">
        <xsl:for-each-group select="dependency" group-by="@author">
            <xsl:value-of select="current-grouping-key()" />
        </xsl:for-each-group>
    </xsl:template>

</xsl:stylesheet>