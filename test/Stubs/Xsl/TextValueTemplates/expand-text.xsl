<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection" expand-text="yes">
        {cd/artist}
    </xsl:template>

</xsl:stylesheet>