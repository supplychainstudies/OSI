<xsl:transform version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:lca="http://www.openLCA.org/mappingDoc" 
	xmlns:xs="http://www.w3.org/2001/XMLSchema">
	<xsl:output method="xml" indent="yes"/>
	
	
	<xsl:template match="/">
	    <xsl:element name="xs:schema">
	      <xsl:apply-templates select="xs:schema/xs:import/@schemaLocation"/>
	    </xsl:element>
		<xsl:apply-templates />
	  </xsl:template>
	  <xsl:template match="@schemaLocation">    
	    <xsl:copy-of select="document(.)"/>
	 </xsl:template>
	
	<xsl:template match="xs:complexType">
				<br/><b><xsl:value-of select="./@name"/></b><br/>										
	</xsl:template>	
	
</xsl:transform>

