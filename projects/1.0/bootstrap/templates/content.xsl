<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="content" match="content">
    <xsl:param name="mainColumnWidth"/>
    <xsl:variable name="xsparrowClass">
      <xsl:if test="/docxap/@transformer='XSparrow'">
        <xsl:value-of select="concat('xsparrow-',local-name())"/>
      </xsl:if>
    </xsl:variable>
    <xsl:variable name="localName">      
      <xsl:value-of select="local-name()"/>      
    </xsl:variable>

    <div class="container">
      <xsl:choose>
        <xsl:when test="/docxap/@transformer='XSparrow'">
          <xsl:variable name="column-width" select="12"/>
          <div class="col-md-{$column-width}">
            <div class="row {$xsparrowClass} {$localName}">
              <div class="col-md-12" uid="{@uid}">
                <div class="page-header" uid="{@uid}">
                  <h1>
                    Custom this theme
                  </h1>
                </div>              
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
              </div>
            </div>
          </div>
          </xsl:when>
          <xsl:otherwise>
            <xsl:choose>
              <!--If has child-->
              <xsl:when test="*">        
                <div class="row {$xsparrowClass} {$localName}" uid="{@uid}"  >
                 <xsl:apply-templates select="*"/>
               </div>

             </xsl:when>
             <xsl:otherwise>
              <div class="row" uid="{@uid}">
                Insert content here
              </div>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:otherwise>
      </xsl:choose>  
      
    </div>     
  </xsl:template>
</xsl:stylesheet>
