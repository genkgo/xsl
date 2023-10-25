<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:x="urn:math">

    <!-- cannot combine import and include for functions, see https://gitlab.gnome.org/GNOME/libxslt/-/issues/97 -->
    <xsl:include href="function-import.xsl" />
    <xsl:include href="function-include.xsl" />

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <xsl:value-of select="x:pow(count(cd))" />
        <xsl:value-of select="' '" />
        <xsl:value-of select="x:sqrt(count(cd))" />
    </xsl:template>

</xsl:stylesheet>