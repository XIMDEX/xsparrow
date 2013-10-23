<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="main-column" match="main-column">
  	<xsl:variable name="column-width" select="12"/>
  	aa<div class="col-md-{$column-width}">
  		<div class="row">
  			<div class="col-md-12" uid="{@uid}">
  				<p>
  					A sample paragraph using Bootstrap theme.
  				</p>
  			</div>
  		</div>
  	</div>
  </xsl:template>
</xsl:stylesheet>