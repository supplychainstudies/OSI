<xsl:stylesheet
	version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ecospold="http://www.EcoInvent.org/EcoSpold01"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<xsl:output method="xml" indent="yes" omit-xml-declaration="no"/>

	<xsl:template match="ecospold:processInformation">
		<activityDataset>	
		<activityDescription>		
			<activity>
				<xsl:attribute name="id">UUID</xsl:attribute>				
				<xsl:attribute name="activityNameId">UUID</xsl:attribute>	
			<xsl:if test="ecospold:parentActivityId">
				<xsl:attribute name="parentActivityId">
					<xsl:value-of select="ecospold:parentActivityId" /> 
				</xsl:attribute>
			</xsl:if>
				<xsl:attribute name="inheritanceDepth">
					<xsl:value-of select="ecospold:inheritanceDepth" /> 
				</xsl:attribute>
				<xsl:attribute name="type">
					<xsl:value-of select="type" /> 
				</xsl:attribute>			
				<xsl:attribute name="specialActivityType">
					<xsl:value-of select="specialActivityType" /> 
				</xsl:attribute>
				<xsl:attribute name="energyValues">
					<xsl:value-of select="activityNameId" /> 
				</xsl:attribute>
				<xsl:attribute name="energyValues">
					<xsl:value-of select="activityNameId" /> 
				</xsl:attribute>
				<xsl:attribute name="masterAllocationPropertyId">
					<xsl:value-of select="masterAllocationPropertyId" /> 
				</xsl:attribute>				
				<xsl:attribute name="datasetIcon">
					<xsl:value-of select="datasetIcon" /> 
				</xsl:attribute>
			
				<xsl:foreach match="name">                
					<activityName>
						<xsl:attribute name="xml:lang">
							<xsl:value-of select="langCode" /> 
						</xsl:attribute>					
					<xsl:value-of select="value" /> 
					</activityName>
				</xsl:foreach>

				<xsl:foreach match="synonyms">                
					<synonym>
						<xsl:attribute name="xml:lang">
							<xsl:value-of select="langCode" /> 
						</xsl:attribute>					
					<xsl:value-of select="value" /> 
					</synonym>
				</xsl:foreach>				

				<xsl:foreach match="includedActivitiesStart">                
					<includedActivitiesStart>
						<xsl:attribute name="xml:lang">
							<xsl:value-of select="langCode" /> 
						</xsl:attribute>					
					<xsl:value-of select="value" /> 
					</includedActivitiesStart>
				</xsl:foreach>
			
				<xsl:foreach match="includedActivitiesEnd">                
					<includedActivitiesEnd>
						<xsl:attribute name="xml:lang">
							<xsl:value-of select="langCode" /> 
						</xsl:attribute>					
					<xsl:value-of select="value" /> 
					</includedActivitiesEnd>
				</xsl:foreach>

				<xsl:foreach match="allocationComment">                
					<allocationComment>
						<xsl:attribute name="xml:lang">
							<xsl:value-of select="langCode" /> 
						</xsl:attribute>					
					<xsl:value-of select="value" /> 
					</allocationComment>
				</xsl:foreach>				
			</activity>

				<xsl:if test="ecospold:geography">
					<geography> 
						<xsl:attribute name="geographyId">
							UUID
						</xsl:attribute>
					 	<xsl:if test="ecospold:geography/@location">         
							<shortname xml:lang=""><xsl:value-of select="ecospold:geography/@location" /></shortname>
						</xsl:if>
						<xsl:if test="ecospold:geography/@text">
							<comment><xsl:value-of select="ecospold:geography/@text" /></comment>
						</xsl:if>
					</geography>
				</xsl:if>
		
				<xsl:if test="ecospold:technology">
					<technology>
						<xsl:attribute name="technologyLevel">3</xsl:attribute>
						<xsl:if test="ecospold:technology/@text">
							<comment>
								<xsl:value-of select="ecospold:technology/@text" />
		                    </comment>
						</xsl:if>
					</technology>	
				</xsl:if>
		
				<xsl:if test="ecospold:timePeriod">
					<timePeriod>
						<xsl:if test="ecospold:timePeriod/@dataValidForEntirePeriod">
							<xsl:attribute name="isDataValidForEntirePeriod">
								<xsl:value-of select="timePeriod/@dataValidForEntirePeriod" /> 
							</xsl:attribute>
						</xsl:if>				
						<xsl:if test="ecospold:timePeriod/ecospold:startYear">
							<xsl:attribute name="startDate">
								<xsl:value-of select="ecospold:timePeriod/ecospold:startYear" /> 
							</xsl:attribute>
						</xsl:if>
						<xsl:if test="ecospold:timePeriod/ecospold:endYear">
							<xsl:attribute name="endDate">
								<xsl:value-of select="ecospold:timePeriod/ecospold:endYear" /> 
							</xsl:attribute>
						</xsl:if>
						<xsl:if test="ecospold:timePeriod/@text">
							<comment>
								<xsl:value-of select="ecospold:timePeriod/@text" /> 
		                    </comment>
						</xsl:if>
					</timePeriod>
				</xsl:if>
	    	</activityDescription>
		</activityDataset>
	</xsl:template>
</xsl:stylesheet>
