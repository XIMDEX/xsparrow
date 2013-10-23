<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
   	<xsl:template name="paragraph" match="paragraph">
   		<xsl:variable name="isLead">
   			<xsl:choose>
				<xsl:when test="@type='lead'">
					<xsl:text>lead</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					
				</xsl:otherwise>
			</xsl:choose>	
   		</xsl:variable>   		
		<p uid="{@uid}" class="$isLead text-@text-align text-@text-style">
			<xsl:apply-templates/>
		</p>
   	</xsl:template>
</xsl:stylesheet>
