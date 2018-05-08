<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xs="http://www.w3.org/2001/XMLSchema">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">

        <xsl:value-of select="xs:integer(cd[1]/year)" />

    </xsl:template>

</xsl:stylesheet>