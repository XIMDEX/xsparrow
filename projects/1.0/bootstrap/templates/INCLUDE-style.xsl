<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template name="INCLUDE-style" match="INCLUDE-style">


	<!-- Header -->

	<xsl:variable name="headerBackgroundColor">
		<xsl:choose>
			<xsl:when test="not (//xsparrow-theme/body/header/@background-color) or 
				//xsparrow-theme/body/header/@background-color = ''">
				transparent
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="//xsparrow-theme/body/header/@background-color"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>

	<xsl:variable name="headerFontColor">
		<xsl:choose>
			<xsl:when test="not (//xsparrow-theme/body/header/@font-color) or 
				//xsparrow-theme/body/header/@font-color = ''">
				transparent
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="//xsparrow-theme/body/header/@font-color"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>


	<xsl:variable name="headerFontSize">
		<xsl:choose>
			<xsl:when test="not (//xsparrow-theme/body/header/@font-size) or 
				//xsparrow-theme/body/header/@font-size = ''">
				auto
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="concat(//xsparrow-theme/body/header/@font-size,'%')"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>

	<xsl:variable name="headerTextAlign">
		<xsl:choose>
			<xsl:when test="not (//xsparrow-theme/body/header/@align) or 
				//xsparrow-theme/body/header/@align = ''">
				auto
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="//xsparrow-theme/body/header/@align"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>			


		<!-- Container -->

		<xsl:variable name="bodyBackgroundColor">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/@background-color) or 
					//xsparrow-theme/body/@background-color = ''">
					auto
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//xsparrow-theme/body/@background-color"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="bodyBackgroundImage">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/@background-image) or 
					//xsparrow-theme/body/@background-image = ''">
					<xsl:text>none</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					url(<xsl:value-of select="concat('@@@RMximdex.dotdot(', //xsparrow-theme/body/@background-image,')@@@')"/>)
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>	

		<xsl:variable name="bodyBackgroundPosition">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/@background-position) or //xsparrow-theme/body/@background-position = ''">
					<xsl:text>none</xsl:text>
				</xsl:when>
				<xsl:otherwise>                         					
					<xsl:value-of select="//xsparrow-theme/body/@background-position"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="bodyBackgroundRepeat">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/@background-position) or //xsparrow-theme/body/@background-repeat = ''">
					<xsl:text>no-repeat</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//xsparrow-theme/body/@background-repeat"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="bodyFontSize">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/@font-size) or //xsparrow-theme/body/@font-size = ''">
					auto
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="concat(//xsparrow-theme/body/@font-size,'%')"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="bodyFontColor">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/@font-color) or //xsparrow-theme/body/@font-color = ''">
					auto
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//xsparrow-theme/body/@font-color"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="contentAlign">
			<xsl:choose>
				<xsl:when test="not (//xsparrow-theme/body/content/@align) or //xsparrow-theme/body/content/@align">
					inherit
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//xsparrow-theme/body/content/@align"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>


		<!-- End of container -->


	 
		<xsl:variable name="MenuBackgroundColor">
			<xsl:choose>
				<xsl:when test="not (//menu[@background-color]/@background-color) or //menu[@background-color]/@background-color = ''">
					transparent
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//menu[@background-color]/@background-color"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="MenuFontColor">
			<xsl:choose>
				<xsl:when test="not (//menu[@font-color]/@font-color) or //menu[@font-color]/@font-color = ''">
					transparent
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//menu[@font-color]/@font-color"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="MenuHoverFontColor">
			<xsl:choose>
				<xsl:when test="not (//menu[@font-color-hover]/@font-color-hover) or //menu[@font-color-hover]/@font-color-hover = ''">
					transparent
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//menu[@font-color-hover]/@font-color-hover"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>



		<!--Aqui comienza el css-->          
		<style type="text/css">
			

			body{
				background-url: <xsl:value-of select="$bodyBackgroundImage"/>;
				background-position: <xsl:value-of select="$bodyBackgroundPosition"/>;
				background-repeat: <xsl:value-of select="$bodyBackgroundRepeat"/>;			
				background-color: <xsl:value-of select="$bodyBackgroundColor"/>;
				font-size: <xsl:value-of select="$bodyFontSize"/>;
				color: <xsl:value-of select="$bodyFontColor"/>;	
			}

			div.header{
			background-color:<xsl:value-of select="$headerBackgroundColor"/>;
			color:<xsl:value-of select="$headerFontColor"/>;
			text-align:<xsl:value-of select="$headerTextAlign"/>
			}
			div.header h1{                      				
			font-size:<xsl:value-of select="$headerFontSize"/>;

			}
			
			div.container div.content p{

				text-align:<xsl:value-of select="$contentAlign"/>;
			
			}


			.navbar-inner{
			background-color:<xsl:value-of select="$MenuBackgroundColor"/>;
			color: 	<xsl:value-of select="$MenuFontColor"/>;
			background-image: none;                
			}


			.navbar .nav li a.navbar-link{                    	  
			color: 	 <xsl:value-of select="$MenuFontColor"/>;                          
			}

			.navbar .nav li a.navbar-link:hover{                    	   
			color: 	<xsl:value-of select="$MenuHoverFontColor"/>;                           
			}		


		</style>
		<!-- Fin del css -->
	</xsl:template>
</xsl:stylesheet>
