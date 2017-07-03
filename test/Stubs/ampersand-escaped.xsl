<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output method="xml"
                indent="no"
                encoding="UTF-8"/>

    <xsl:template match="collection">
        <xsl:apply-templates select="cd[year &gt; 1995]"/>
    </xsl:template>

    <xsl:template match="cd">
        <xsl:value-of select="title" />
    </xsl:template>

</xsl:stylesheet>