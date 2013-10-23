<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template name="header" match="header">
		<div class="container">
			<div class="jumbotron header" uid="{@uid}">
  				<xsl:apply-templates select="header-title"/>
  				<xsl:apply-templates select="header-subtitle"/>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>
