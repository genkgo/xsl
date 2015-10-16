<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xsl:param name="dateTime" />
    <xsl:param name="picture" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="format-dateTime(xs:dateTime($dateTime), $picture)" />
    </xsl:template>

</xsl:stylesheet>