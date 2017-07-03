<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="string-join(subsequence(tokenize('some string with an &amp; included', '\s'), 1, 2), ' ')" />
    </xsl:template>

</xsl:stylesheet>