<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:template name="ximlet" match="ximlet">
        <xsl:choose>
            <xsl:when test="/docxap/@transformer!='xEDIT'">
                <xsl:for-each select="*">
                    <xsl:if test="name() != 'theme-properties'">
                        <xsl:apply-templates select="."/>
                    </xsl:if>
                </xsl:for-each>
            </xsl:when>
            <xsl:when test="not(xsparrow-theme)">
                <div uid="{@uid}">
                    <xsl:apply-templates/>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <span uid="{@uid}">
                    <xsl:apply-templates select="xsparrow-theme/body/header"/>
                </span>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
</xsl:stylesheet>
