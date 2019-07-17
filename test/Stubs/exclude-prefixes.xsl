<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:test="urn:test"
                xmlns:xs="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xs:function name="test:test">
        <xsl:value-of select="'test'" />
    </xs:function>

    <xsl:template match="/">
        <p>
            <xsl:value-of select="test:test()" />
        </p>
    </xsl:template>

</xsl:stylesheet>