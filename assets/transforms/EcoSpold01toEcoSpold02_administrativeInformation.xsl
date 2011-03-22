<xsl:stylesheet
	version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ecospold="http://www.EcoInvent.org/EcoSpold01"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<xsl:output method="xml" indent="yes" omit-xml-declaration="no"/>

<xsl:template match="ecospold:administrativeInformation">		
		<administrativeInformation>
			<xsl:if test="ecospold:dataEntryBy">		
    			<dataEntryBy>
					<xsl:if test="ecospold:person/@number" namespace="dataEntryBy">
						<xsl:attribute name="personContextId">
							<xsl:value-of select="ecospold:person/@number" /> 
						</xsl:attribute>
					</xsl:if>

					<xsl:if test="ecospold:person/@email" namespace="dataEntryBy">
						<xsl:attribute name="personEmail">
							<xsl:value-of select="ecospold:person/@email" /> 
						</xsl:attribute>
					</xsl:if>					

					<xsl:if test="ecospold:person/@number" namespace="dataEntryBy">
						<xsl:attribute name="personId">
							<xsl:value-of select="ecospold:person/@number" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:person/@name" namespace="dataEntryBy">
						<xsl:attribute name="personName">
							<xsl:value-of select="ecospold:person/@name" /> 
						</xsl:attribute>
					</xsl:if>
				</dataEntryBy>
			</xsl:if>
			
			<xsl:if test="ecospold:dataGeneratorAndPublication">
    			<dataGeneratorAndPublication>
					<xsl:if test="ecospold:dataGeneratorAndPublication/@accessRestrictedTo">
						<xsl:attribute name="accessRestrictedTo">
							<xsl:value-of select="ecospold:dataGeneratorAndPublication/@accessRestrictedTo" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:dataGeneratorAndPublication/@companyCode">
						<xsl:attribute name="companyCode">
							<xsl:value-of select="ecospold:dataGeneratorAndPublication/@companyCode" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:dataGeneratorAndPublication/@dataPublishedIn">
						<xsl:attribute name="companyCode">
							<xsl:value-of select="ecospold:dataGeneratorAndPublication/@dataPublishedIn" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:dataGeneratorAndPublication/@copyright">
						<xsl:attribute name="copyright">
							<xsl:value-of select="ecospold:dataGeneratorAndPublication/@copyright" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:dataGeneratorAndPublication/@pageNumbers">
						<xsl:attribute name="pageNumbers">
							<xsl:value-of select="ecospold:dataGeneratorAndPublication/@pageNumbers" /> 
						</xsl:attribute>
					</xsl:if>												
				</dataGeneratorAndPublication>	
			</xsl:if>
			
			<xsl:if test="ecospold:fileAttributes">
    			<fileAttributes>							
					<xsl:attribute name="fileGenerator">
						OSI XSL Converter - Adapted from Open LCA
					</xsl:attribute>
					
					<xsl:if test="ecospold:fileAttributes/@contextId">
						<xsl:attribute name="contextId">
							<xsl:value-of select="ecospold:fileAttributes/@contextId" /> 
						</xsl:attribute>
					</xsl:if>					

					<xsl:if test="ecospold:fileAttributes/@creationTimestamp">
						<xsl:attribute name="creationTimestamp">
							<xsl:value-of select="ecospold:fileAttributes/@creationTimestamp" /> 
						</xsl:attribute>
					</xsl:if>					

					<xsl:if test="ecospold:fileAttributes/@defaultLanguage">
						<xsl:attribute name="defaultLanguage">
							<xsl:value-of select="ecospold:fileAttributes/@defaultLanguage" /> 
						</xsl:attribute>
					</xsl:if>					

					<xsl:if test="ecospold:fileAttributes/@fileTimestamp">
						<xsl:attribute name="fileTimestamp">
							<xsl:value-of select="ecospold:fileAttributes/@fileTimestamp" /> 
						</xsl:attribute>
					</xsl:if>					

					<xsl:if test="ecospold:fileAttributes/@internalSchemaVersion">
						<xsl:attribute name="internalSchemaVersion">
							<xsl:value-of select="ecospold:fileAttributes/@internalSchemaVersion" /> 
						</xsl:attribute>
					</xsl:if>					

					<xsl:if test="ecospold:fileAttributes/@lastEditTimestamp">
						<xsl:attribute name="lastEditTimestamp">
							<xsl:value-of select="ecospold:fileAttributes/@lastEditTimestamp" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:fileAttributes/@majorRelease">
						<xsl:attribute name="majorRelease">
							<xsl:value-of select="ecospold:fileAttributes/@majorRelease" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:fileAttributes/@majorRevision">
						<xsl:attribute name="majorRevision">
							<xsl:value-of select="ecospold:fileAttributes/@majorRevision" /> 
						</xsl:attribute>
					</xsl:if>
					
					<xsl:if test="ecospold:fileAttributes/@minorRelease">
						<xsl:attribute name="minorRelease">
							<xsl:value-of select="ecospold:fileAttributes/@minorRelease" /> 
						</xsl:attribute>
					</xsl:if>
										
					<xsl:if test="ecospold:fileAttributes/@minorRevision">
						<xsl:attribute name="minorRevision">
							<xsl:value-of select="minorRevision" /> 
						</xsl:attribute>
					</xsl:if>					
										
					<xsl:foreach select="ecospold:fileAttributes/contextNames">
						<contextName>
								<xsl:attribute name="xml:lang">
									<xsl:value-of select="langCode" /> 
								</xsl:attribute>					
							<xsl:value-of select="value" />						
						</contextName>
					</xsl:foreach>	
				</fileAttributes>
			</xsl:if>
		</administrativeInformation>
	</xsl:template>
</xsl:stylesheet>