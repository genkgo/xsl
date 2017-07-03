<xsl:stylesheet version="2.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:my="https://github.com/genkgo/xsl/tree/master/tests/Stubs/Extension/MyExtension">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        <xsl:value-of select="my:collection-sqrt(1, 2, 3, 4, 5, 6)" />
    </xsl:template>

</xsl:stylesheet>