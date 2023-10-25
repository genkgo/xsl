<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:x="urn:math">

    <xsl:function name="x:pow">
        <xsl:param name="number" />

        <xsl:value-of select="$number * $number" />
    </xsl:function>

</xsl:stylesheet>