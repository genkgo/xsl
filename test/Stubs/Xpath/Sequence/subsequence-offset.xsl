<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="subsequence(tokenize('xsl2 is transpiled by genkgo/xsl', '\s'), 3)" />
    </xsl:template>

</xsl:stylesheet>