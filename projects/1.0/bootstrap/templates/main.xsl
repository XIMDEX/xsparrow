<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="main" match="main">
   <xsl:choose>
    <xsl:when test="/docxap/@tipo_documento='configuration.xml'">      
        <div class="container" uid="{@uid}">
          <xsl:apply-templates select="header|content|footer"/>
        </div>      
    </xsl:when>
    <xsl:otherwise>                                   
     <xsl:apply-templates select="config-header"/>			<!-- TODO -->
   </xsl:otherwise>
 </xsl:choose>
</xsl:template>
</xsl:stylesheet>
