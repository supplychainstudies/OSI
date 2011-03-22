<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:lca="http://www.openLCA.org/mappingDoc">
	<xsl:output method="xml" indent="yes"/>
	<xsl:template match="lca:parentElement">
				<xsl:for-each select=".">
					<br/><b><xsl:value-of select="./@name"/></b><br/>
					<xsl:for-each select="./lca:field">
						<xsl:value-of select="./@name"/><br/>
					</xsl:for-each>						
				</xsl:for-each>
	</xsl:template>
</xsl:transform>