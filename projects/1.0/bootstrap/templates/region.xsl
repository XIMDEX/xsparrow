<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template name="region" match="region">
		<xsl:choose>
			<xsl:when test="*">
				<div class="col-md-{@cols} col-md-offset-{@offset}" uid="{@uid}">
					<xsl:apply-templates select="*"/>
				</div>
			</xsl:when>
			<xsl:otherwise>
				<div class="col-md-{@cols} col-md-offset-{@offset}" uid="{@uid}">
					Insert content here
				</div>
			</xsl:otherwise>
		</xsl:choose> 		
	</xsl:template>
</xsl:stylesheet>
