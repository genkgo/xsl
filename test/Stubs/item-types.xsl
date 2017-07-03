<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">
        <xsl:apply-templates select=".//Requirement" mode="min"/>
    </xsl:template>

    <xsl:template match="Requirement|EffectReq" mode="min">
        <xsl:value-of select="count(id(@id)/preceding-sibling::*)"/> => <xsl:value-of select="@min"/><xsl:if test="position()!=last()">, </xsl:if>
    </xsl:template>

</xsl:stylesheet>