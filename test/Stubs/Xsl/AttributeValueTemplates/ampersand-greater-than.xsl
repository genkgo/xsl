<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="collection">
        <xsl:apply-templates select="cd" />
    </xsl:template>

    <xsl:template match="cd">
        <a href="link?x=y&amp;year={year[. &gt; 1995 ]}">matched</a>
    </xsl:template>

</xsl:stylesheet>