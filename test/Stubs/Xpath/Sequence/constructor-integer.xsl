<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">

        <xsl:value-of select="(0, 1, 2, 3)" />

    </xsl:template>

</xsl:stylesheet>