<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ecospold="http://www.EcoInvent.org/EcoSpold01"
	>
	<xsl:output method="xml" indent="yes" omit-xml-declaration="no" media-type="text/xml" />
	
	<xsl:template match="ecospold:modellingAndValidation">		
		<modellingAndValidation>
			<xsl:if test="ecospold:representativeness">
				<representativeness>			
					<xsl:attribute name="systemModelId">8B738EA0-F89E-4627-8679-433616064E82</xsl:attribute>
					<systemModelName>undefined</systemModelName>
					<xsl:if test="ecospold:representativeness/@percent">
						<xsl:attribute name="percent">
							<xsl:value-of select="ecospold:representativeness/@percent" />
						</xsl:attribute>
					</xsl:if>
					<xsl:if test="ecospold:representativeness/@samplingProcedure">            
						<samplingProcedure><xsl:value-of select="ecospold:representativeness/@samplingProcedure" /></samplingProcedure>
					</xsl:if>
			
					<xsl:if test="ecospold:representativeness/@extrapolations">               
						<extrapolations><xsl:value-of select="ecospold:representativeness/@extrapolations" /></extrapolations>
					</xsl:if>
				</representativeness>
			</xsl:if>

			<xsl:for-each select="ecospold:validation">
				<review>
					<xsl:if test="ecospold:proofReadingValidator">
						<xsl:variable name="reviewer_ref" select="proofReadingValidator"></xsl:variable>
						<xsl:variable name="reviewer" select="./ecospold:metaInformation/ecospold:administrativeInformation/ecospold:person[@id='{$reviewer_ref}']"></xsl:variable>
						<xsl:if test="$reviewer">
							<xsl:attribute name="reviewerEmail">
								<xsl:value-of select="$reviewer" />
							</xsl:attribute>
							<xsl:attribute name="reviewerName">
								<xsl:value-of select="$reviewer" />
							</xsl:attribute>
							<xsl:attribute name="reviewerId">
								<xsl:value-of select="$reviewer" />
							</xsl:attribute>							
						</xsl:if>
					</xsl:if>
					<xsl:if test="./dataSetInformation/@timestamp">
						<xsl:attribute name="reviewDate">
							<xsl:value-of select="./dataSetInformation/@timestamp" />
						</xsl:attribute>
					</xsl:if>
					<xsl:if test="ecospold:proofReadingDetails">
		            	<details>
							<xsl:value-of select="ecospold:proofReadingDetails" />
			            </details>
					</xsl:if>
					<!--<xsl:if test="@otherDetails">
			            <otherDetails
			                xml:lang="en"
			                xsi:type="TString32000">
							<xsl:value-of select="ecospold:otherDetails" />
						</otherDetails>
					</xsl:if>-->
		        </review>
				</xsl:for-each>

		</modellingAndValidation>
	</xsl:template>
</xsl:stylesheet>