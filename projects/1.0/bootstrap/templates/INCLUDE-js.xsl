<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template name="INCLUDE-js" match="INCLUDE-js">
  	<script type="text/javascript" src="@@@RMximdex.dotdot(common/js/jquery-1.10.2.min.js)@@@"/>
    <script type="text/javascript" src="@@@RMximdex.dotdot(common/bootstrap/js/bootstrap.min.js)@@@"/>
  </xsl:template>
</xsl:stylesheet>