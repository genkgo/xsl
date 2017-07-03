<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="packages">

        <xsl:for-each-group select="package" group-by="year-from-dateTime(xs:dateTime(year/@value))">
            <xsl:value-of select="current-grouping-key()" />
        </xsl:for-each-group>

    </xsl:template>

</xsl:stylesheet>