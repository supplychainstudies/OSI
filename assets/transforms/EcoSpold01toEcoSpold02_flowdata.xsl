<xsl:stylesheet
	version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ecospold="http://www.EcoInvent.org/EcoSpold01"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<xsl:output method="xml" indent="yes" omit-xml-declaration="no"/>

<xsl:template match="ecospold:flowData">
	<flowData>
			<xsl:for-each select="ecospold:exchange">
				<xsl:choose>
					<xsl:when test="ecospold:inputGroup=4 or ecospold:outputGroup=4">	
						<elementaryFlow>
							<xsl:attribute name="elementaryExchangeId">UUID</xsl:attribute>
							<xsl:if test="@CASNumber">
								<xsl:attribute name="casNumber">
									<xsl:value-of select="@CASNumber" />
								</xsl:attribute>						 
							</xsl:if>
							<xsl:if test="@formula">
								<xsl:attribute name="formula">
									<xsl:value-of select="@formula" />
								</xsl:attribute>						 
							</xsl:if>
							<xsl:call-template name = "common_exchange"></xsl:call-template>
						</elementaryFlow>						
					</xsl:when>
					<xsl:otherwise>
						<intermediateExchange>
							<xsl:attribute name="intermediateExchangeId">UUID</xsl:attribute>	
							<xsl:call-template name ="common_exchange"></xsl:call-template>
						</intermediateExchange>
					</xsl:otherwise>
				</xsl:choose>	
			</xsl:for-each>
		</flowData>
	</xsl:template>
	
				
	<xsl:template name="common_exchange">
					<xsl:attribute name="id">UUID</xsl:attribute>
					
					<!--><xsl:if test="@activityLinkId">
						activityLinkId="$exchange.activityLinkId" 
					</xsl:if>-->
					<xsl:if test="@meanValue">
						<xsl:attribute name="amount">
							<xsl:value-of select="@meanValue" />
						</xsl:attribute>
					</xsl:if>

					<xsl:attribute name="isCalculatedAmount">false</xsl:attribute>
					
					<!--<xsl:if test="mathematicalRelation">
						mathematicalRelation="$exchange.mathematicalRelation" 
					</xsl:if>
					<xsl:if test="pageNumbers">
						pageNumbers="$exchange.pageNumbers" 
					</xsl:if>
					<xsl:if test="productionVolumeAmount">
						productionVolumeAmount="$exchange.productionVolumeAmount" 
					</xsl:if>
					<xsl:if test="sourceId">
						sourceId="$exchange.sourceId" 
					</xsl:if>
					<xsl:if test="specificAllocationPropertyId">
						specificAllocationPropertyId="$exchange.specificAllocationPropertyId" 
					</xsl:if>
		    		<xsl:if test="unitId">
						unitId="$exchange.unitId" 
					</xsl:if>					
					<xsl:if test="variableName">
						variableName="$exchange.variableName" 
					</xsl:if>-->
		
					<xsl:if test="@name">
						<name><xsl:value-of select="@name" /></name> 
					</xsl:if>		               
		        	
					<xsl:if test="@unit">
						<unitName><xsl:value-of select="@unit" /></unitName> 
					</xsl:if>			
			
					<xsl:if test="@generalComment">
						<comment><xsl:value-of select="@generalComment" /></comment> 
					</xsl:if>

					<xsl:if test="@uncertaintyType">
						 <!--#set($uncertainty =  $exchange.uncertainty)
		        		#parse("ES2Uncertainty.vtl")-->
					</xsl:if>        	
        
		            <!--#foreach ($it in $exchange.synonym)
		              <synonym xml:lang="$it.langCode">$it.value</synonym>
		            </xsl:foreach>
			
					#foreach ($it in $exchange.tags)
						<tag>$it</tag>
					</xsl:foreach>
			
					#foreach ($it in $exchange.productionVolumeComment)
						<productionVolumeComment xml:lang="$it.langCode">$it.value</productionVolumeComment>
					</xsl:foreach>
			
					## product classifications
					<xsl:foreach select="classification in $exchange.productClassifications)
					<classification
				
						<xsl:if test="$classification.classificationId) 
							classificationId="$classification.classificationId" 
						</xsl:if>
						>
				
						#foreach ($it in $classification.classificationSystem)
		                  <classificationSystem xml:lang="$it.langCode">$it.value</classificationSystem>
						</xsl:foreach>
				
		                #foreach ($it in $classification.classificationValue)
		                  <classificationValue xml:lang="$it.langCode">$it.value</classificationValue>
		                </xsl:foreach>			
				
					</classification>
					</xsl:if> ##foreach classifiactions-->
			
					<xsl:if test="ecospold:inputGroup">
						<inputGroup><xsl:value-of select="ecospold:inputGroup" /></inputGroup> 
					</xsl:if>

					<xsl:if test="ecospold:outputGroup">
						<outputGroup><xsl:value-of select="ecospold:outputGroup" /></outputGroup> 
					</xsl:if>
	
			<!--<xsl:foreach select="parameter in $dataset.parameter)
			<parameter
				<xsl:if test="$parameter.amount)
					amount="$parameter.amount" 
				</xsl:if>
				<xsl:if test="$parameter.id)
					id="$parameter.id" 
				</xsl:if>
				<xsl:if test="$parameter.mathematicalRelation)
					mathematicalRelation="$parameter.mathematicalRelation" 
				</xsl:if>
				<xsl:if test="$parameter.variableName)
					variableName="$parameter.variableName" 
				</xsl:if>
				>
				<xsl:foreach select="it in $parameter.name)                
					<name xml:lang="$it.langCode">$it.value</name>
				</xsl:foreach>
				<xsl:if test="$parameter.uncertainty)
					#set($uncertainty =  $parameter.uncertainty)
					#parse("ES2Uncertainty.vtl")
				</xsl:if>
				<xsl:foreach select="it in $parameter.comment)                
					<comment xml:lang="$it.langCode">$it.value</comment>
				</xsl:foreach>
			</parameter>
			</xsl:foreach>-->
	</xsl:template>
</xsl:stylesheet>