<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ecospold="http://www.EcoInvent.org/EcoSpold01"
	>
	<xsl:output method="xml" indent="yes" omit-xml-declaration="no" media-type="text/xml" />
	<xsl:include href="EcoSpold01toEcoSpold02_administrativeInformation.xsl"/>
	<xsl:include href="EcoSpold01toEcoSpold02_activities.xsl"/>
	<xsl:include href="EcoSpold01toEcoSpold02_flowdata.xsl"/>
	<xsl:include href="EcoSpold01toEcoSpold02_modellingandvalidation.xsl"/>
	<xsl:template match="/">
		<ecoSpold xsi:schemaLocation="http://www.EcoInvent.org/EcoSpold02 ../Schemas/EcoSpold02.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.EcoInvent.org/EcoSpold02">				
			<xsl:for-each select="ecospold:ecoSpold/ecospold:dataset">
					<xsl:apply-templates />						
				</xsl:for-each>
		</ecoSpold>
	</xsl:template>
	
	<xsl:template match="ecospold:metaInformation">
		<metaInformation>
		<xsl:apply-templates />	
		</metaInformation>
	</xsl:template>
	
</xsl:stylesheet>

