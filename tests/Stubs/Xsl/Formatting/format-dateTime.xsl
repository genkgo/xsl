<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xsl:param name="dateTime" />
    <xsl:param name="picture" />
    <xsl:param name="language" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:choose>
            <xsl:when test="$language">
                <xsl:value-of select="format-dateTime(xs:dateTime($dateTime), $picture, $language)" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="format-dateTime(xs:dateTime($dateTime), $picture)" />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>