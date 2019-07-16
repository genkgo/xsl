<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="packages">

        <xsl:for-each-group select="package" group-by="author">
            <xsl:sort select="current-grouping-key()" order="descending"/>

            <xsl:for-each select="current-group()">
                <xsl:sort select="name" />

                <xsl:value-of select="name" />
            </xsl:for-each>
        </xsl:for-each-group>

    </xsl:template>

</xsl:stylesheet>