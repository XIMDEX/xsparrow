<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  	<xsl:template name="header-title" match="header-title">
  		<xsl:variable name="xsparrowClass">
			<xsl:if test="/docxap/@transformer='XSparrow'">
				<xsl:value-of select="concat('xsparrow-',local-name())"/>
			</xsl:if>
		</xsl:variable>
		<h1 uid="{@uid}" class="{$xsparrowClass}">
			<xsl:value-of select="."/>
		</h1>		
      		
      	</xsl:template>
</xsl:stylesheet>
