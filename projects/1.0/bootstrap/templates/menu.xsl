<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
 	<xsl:template name="menu" match="menu">
    <header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
     <div class="container">     
       <nav class="navbar-collapse bs-navbar-collapse" role="navigation">
         <ul class="nav navbar-nav" uid="{@uid}">
           <xsl:apply-templates/>
         </ul>
       </nav>
     </div>
    </header>
 	</xsl:template>
</xsl:stylesheet>
