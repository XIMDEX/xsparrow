<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="abbr" match="abbr">
  	<abbr uid="{@uid}" class="{@type}" title="{@title}">
  		<xsl:apply-templates/>
  	</abbr>
  </xsl:template>
</xsl:stylesheet>