<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:param name="date" />

    <xsl:template match="/">
        <xsl:value-of select="adjust-dateTime-to-timezone(xs:dateTime($date))" />
    </xsl:template>

</xsl:stylesheet>