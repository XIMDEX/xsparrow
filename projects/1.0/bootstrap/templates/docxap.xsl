<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:param name="xmlcontent"/>
  <xsl:output indent="yes" omit-xml-declaration="yes" method="html"/>
  <xsl:include href="{URL_PATH}/data/nodes/{PROJECT_NAME}/templates/templates_include.xsl"/>
  <xsl:template name="docxap" match="docxap">
    <xsl:choose>
      <xsl:when test="/docxap/@tipo_documento='configuration.xml'">
        <xsl:call-template name="docxap-configuration"/>
      </xsl:when>

      <xsl:when test="/docxap/@tipo_documento='basic-document.xml'">
        <xsl:call-template name="docxap-configuration"/>
      </xsl:when>

      <xsl:when test="/docxap/@tipo_documento='footer.xml'">
        <xsl:call-template name="docxap-footer"/>
      </xsl:when>

      <xsl:when test="/docxap/@tipo_documento='menu.xml'">
        <xsl:call-template name="docxap-menu"/>
      </xsl:when>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>
