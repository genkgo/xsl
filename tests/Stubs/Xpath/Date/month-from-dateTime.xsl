<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="month-from-dateTime(xs:dateTime('2015-07-07 15:00:00'))" />
    </xsl:template>

</xsl:stylesheet>