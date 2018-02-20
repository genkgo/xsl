<!DOCTYPE xsl:stylesheet [<!ENTITY test SYSTEM "php://filter/read=convert.base64-encode/resource=/tmp/xsl-passwd">]>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="collection">
        &test;
    </xsl:template>

</xsl:stylesheet>