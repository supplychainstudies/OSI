<xsl:transform version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:owl="http://www.w3.org/2002/07/owl#"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
	>
	<xsl:output method="html" indent="yes"/>
	<xsl:template match="owl:Ontology">
				<html>
					<body>
						<h2>Bubble</h2>
							<form>
				<xsl:for-each select="owl:Class">
					<br/><b><xsl:value-of select="./rdfs:label"/></b><br/>	
								
				</xsl:for-each>
			
				</form>
			</body>
		</html>
	</xsl:template>
</xsl:transform>