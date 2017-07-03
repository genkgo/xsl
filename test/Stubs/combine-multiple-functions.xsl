<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <xsl:value-of select="floor(round-half-to-even(sum(person/weight), 2))" />
    </xsl:template>

</xsl:stylesheet>