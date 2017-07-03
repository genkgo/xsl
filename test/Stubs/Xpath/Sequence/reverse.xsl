<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="reverse(('xsl2','is','transpiled','by','genkgo/xsl'))" />
    </xsl:template>

</xsl:stylesheet>