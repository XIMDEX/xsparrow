<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="body" match="body">
    <xsl:variable name="xsparrowClass">
      <xsl:if test="/docxap/@transformer='XSparrow'">
        <xsl:value-of select="concat('xsparrow-',local-name())"/>
      </xsl:if>
    </xsl:variable>
   <xsl:choose>
    <xsl:when test="/docxap/@tipo_documento='configuration.xml'">  
    <body class="{$xsparrowClass}">    
        <div class="container" uid="{@uid}">
          <xsl:apply-templates select="header|content|footer"/>
        </div>
      </body>
    </xsl:when>
    <xsl:otherwise>                                   
     <xsl:apply-templates select="config-header"/>			<!-- TODO -->
   </xsl:otherwise>
 </xsl:choose>
</xsl:template>
</xsl:stylesheet>
