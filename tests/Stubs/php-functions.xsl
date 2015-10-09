<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
    <xsl:param name="owner" select="'Genkgo Xsl'"/>

    <xsl:output method="xml"
                indent="no"
                omit-xml-declaration="yes"
                encoding="UTF-8"/>

    <xsl:template match="collection">
        <xsl:value-of select="php:functionString('strpos', 'Hello', 'World')" />
    </xsl:template>

</xsl:stylesheet>