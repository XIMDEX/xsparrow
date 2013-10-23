<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="blockquote" match="blockquote">
  	<xsl:variable name="align">
  		<xsl:choose>
  			<xsl:when test="@align='right'">
  				<xsl:text>pull-right</xsl:text>
  			</xsl:when>
  		</xsl:choose>
  	</xsl:variable>
  	<blockquote>
  		<p uid="{@uid}" class="{$align}">
  			<xsl:apply-templates select="text()"/>
  		</p>
  		<xsl:apply-templates select="*"/>
  	</blockquote>
  </xsl:template>
</xsl:stylesheet>