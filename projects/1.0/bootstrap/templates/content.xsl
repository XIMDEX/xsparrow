<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="content" match="content">
    <xsl:variable name="xsparrowClass">
      <xsl:if test="/docxap/@transformer='XSparrow'">
        <xsl:value-of select="concat('xsparrow-',local-name())"/>
      </xsl:if>
    </xsl:variable>
    <xsl:variable name="localName">      
      <xsl:value-of select="local-name()"/>      
    </xsl:variable>

    <xsl:variable name="leftColumnWidth">
      <xsl:choose>
        <xsl:when test="@left-column='yes'">
          <xsl:choose>
            <xsl:when test="@right-column='yes'">
              <xsl:value-of select="3"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="6"/>
            </xsl:otherwise>
          </xsl:choose>          
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="0"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>

    <xsl:variable name="rightColumnWidth">
      <xsl:choose>
        <xsl:when test="@right-column='yes'">
          <xsl:choose>
            <xsl:when test="@left-column='yes'">
              <xsl:value-of select="3"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="6"/>
            </xsl:otherwise>
          </xsl:choose>          
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="0"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>

    <xsl:variable name="mainColumnWidth">
      <xsl:value-of select="12 - $leftColumnWidth - $rightColumnWidth"/>
    </xsl:variable>

    <div class="container {$xsparrowClass} {$localName}" uid="{@uid}" >
      <xsl:choose>
        <xsl:when test="/docxap/@transformer='XSparrow'">
          
            <xsl:call-template name="left-column">
              <xsl:with-param name="width" select="$leftColumnWidth"/>
            </xsl:call-template>
          
          <xsl:call-template name="main-column">
            <xsl:with-param name="width" select="$mainColumnWidth"/>
          </xsl:call-template>

          
            <xsl:call-template name="right-column">
              <xsl:with-param name="width" select="$rightColumnWidth"/>
            </xsl:call-template>
          
            
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
