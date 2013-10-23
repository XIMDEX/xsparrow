<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template name="small" match="small">
		<small uid="{@uid}">
			<xsl:apply-templates/>
		</small>
	</xsl:template>
</xsl:stylesheet>