<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <cd>
            <xsl:attribute name="attr" select=".//instrument" separator=", " />
        </cd>
    </xsl:template>

</xsl:stylesheet>