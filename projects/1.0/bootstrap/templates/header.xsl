<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template name="header" match="header">
		<xsl:variable name="xsparrowClass">
			<xsl:if test="/docxap/@transformer='XSparrow'">
				<xsl:value-of select="concat('xsparrow-',local-name())"/>
			</xsl:if>
		</xsl:variable>	
			
			<div class="jumbotron header {$xsparrowClass}" uid="{@uid}">
  				<xsl:apply-templates select="header-title"/>
  				<xsl:apply-templates select="header-subtitle"/>
			</div>		
	</xsl:template>
</xsl:stylesheet>
