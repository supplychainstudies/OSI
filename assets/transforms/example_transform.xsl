<xsl:transform version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:owl="http://www.w3.org/2002/07/owl#"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
	xmlns:ecospold="http://www.EcoInvent.org/EcoSpold01"
	>
	<xsl:output method="html" indent="yes"/>
	<xsl:template match="ecospold:ecoSpold">
				<html>
					<body>
						<h2>New Format</h2>
				<xsl:for-each select="*">
					<br/><b><xsl:value-of select="*"/></b><br/>	
								
				</xsl:for-each>

			</body>
		</html>
	</xsl:template>
</xsl:transform>