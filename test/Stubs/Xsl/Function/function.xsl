<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:x="urn:math">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:function name="x:sqrt">
        <xsl:param name="number" />

        <xsl:value-of select="$number * $number" />
    </xsl:function>

    <xsl:template match="collection">
        <xsl:value-of select="x:sqrt(count(cd))" />
    </xsl:template>

</xsl:stylesheet>