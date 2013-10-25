<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="docxap-configuration" match="docxap-configuration">

    <xsl:text disable-output-escaping="yes">
      <![CDATA[<!DOCTYPE html>]]>
    </xsl:text>

    <html lang="en">
      <head>
        <xsl:call-template name="INCLUDE-metas"/>
        <xsl:call-template name="INCLUDE-css"/>
        <xsl:call-template name="INCLUDE-style"/>
      </head>
      
        <xsl:choose>
          <xsl:when test="/docxap/@tipo_documento='configuration.xml'">
            <xsl:apply-templates select="/docxap/xsparrow-theme/body"/>
          </xsl:when>
          <xsl:when test="/docxap/@tipo_documento='menu.xml'">
            <xsl:apply-templates select="/docxap/xsparrow-theme/body"/>
          </xsl:when>
          <xsl:when test="/docxap/@tipo_documento='new.xml'">
            <xsl:apply-templates select="/docxap/ximlet/menu/.."/>
            <xsl:apply-templates select="/docxap/ximlet/xsparrow-theme/.."/>
            <xsl:apply-templates select="/docxap/new"/>
          </xsl:when>
          <xsl:when test="/docxap/@tipo_documento='basic-document.xml'">
            <body class="body">
            <xsl:apply-templates select="/docxap/ximlet/menu/.."/>
            <div class="container">
              <xsl:apply-templates select="/docxap/ximlet/xsparrow-theme/body/header/../../.."/>
              <xsl:apply-templates select="content"/>
              <xsl:apply-templates select="/docxap/ximlet/xsparrow-theme/body/footer"/>
            </div>
            <xsl:call-template name="INCLUDE-js"/>
          </body>
          </xsl:when>
          <xsl:otherwise>
            <xsl:apply-templates select="/docxap/ximlet/menu/.."/>
            <xsl:apply-templates select="/docxap/ximlet/xsparrow-theme/config/.."/>
            <xsl:apply-templates select="/docxap/ximlet/xsparrow-theme/main/content"/>
            <xsl:apply-templates select="/docxap/ximlet/footer/.."/>
          </xsl:otherwise>
        </xsl:choose>      
    </html>
  </xsl:template>
</xsl:stylesheet>
