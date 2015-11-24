<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="minutes-from-time(xs:time('05:08:40'))" />
    </xsl:template>

</xsl:stylesheet>