<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="owner" select="'Genkgo Xsl'"/>

    <xsl:output method="xml"
                indent="no"
                encoding="UTF-8"/>

    <xsl:template match="collection">
        <test>
            <h1>
                <xsl:value-of select="$owner"/>
            </h1>
            <xsl:apply-templates/>
        </test>
    </xsl:template>

    <xsl:template match="cd">
        <h2>
            <xsl:value-of select="title"/>
        </h2>
        <h3>
            <xsl:value-of select="concat('by ', artist, ' - ', year)" />
        </h3>
    </xsl:template>

</xsl:stylesheet>