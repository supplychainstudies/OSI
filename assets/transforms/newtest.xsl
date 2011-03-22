<xsl:transform version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="xml" indent="yes"/>
	
	<xsl:template match="/">
	  <ilcd>
		Its
		<xsl:foreach select=".">
			<xsl:value-of select="./" /> 
		</xsl:foreach>
	  <xsl:apply-templates select="dataset" />
	  </ilcd>
	</xsl:template>
	<!-- geography -->
	
	<xsl:template match="dataset">
		peanut 
		<xsl:apply-templates/>
	</xsl:template>
	
	<xsl:template match="metaInformation">
		butter
		<xsl:apply-templates/>
	</xsl:template>
	
	<xsl:template match="processInformation">
		jelly
		<xsl:apply-templates/>
	</xsl:template>
	
	<xsl:template match="geography">
		time
		<geography>
			<locationOfOperationSupplyOrProduction>
				<xsl:if test="latitudeAndLongitude">
					<xsl:attribute name="latitudeAndLongitude">
						<xsl:value-of select="./latitudeAndLongitude" /> 
					</xsl:attribute>
				</xsl:if>
				<xsl:if test="location">
					<xsl:attribute name="location">
						<xsl:value-of select="./location" /> 
					</xsl:attribute>
				</xsl:if>

			<xsl:foreach select="description">
				<descriptionOfRestrictions> 		
					<xsl:attribute name="xml:lang">
						<xsl:value-of select="./langCode" /> 
					</xsl:attribute>
					<xsl:value-of select="./value" />
				</descriptionOfRestrictions>
			</xsl:foreach>				
			</locationOfOperationSupplyOrProduction>
			<!--<xsl:foreach select="./subLocations">
				<subLocationOfOperationSupplyOrProduction 
					<xsl:if test="./latitudeAndLongitude">
						latitudeAndLongitude="<xsl:value-of select="./latitudeAndLongitude" />" 
					</xsl:if>
					<xsl:if test="./subLocation">
						subLocation="<xsl:value-of select="./subLocation" />"
					</xsl:if>						
					>
					<xsl:foreach select="./description">
						<descriptionOfRestrictions xml:lang="<xsl:value-of select="./langCode" />"><xsl:value-of select="./value" /> </descriptionOfRestrictions>
					</xsl:foreach>
				</subLocationOfOperationSupplyOrProduction> 
			</xsl:foreach>-->
		</geography>
	</xsl:template>

</xsl:transform>