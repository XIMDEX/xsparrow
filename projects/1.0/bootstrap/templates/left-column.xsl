<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
         	<xsl:template name="left-column" match="left-column">
                <xsl:param name="width"/>
<xsl:variable name="xsparrowClass">
      <xsl:if test="/docxap/@transformer='XSparrow'">
        <xsl:value-of select="concat('xsparrow-',name())"/>
      </xsl:if>
    </xsl:variable>
    <xsl:variable name="localName">      
      <xsl:value-of select="'left-column'"/>      
    </xsl:variable>
<div class="col-md-{$width} col-sm-{$width} {$xsparrowClass} {$localName}" uid="{@uid}">
            <div class="row">
            <xsl:choose>
              <xsl:when test="/docxap/@transformer='XSparrow'">
                <div class="page-header">
                  <h1>
                    Custom this theme
                  </h1>
                </div>              
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
              </xsl:when>
              <xsl:otherwise>
                
              </xsl:otherwise>
              
            </xsl:choose>
          </div>
              </div>    
         	</xsl:template>
</xsl:stylesheet>
