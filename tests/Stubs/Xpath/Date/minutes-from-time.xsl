<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="minutes-from-dateTime(xs:dateTime('2015-11-24 19:59:20'))" />
    </xsl:template>

</xsl:stylesheet>