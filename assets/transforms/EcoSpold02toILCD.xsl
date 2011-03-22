<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet version="1.0" href="../../stylesheets/process2html.xsl" type="text/xsl"?>
<processDataSet 
	xmlns="http://lca.jrc.it/ILCD/Process" 
	xmlns:common="http://lca.jrc.it/ILCD/Common"
	metaDataOnly="$process.metaDataOnly"	
	locations="../ILCDLocations.xml"
	version="1.1"	
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://lca.jrc.it/ILCD/Process ../../schemas/ILCD_ProcessDataSet.xsd">
	
	<processInformation>
		
		## process information		
		#set($dataSetInfo = $process.description)
		<dataSetInformation>
			<common:UUID>$dataSetInfo.uuid</common:UUID>
			<name>
				#foreach($name in $dataSetInfo.name)
					<baseName xml:lang="$name.langCode">$name.value</baseName>
				#end
				#foreach($tsr in $dataSetInfo.treatmentStandardsRoutes)
					<treatmentStandardsRoutes xml:lang="$tsr.langCode">$tsr.value</treatmentStandardsRoutes>
				#end
				#foreach($mix in $dataSetInfo.mixAndLocationTypes)
					<mixAndLocationTypes xml:lang="$mix.langCode">$mix.value</mixAndLocationTypes>
				#end
				#foreach($fu in $dataSetInfo.functionalUnitFlowProperties)
					<functionalUnitFlowProperties xml:lang="$fu.langCode">$fu.value</functionalUnitFlowProperties>
				#end
			</name>
			#foreach($synonym in $dataSetInfo.synonyms)
				<common:synonyms xml:lang="$synonym.langCode">$synonym.value</common:synonyms>
			#end
			#if($dataSetInfo.complementingProcesses.size() > 0)
				<complementingProcesses>
					#set($refElem = "referenceToComplementingProcess")
					#foreach($refVal in $dataSetInfo.complementingProcesses)
						#parse("ILCDDataSetRef.vtl")
					#end
				</complementingProcesses>
			#end
			<classificationInformation>
				#foreach($classification in $dataSetInfo.classifications)
					<common:classification
						#if($classification.fileURI)
							classes="$classification.fileURI"
						#end
						#if($classification.name)
							name="$classification.name"
						#end
					>
					#foreach($class in $classification.classes)
						<common:class 
							#if($class.id)
							classId="$class.id" 
							#end
							#if($class.level)
							level="$class.level"
							#end
							>$class.name</common:class>
					#end					
					</common:classification>
				#end
            </classificationInformation>
			#foreach($comment in $dataSetInfo.comment)
			<common:generalComment xml:lang="$comment.langCode">$comment.value</common:generalComment>
			#end
			#set($refElem = "referenceToExternalDocumentation")
			#foreach($refVal in $dataSetInfo.externalDocumentations)
				#parse("ILCDDataSetRef.vtl")
			#end
		</dataSetInformation>
		
		## quantitative reference
		#if($process.quantitativeReference)
			#set($qRef = $process.quantitativeReference)
		<quantitativeReference 
				#if($qRef.type)
				type="$qRef.type"
				#end
				>
				#foreach($refFlow in $qRef.referenceFlows)
					<referenceToReferenceFlow>$refFlow</referenceToReferenceFlow>
				#end
				#foreach($fu in $qRef.functionalUnits)
					<functionalUnitOrOther xml:lang="$fu.langCode">$fu.value</functionalUnitOrOther>
				#end
    	</quantitativeReference>
		#end
		
		## time
		#if($process.time)
			#set($time = $process.time)
			<time>
				#if($time.referenceYear)
					<common:referenceYear>$time.referenceYear</common:referenceYear>
				#end
				#if($time.validUntil)
					<common:dataSetValidUntil>$time.validUntil</common:dataSetValidUntil>
				#end
				#foreach($descr in $time.description)
				<common:timeRepresentativenessDescription xml:lang="$descr.langCode">$descr.value</common:timeRepresentativenessDescription>
				#end				
			</time>
		#end
		
		

		<!-- geography -->
		<xsl:template match="geography">
			<geography>
				<locationOfOperationSupplyOrProduction 
					<xsl:if test="latitudeAndLongitude">
						latitudeAndLongitude=<xsl:value-of select="." /> 
					</xsl:if>
					<xsl:if test="location">
						location=<xsl:value-of select="." /> 
					</xsl:if>
				>
				<xsl:foreach select="./description">
					<descriptionOfRestrictions xml:lang="<xsl:value-of select="./langCode" />"><xsl:value-of select="./value" /> </descriptionOfRestrictions>
				</xsl:foreach>				
				</locationOfOperationSupplyOrProduction>
				<xsl:foreach select="./subLocations">
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
				</xsl:foreach>
			</geography>
		</xsl:template>
		
		
		## technology
		#if($process.technology)
    		#set($technology = $process.technology)
    		<technology>
    			#foreach($description in $technology.description)
    				<technologyDescriptionAndIncludedProcesses xml:lang="$description.langCode">$description.value</technologyDescriptionAndIncludedProcesses>
    			#end
    			#set($refElem = "referenceToIncludedProcesses")
    			#foreach($refVal in $technology.includedProcesses)
    				#parse("ILCDDataSetRef.vtl")
    			#end
    			#foreach($applicability in $technology.applicability)
    				<technologicalApplicability xml:lang="$applicability.langCode">$applicability.value</technologicalApplicability>
    			#end
    			#if($technology.pictogramme)
    				#set($refVal = $technology.pictogramme)
    				#set($refElem = "referenceToTechnologyPictogramme")
    				#parse("ILCDDataSetRef.vtl")
    			#end
    			#set($refElem = "referenceToTechnologyFlowDiagrammOrPicture")
    			#foreach($refVal in $technology.flowDiagrammsAndPictures)
    				#parse("ILCDDataSetRef.vtl")
    			#end
            </technology>	
		#end
		
		## mathematical relations
		#if($process.mathSection)
			<mathematicalRelations>
				#foreach($descr in $process.mathSection.modelDescription)
					<modelDescription xml:lang="$descr.langCode">$descr.value</modelDescription>
				#end
				#foreach($param in $process.mathSection.parameters)
					<variableParameter
						#if($param.name)
							name = "$param.name"
						#end						
					>
					#if($param.formula)
						<formula>$param.formula</formula>
					#end
					#if($param.meanValue)
						<meanValue>$param.meanValue</meanValue>
					#end
					#if($param.minimumValue)
						<minimumValue>$param.minimumValue</minimumValue>
					#end
					#if($param.maximumValue)
						<maximumValue>$param.maximumValue</maximumValue>
					#end
					#if($param.uncertaintyType)
						<uncertaintyDistributionType>$param.uncertaintyType</uncertaintyDistributionType>
					#end
					#if($param.relStdDeviation95In)
						<relativeStandardDeviation95In>$param.relStdDeviation95In</relativeStandardDeviation95In>
					#end
					#foreach($comment in $param.comment)
                        <comment xml:lang="$comment.langCode">$comment.value</comment>
					#end
                    </variableParameter>
				#end
			</mathematicalRelations>
		#end
		
    </processInformation>
	
	<modellingAndValidation>
		
		## LCI method and allocation
		#if($process.method)
			#set($method = $process.method)
    		<LCIMethodAndAllocation>
				#if($method.dataSetType)
                    <typeOfDataSet>$method.dataSetType</typeOfDataSet>
				#end
				#if($method.lciMethodPrinciple)
                    <LCIMethodPrinciple>$method.lciMethodPrinciple</LCIMethodPrinciple>
				#end
				#foreach($dev in $method.deviationsFromLCIMethodPrinciple)
                    <deviationsFromLCIMethodPrinciple xml:lang="$dev.langCode">$dev.value</deviationsFromLCIMethodPrinciple>
				#end
				#foreach($it in $method.lciMethodApproaches)
                    <LCIMethodApproaches>$it</LCIMethodApproaches>
				#end
				#foreach($it in $method.deviationsFromLCIMethodApproaches)
                    <deviationsFromLCIMethodApproaches xml:lang="$it.langCode">$it.value</deviationsFromLCIMethodApproaches>
				#end
				#foreach($it in $method.modellingConstants)
                    <modellingConstants xml:lang="$it.langCode">$it.value</modellingConstants>
				#end
				#foreach($it in $method.deviationsFromModellingConstants)
                    <deviationsFromModellingConstants xml:lang="$it.langCode">$it.value</deviationsFromModellingConstants>
				#end
				#set($refElem = "referenceToLCAMethodDetails")
    			#foreach($refVal in $method.methodDetails)
    				#parse("ILCDDataSetRef.vtl")
    			#end
            </LCIMethodAndAllocation>
		#end
		
		## representativeness
		#if($process.representativeness)
			#set($repr = $process.representativeness)
			<dataSourcesTreatmentAndRepresentativeness>
				#foreach($it in $repr.cutOffPrinciples)
					<dataCutOffAndCompletenessPrinciples xml:lang="$it.langCode">$it.value</dataCutOffAndCompletenessPrinciples>
				#end
				#foreach($it in $repr.deviationsFromCutOffPrinciples)
					<deviationsFromCutOffAndCompletenessPrinciples xml:lang="$it.langCode">$it.value</deviationsFromCutOffAndCompletenessPrinciples>
				#end
				#foreach($it in $repr.dataSelectionPrinciples)
					<dataSelectionAndCombinationPrinciples xml:lang="$it.langCode">$it.value</dataSelectionAndCombinationPrinciples>
				#end
				#foreach($it in $repr.deviationsFromDataSelectionPrinciples)
					<deviationsFromSelectionAndCombinationPrinciples xml:lang="$it.langCode">$it.value</deviationsFromSelectionAndCombinationPrinciples>
				#end							
				#foreach($it in $repr.extrapolationPrinciples)
					<dataTreatmentAndExtrapolationsPrinciples xml:lang="$it.langCode">$it.value</dataTreatmentAndExtrapolationsPrinciples>
				#end
				#foreach($it in $repr.deviationsFromExtrapolationPrinciples)
					<deviationsFromTreatmentAndExtrapolationPrinciples xml:lang="$it.langCode">$it.value</deviationsFromTreatmentAndExtrapolationPrinciples>
				#end
				#set($refElem = "referenceToDataHandlingPrinciples")
				#foreach($refVal in $repr.dataHandlingPrinciples)
					#parse("ILCDDataSetRef.vtl")
				#end
				#set($refElem = "referenceToDataSource")
				#foreach($refVal in $repr.dataSources)
					#parse("ILCDDataSetRef.vtl")
				#end
				#if($repr.percent)
					<percentageSupplyOrProductionCovered>$repr.percent</percentageSupplyOrProductionCovered>
				#end
				#foreach($it in $repr.productionVolume)
					<annualSupplyOrProductionVolume xml:lang="$it.langCode">$it.value</annualSupplyOrProductionVolume>
				#end
				#foreach($it in $repr.samplingProcedure)
					<samplingProcedure xml:lang="$it.langCode">$it.value</samplingProcedure>
				#end
				#foreach($it in $repr.dataCollectionPeriod)
					<dataCollectionPeriod xml:lang="$it.langCode">$it.value</dataCollectionPeriod>
				#end
				#foreach($it in $repr.uncertaintyAdjustments)
					<uncertaintyAdjustments xml:lang="$it.langCode">$it.value</uncertaintyAdjustments>
				#end
				#foreach($it in $repr.useAdviceForDataSet)
					<useAdviceForDataSet xml:lang="$it.langCode">$it.value</useAdviceForDataSet>
				#end				
			</dataSourcesTreatmentAndRepresentativeness>
		#end
		
		#if($process.completeness)
			#set($completeness = $process.completeness)
			<completeness>
				#if($completeness.completenessProductModel)
                    <completenessProductModel>$completeness.completenessProductModel</completenessProductModel>
				#end
				#set($refElem = "referenceToSupportedImpactAssessmentMethods")
				#foreach($refVal in $completeness.supportedImpactAssessmentMethods)
					#parse("ILCDDataSetRef.vtl")
				#end
				#foreach($it in $completeness.elemFlowCompleteness)
					<completenessElementaryFlows 
						#if($it.type)
							type="$it.type"
						#end
						#if($it.value)				
							value="$it.value"
						#end
					/>
				#end
				#foreach($it in $completeness.completenessOtherProblemFields)
					<completenessOtherProblemField xml:lang="$it.langCode">$it.value</completenessOtherProblemField>
				#end
            </completeness>
		#end
		
		## reviews
		#if($process.reviews.size() > 0)
			<validation>
				#foreach($review in $process.reviews)
					<review
						#if($review.type)
							type="$review.type"
						#end>
						
						## review scopes
						#foreach($scope in $review.scopes)
							<common:scope
								#if($scope.name)
									name="$scope.name"
								#end>
								#foreach($method in $scope.methods)
									<common:method name="$method"/>
								#end
                            </common:scope>
						#end
						
						## review details
						#foreach($it in $review.details)
                             <common:reviewDetails xml:lang="$it.langCode">$it.value</common:reviewDetails>
						#end
						
						## references to reviewers
						#set($refElem = "common:referenceToNameOfReviewerAndInstitution")
        				#foreach($refVal in $review.reviewers)
        					#parse("ILCDDataSetRef.vtl")
        				#end
						
						## other review details
						#foreach($it in $review.otherDetails)
                             <common:otherReviewDetails xml:lang="$it.langCode">$it.value</common:otherReviewDetails>
						#end
						
						## reference to review report
						#if($review.reviewReport)
							#set($refElem = "common:referenceToCompleteReviewReport")
							#set($refVal = $review.reviewReport)
							#parse("ILCDDataSetRef.vtl")
						#end
						
                    </review>
				#end
            </validation>
		#end
		
    </modellingAndValidation>
	
	<administrativeInformation>
	
		## TODO: commissioner and goal
		
		## data generators
		#if($process.dataGenerators.size() > 0)
			<dataGenerator>
				## references to data generators
				#set($refElem = "common:referenceToPersonOrEntityGeneratingTheDataSet")
        		#foreach($refVal in $process.dataGenerators)
        			#parse("ILCDDataSetRef.vtl")
        		#end
            </dataGenerator>
		#end
		
	## data entry by	
	#if($process.entry)	
		#set ($entry = $process.entry)
		<dataEntryBy>
            #if ($entry.timeStamp)
				<common:timeStamp>$entry.timeStamp</common:timeStamp>
			#end
			
            #set($refElem = "common:referenceToDataSetFormat")
            #foreach ($refVal in $entry.dataFormatReferences)
				#parse("ILCDDataSetRef.vtl")
			#end
			
            #if ($entry.originalFormatReference)
				#set($refElem = "common:referenceToConvertedOriginalDataSetFrom")
				#set($refVal = $entry.originalFormatReference)
				#parse("ILCDDataSetRef.vtl")             
            #end
            
            #if ($entry.dataSetEntryReference)
				#set($refElem = "common:referenceToPersonOrEntityEnteringTheData")
				#set($refVal = $entry.dataSetEntryReference)
				#parse("ILCDDataSetRef.vtl")               
            #end
            
			#set($refElem = "common:referenceToDataSetUseApproval")
            #foreach ($refVal in $entry.useApprovalReferences)				
				#parse("ILCDDataSetRef.vtl")   
            #end
        </dataEntryBy>
	#end	
	
	
	## publication and ownership
	#if($process.publication)
		#set($publication = $process.publication)
		<publicationAndOwnership>
        
		#if ($publication.lastRevision)
          <common:dateOfLastRevision>$publication.lastRevision</common:dateOfLastRevision>
        #end
        
        #if ($publication.dataSetVersion)
          <common:dataSetVersion>$publication.dataSetVersion</common:dataSetVersion>
        #end
        
		#set($refElem = "common:referenceToPrecedingDataSetVersion")
        #foreach ($refVal in $publication.precedingDataSetReferences)
          #parse("ILCDDataSetRef.vtl")
		#end
		
        #if ($publication.permanentDataSetURI)
          <common:permanentDataSetURI>$publication.permanentDataSetURI</common:permanentDataSetURI>
        #end
        
        #if ($publication.workflowStatus)
          <common:workflowAndPublicationStatus>$publication.workflowStatus</common:workflowAndPublicationStatus>
        #end
        
        #if ($publication.republicationReference)
			#set($refElem = "common:referenceToUnchangedRepublication")
			#set($refVal = $publication.republicationReference)
			#parse("ILCDDataSetRef.vtl")          
        #end
        
        #if ($publication.registrationAuthorityReference)
            #set($refElem = "common:referenceToRegistrationAuthority")
			#set($refVal = $publication.registrationAuthorityReference)
			#parse("ILCDDataSetRef.vtl")   
        #end
        
        #if ($publication.registrationNumber)
          <common:registrationNumber>$publication.registrationNumber</common:registrationNumber>
        #end
        
        #if ($publication.ownershipReference)
          #set($refElem = "common:referenceToOwnershipOfDataSet")
		  #set($refVal = $publication.ownershipReference)
		  #parse("ILCDDataSetRef.vtl")          
        #end
        
        #if ($publication.copyright)
          <common:copyright>$publication.copyright</common:copyright>
		#else
		  <common:copyright>false</common:copyright>	
        #end
        
		#set($refElem = "common:referenceToEntitiesWithExclusiveAccess")
        #foreach ($refVal in $publication.exclusiveAccessReferences)
          #parse("ILCDDataSetRef.vtl")  
		#end
		
        #if ($publication.licenseType)
          <common:licenseType>$publication.licenseType</common:licenseType>
        #end
        
        #foreach ($it in $publication.accessRestrictions)
          <common:accessRestrictions xml:lang="$it.langCode">$it.value</common:accessRestrictions>
		#end
		
        </publicationAndOwnership>
	#end
	
    </administrativeInformation>
	
	<exchanges>
		
		#foreach($exchange in $process.exchanges)
			<exchange
				#if($exchange.id)
					dataSetInternalID="$exchange.id"
				#end
			>
			#if($exchange.flowDataSet)
				#set($refElem = "referenceToFlowDataSet")
				#set($refVal = $exchange.flowDataSet)
				#parse("ILCDDataSetRef.vtl")
			#end
			#if($exchange.location)
                <location>$exchange.location</location>
			#end
			#if($exchange.functionType)
                <functionType>$exchange.functionType</functionType>
			#end
			#if($exchange.direction)
                <exchangeDirection>$exchange.direction</exchangeDirection>
			#end
			#if($exchange.variableReference)
                <referenceToVariable>$exchange.variableReference</referenceToVariable>
			#end
			#if($exchange.meanAmount)
                <meanAmount>$exchange.meanAmount</meanAmount>
			#end
			#if($exchange.resultingAmount)
                <resultingAmount>$exchange.resultingAmount</resultingAmount>
			#end
			#if($exchange.minimumAmount)
                <minimumAmount>$exchange.minimumAmount</minimumAmount>
			#end
			#if($exchange.maximumAmount)
                <maximumAmount>$exchange.minimumAmount</maximumAmount>
			#end
			#if($exchange.uncertaintyDistribution)
                <uncertaintyDistributionType>$exchange.uncertaintyDistribution</uncertaintyDistributionType>
			#end
			#if($exchange.relStdDeviation95In)
                <relativeStandardDeviation95In>$exchange.relStdDeviation95In</relativeStandardDeviation95In>
			#end
			#if($exchange.allocationFactors.size() > 0)
				<allocations>
				#foreach($it in $exchange.allocationFactors)
					<allocation
						#if($it.fraction)
							allocatedFraction="$it.fraction"
						#end
						#if($it.coProductId)
							internalReferenceToCoProduct="$it.coProductId"
						#end
					/>
				#end
                </allocations>				
			#end
			#if($exchange.dataSourceType)
                <dataSourceType>$exchange.dataSourceType</dataSourceType>
			#end
			#if($exchange.dataDerivationType)
                <dataDerivationTypeStatus>$exchange.dataDerivationType</dataDerivationTypeStatus>
			#end
			#if($exchange.dataSources.size() > 0)
				<referencesToDataSource>
					#set($refElem = "referenceToDataSource")
					#foreach($refVal in $exchange.dataSources)
						#parse("ILCDDataSetRef.vtl")
					#end
                </referencesToDataSource>
			#end
			#foreach($it in $exchange.comment)
                <generalComment xml:lang="$it.langCode">$it.value</generalComment>
			#end
            </exchange>
		#end
		
    </exchanges>
	
	
</processDataSet>

	private void dataSetInformation(ES2Dataset eDataset, ILCDProcess iProcess) {

		ILCDProcessDescription iDescription = new ILCDProcessDescription();
		iProcess.description = iDescription;

		if (eDataset.description != null) {
			ES2Description eDescription = eDataset.description;

			// UUID
			iDescription.uuid = eDescription.id;
			// name
			iDescription.getName().addAll(eDescription.getName());

			// synonyms
			if (!eDescription.getSynonyms().isEmpty()) {
				String syns = "";
				Iterator<LangString> it = eDescription.getSynonyms().iterator();
				while (it.hasNext()) {
					LangString langString = it.next();
					syns += langString.getValue();
					if (it.hasNext()) {
						syns += "; ";
					}
				}
				iDescription.getSynonyms().add(new LangString(syns));
			}

			// general comment -> technological applicability
			if (eDescription.generalComment != null
					&& eDescription.generalComment.hasText()) {
				iDescription.getComment().add(
						eDescription.generalComment.getFirstLangString());
			}

		}

		// classifications
		for (ES2ClassificationRef eClassification : eDataset
				.getClassifications()) {
			ILCDClassification iClassification = new ILCDClassification();
			iDescription.getClassifications().add(iClassification);
			iClassification.fileURI = "../classifications.xml"; // DEFAULT

			iClassification.name = LangString.getFirstValue(eClassification
					.getClassificationSystem());
			int level = 0;
			for (LangString langString : eClassification
					.getClassificationValue()) {
				if (langString.getValue() != null) {
					String[] entries = langString.getValue().split("/");
					for (String e : entries) {
						if (e.length() > 0) {
							iClassification.getClasses().add(
									new ILCDClass(level, e));
							level++;
						}
					}
				}
			}
		}
	}

	/**
	 * Creates the time element.
	 */
	private void time(ES2Dataset eDataset, ILCDProcess iProcess) {

		if (eDataset.timePeriod != null) {
			ES2TimePeriod eTime = eDataset.timePeriod;
			ILCDTime iTime = new ILCDTime();
			iProcess.time = iTime;

			// start date
			if (eTime.startDate != null && eTime.startDate.length() > 3) {
				try {
					String val = eTime.startDate.substring(0, 4);
					iTime.referenceYear = Integer.parseInt(val);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}

			// end date
			if (eTime.endDate != null && eTime.endDate.length() > 3) {
				try {
					String val = eTime.endDate.substring(0, 4);
					iTime.validUntil = Integer.parseInt(val);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}

			// comment
			if (eTime.comment != null && eTime.comment.hasText()) {
				iTime.getDescription().add(eTime.comment.getFirstLangString());
			}

			// TODO: isValidForEntirePeriod
		}

	}


	/**
	 * Creates the technology element.
	 */
	private void technology(ES2Dataset eDataset, ILCDProcess iProcess) {

		ILCDProcessTechnology iTechnology = new ILCDProcessTechnology();
		iProcess.technology = iTechnology;

		if (eDataset.description != null) {

			// included activities start & end -> included processes
			ES2Description eDescription = eDataset.description;
			String includedActivities = null;
			if (!eDescription.getIncludedActivitiesStart().isEmpty()) {
				includedActivities = eDescription.getIncludedActivitiesStart()
						.get(0).getValue();
			}
			if (!eDescription.getIncludedActivitiesEnd().isEmpty()) {
				if (includedActivities != null
						&& includedActivities.length() > 0) {
					includedActivities += " // "
							+ eDescription.getIncludedActivitiesEnd().get(0)
									.getValue();
				} else {
					includedActivities = eDescription
							.getIncludedActivitiesEnd().get(0).getValue();
				}
			}
			if (includedActivities != null) {
				iTechnology.getDescription().add(
						new LangString(includedActivities));
			}
		}

		if (eDataset.technology != null && eDataset.technology.comment != null
				&& eDataset.technology.comment.hasText()) {
			iTechnology.getApplicability().add(
					eDataset.technology.comment.getFirstLangString());
		}
	}

	// TODO: mathematical relations

	/**
	 * Creates the LCI method element.
	 */
	private void lciMethod(ES2Dataset eDataset, ILCDProcess iProcess) {

		ILCDProcessMethod iMethod = new ILCDProcessMethod();
		iProcess.method = iMethod;

		// the process type
		if (eDataset.description != null) {
			if (eDataset.description.type == 2) {
				iMethod.dataSetType = "LCI result";
			} else {
				iMethod.dataSetType = "Unit process, black box";
			}
		}

		// enumeration values
		iMethod.lciMethodPrinciple = "Other";
		iMethod.getLciMethodApproaches().add("Other");

		// deviations from ...
		LangString stdText = new LangString();
		stdText.setValue("See the methodology report.");
		iMethod.getDeviationsFromLCIMethodApproaches().add(stdText);
		iMethod.getDeviationsFromLCIMethodPrinciple().add(stdText);
		iMethod.getDeviationsFromModellingConstants().add(stdText);
		iMethod.getModellingConstants().add(stdText);

		// reference to the methodology data set
		iMethod.getMethodDetails().add(
				ReferenceFactory.ECOINVENT_METHODIC.createReference());

	}

	/**
	 * Creates the representativeness element.
	 */
	private void representativeness(ES2Dataset eDataset, ILCDProcess iProcess) {

		ILCDRepresentativeness iRepri = new ILCDRepresentativeness();
		iProcess.representativeness = iRepri;

		// direct mapping: extrapolations, sampling procedure, percent
		if (eDataset.representativeness != null) {
			iRepri.getSamplingProcedure().addAll(
					eDataset.representativeness.getSamplingProcedure());
			iRepri.getExtrapolationPrinciples().addAll(
					eDataset.representativeness.getExtrapolations());
			iRepri.percent = eDataset.representativeness.percent;
		}

		// default entries for empty fields
		LangString stdText = new LangString();
		stdText.setValue("See the methodology report.");
		if (iRepri.getCutOffPrinciples().isEmpty())
			iRepri.getCutOffPrinciples().add(stdText);

		if (iRepri.getDeviationsFromCutOffPrinciples().isEmpty())
			iRepri.getDeviationsFromCutOffPrinciples().add(stdText);

		if (iRepri.getDataSelectionPrinciples().isEmpty())
			iRepri.getDataSelectionPrinciples().add(stdText);

		if (iRepri.getDeviationsFromDataSelectionPrinciples().isEmpty())
			iRepri.getDeviationsFromDataSelectionPrinciples().add(stdText);

		if (iRepri.getExtrapolationPrinciples().isEmpty())
			iRepri.getExtrapolationPrinciples().add(stdText);

		if (iRepri.getDeviationsFromExtrapolationPrinciples().isEmpty())
			iRepri.getDeviationsFromExtrapolationPrinciples().add(stdText);

		// the use advice
		String use = "This data set was automatically converted "
				+ "with the openLCA data converter. Before you use "
				+ "this data set you should check the entries and the "
				+ "compliance with the ILCD conformity. You "
				+ "can edit this data set with the ILCD Editor "
				+ "freely available at "
				+ "http://lct.jrc.ec.europa.eu/assessment/tools.";
		iRepri.getUseAdviceForDataSet().add(new LangString(use));

	}

	/**
	 * Creates the review elements
	 */
	private void reviews(ES2Dataset eDataset, ILCDProcess iProcess) {
		for (ES2Review eReview : eDataset.getReviews()) {

			ILCDReview iReview = new ILCDReview();
			iProcess.getReviews().add(iReview);
			if (eReview.details != null && eReview.details.hasText())
				iReview.getDetails().add(eReview.details.getFirstLangString());
			if (!eReview.getOtherDetails().isEmpty()) {
				iReview.getOtherDetails().add(
						LangString.getFirst(eReview.getOtherDetails()));
			}

			// TODO: reviewers

		}
	}

	/**
	 * Creates the administrative information.
	 */
	private void adminInfo(ES2Dataset eDataset, ILCDProcess iProcess) {

		// data generator
		if (eDataset.publication != null) {
			if (eDataset.publication.personId != null
					&& eDataset.publication.personName != null) {
				DataSetReference ref = contact(eDataset.publication.personId,
						eDataset.publication.personName,
						eDataset.publication.personEmail);
				iProcess.getDataGenerators().add(ref);
			}
		}

		// data entry by
		ILCDProcessEntry iEntry = new ILCDProcessEntry();
		iProcess.entry = iEntry;
		iEntry.getDataFormatReferences().add(
				ReferenceFactory.ILCD_FORMAT.createReference());
		iEntry.getDataFormatReferences().add(
				ReferenceFactory.ECOSPOLD_FORMAT.createReference());
		if (eDataset.fileAttributes != null
				&& eDataset.fileAttributes.creationTimestamp != null) {
			iEntry.timeStamp = eDataset.fileAttributes.creationTimestamp;
		} else {
			iEntry.timeStamp = Time.now();
		}

		// reference to contact
		if (eDataset.dataEntryBy != null) {
			if (eDataset.dataEntryBy.personId != null
					&& eDataset.dataEntryBy.personName != null) {
				DataSetReference ref = contact(eDataset.dataEntryBy.personId,
						eDataset.dataEntryBy.personName,
						eDataset.dataEntryBy.personEmail);
				iEntry.dataSetEntryReference = ref;
			}
		}

		// publication and ownership
		ILCDProcessPublication publication = new ILCDProcessPublication();
		iProcess.publication = publication;

		if (eDataset.publication != null) {
			publication.copyright = eDataset.publication.isCopyrightProtected;
			publication.dataSetVersion = "01.00.000";
			String processId = iProcess.description.uuid;
			publication.permanentDataSetURI = "http://ecoinvent.org?processId="
					+ processId;
			if (eDataset.publication.dataPublishedIn != null) {
				switch (eDataset.publication.dataPublishedIn) {
				case 0:
					publication.workflowStatus = "Working draft";
					break;
				case 1:
					publication.workflowStatus = "Data set finalised; subsystems published";
					break;
				case 2:
					publication.workflowStatus = "Data set finalised; entirely published";
					break;
				default:
					break;
				}
			}

			// publication source
			if (eDataset.publication.publishedSourceId != null
					&& eDataset.publication.publishedSourceFirstAuthor != null) {
				DataSetReference sourceRef = source(
						eDataset.publication.publishedSourceId,
						eDataset.publication.publishedSourceFirstAuthor,
						eDataset.publication.publishedSourceYear);
				publication.republicationReference = sourceRef;
			}
		}

	}

	// exchanges start

	private void exchanges(ES2Dataset eDataSet, ILCDProcess iProcess) {

		ILCDQuantitativeReference qRef = new ILCDQuantitativeReference();
		qRef.type = "Reference flow(s)";
		iProcess.quantitativeReference = qRef;

		// the intermediate exchanges
		for (ES2IntermediateExchange eExchange : eDataSet
				.getIntermediateExchanges()) {

			ILCDExchange iExchange = new ILCDExchange();
			iProcess.getExchanges().add(iExchange);
			iExchange.id = iProcess.getExchanges().size();// id ++

			// map the general exchange attributes
			iExchange.variableReference = eExchange.variableName;
			if (!eExchange.getComment().isEmpty()) {				
				iExchange.getComment().add(
						LangString.getFirst(eExchange.getComment()));
			}
			iExchange.direction = eExchange.outputGroup != null ? "Output"
					: "Input";

			// make flow (reference) and numeric values
			double factor = flowDispatch(eExchange, iExchange);
			numericValues(eExchange, iExchange, factor);

			// check for quantitative reference
			if (eExchange.outputGroup != null
					&& eExchange.outputGroup.equals(0)) {
				qRef.getReferenceFlows().add(iExchange.id);
			}

		}

		// the elementary exchanges
		for (ES2ElementaryExchange eExchange : eDataSet
				.getElementaryExchanges()) {

			ILCDExchange iExchange = new ILCDExchange();
			iProcess.getExchanges().add(iExchange);
			iExchange.id = iProcess.getExchanges().size();// id ++

			// map the general exchange attributes
			iExchange.variableReference = eExchange.variableName;
			if (!eExchange.getComment().isEmpty()) {				
				iExchange.getComment().add(
						LangString.getFirst(eExchange.getComment()));
			}
			iExchange.direction = eExchange.outputGroup != null ? "Output"
					: "Input";

			// make flow (reference) and numeric values
			double factor = flowDispatch(eExchange, iExchange);
			numericValues(eExchange, iExchange, factor);

		}

	}

	/**
	 * The dispatch function for assigning the respective flow information to
	 * the target exchange. There are four possibilities:
	 * <ul>
	 * <li>The source exchange describes an elementary flow which is stored in
	 * the database and there <b>IS</b> a mapping to a stored flow of the target
	 * format in the database: <code>assignedElemFlow(...)</code> is called.</li>
	 * <li>The source exchange describes an elementary flow which is stored in
	 * the database and there <b>IS NO</b> mapping to a stored flow of the
	 * target format in the database: <code>unassignedElemFlow(...)</code> is
	 * called.</li>
	 * <li>The source exchange describes an elementary flow which is <b>NOT</b>
	 * stored in the database: <code>unknownElemFlow(...)</code> is called.</li>
	 * <li>The source exchange describes a product flow:
	 * <code>productFlow(...)</code> is called</li>
	 * </ul>
	 * 
	 * This function is included in every conversion but, naturally, the
	 * implementation of the case differentiation differs depending on the
	 * respective source and target format.
	 * 
	 * As the reference unit in which a flow is stated in the source format may
	 * differs to the reference unit (of this flow) in the target format, this
	 * function returns a conversion factor which is then applied in the numeric
	 * values of the exchange (see the function <code>numericValues(..)</code>).
	 */
	private double flowDispatch(Object eExchange, ILCDExchange iExchange) {

		// the conversion factor
		double factor = 0;

		if (eExchange instanceof ES2IntermediateExchange) {

			// product flow
			factor = productFlow((ES2IntermediateExchange) eExchange, iExchange);

		} else if (eExchange instanceof ES2ElementaryExchange) {

			ES2ElementaryExchange _eExchange = (ES2ElementaryExchange) eExchange;

			// test if it is an assigned elementary flow
			ElemFlowMap.Entry flowEntry = ElemFlowMap
					.es2ToILCD(_eExchange.elementaryExchangeId);
			if (flowEntry != null) {

				// assigned elementary flow
				factor = assignedElemFlow(flowEntry, iExchange);

			} else {

				// test if it is a known but unassigned elementary flow
				ES2ElemFlowRec flowRec = ES2ElemFlowRec
						.forID(_eExchange.elementaryExchangeId);
				if (flowRec != null) {

					// unassigned but known elementary flow
					factor = unassignedElemFlow(flowRec, iExchange);

				} else {

					// unknown elementary flow
					factor = unknownElemFlow(_eExchange, iExchange);
				}
			}
		}

		return factor;
	}

	private double productFlow(ES2IntermediateExchange eExchange,
			ILCDExchange iExchange) {

		// create the flow reference
		String uuid = eExchange.intermediateExchangeId;
		String name = LangString.getFirstValue(eExchange.getName());
		DataSetReference flowRef = ILCDHelper.newFlowRef(uuid, name);
		iExchange.flowDataSet = flowRef;

		// entry for the unit - flow property conversion
		UnitMap.Entry unitEntry = UnitMap.es2ToILCD(eExchange.unitId);
		if (unitEntry == null) {
			logger.severe("No unit assignment for: " + eExchange.unitId);
			return 0;
		}

		// create the flow if required
		if (!ilcdFolder.exists(flowRef)) {

			ILCDFlow flow = ILCDHelper.makeFlow(uuid, name,
					eExchange.casNumber, null, "Product flow", unitEntry);

			// create the flow
			ILCDHelper.writeFlow(ilcdFolder, flowRef, flow, createdFiles);
		}

		return unitEntry.getFactor();
	}

	private double assignedElemFlow(ElemFlowMap.Entry mapEntry,
			ILCDExchange iExchange) {

		ILCDElemFlowRec flowRec = ILCDElemFlowRec.forID(mapEntry.getFlowId());
		if (flowRec == null) {
			logger.severe("Cannot load ILCD flow for ID: "
					+ mapEntry.getFlowId());
			return 0;
		}

		// set the flow reference
		DataSetReference ref = flowRec.toReference();
		iExchange.flowDataSet = ref;

		// create the flow data set if required
		if (!ilcdFolder.exists(ref)) {
			ILCDHelper.writeFlow(ilcdFolder, ref, flowRec.toFlow(),
					createdFiles);
		}

		return mapEntry.getFactor();
	}

	private double unassignedElemFlow(ES2ElemFlowRec flowRec,
			ILCDExchange iExchange) {

		// create the flow reference
		String uuid = flowRec.getId();
		String name = flowRec.getName();
		DataSetReference flowRef = ILCDHelper.newFlowRef(uuid, name);
		iExchange.flowDataSet = flowRef;

		// entry for the unit - flow property conversion
		UnitMap.Entry unitEntry = UnitMap.es2ToILCD(flowRec.getUnitId());
		if (unitEntry == null) {
			logger.severe("No unit assignment for: " + flowRec.getUnitId());
			return 0;
		}

		if (!ilcdFolder.exists(flowRef)) {

			// create the elementary flow
			ILCDFlow flow = ILCDHelper.makeFlow(uuid, name, flowRec.getCas(),
					flowRec.getFormula(), "Elementary flow", unitEntry);

			// the flow category
			String catId = CompartmentMap.es2ToILCD(flowRec.getCompartmentId());
			if (catId != null) {
				ILCDCompartmentRec rec = ILCDCompartmentRec.forID(catId);
				if (rec != null) {
					flow.description.getElemFlowCategorizations().add(
							rec.toCategorization());
				}
			}

			// create the flow file
			ILCDHelper.writeFlow(ilcdFolder, flowRef, flow, createdFiles);

		}

		return unitEntry.getFactor();

	}

	/**
	 * Creates a reference to an ILCD elementary flow for the given EcoSpold 02
	 * elementary exchange. If there is no elementary flow data set in the ILCD
	 * folder of this conversion, this flow data set is created.
	 */
	private double unknownElemFlow(ES2ElementaryExchange eExchange,
			ILCDExchange iExchange) {

		// create the flow reference
		String uuid = eExchange.elementaryExchangeId;
		String name = LangString.getFirstValue(eExchange.getName());
		DataSetReference flowRef = ILCDHelper.newFlowRef(uuid, name);
		iExchange.flowDataSet = flowRef;

		// entry for the unit - flow property conversion
		UnitMap.Entry unitEntry = UnitMap.es2ToILCD(eExchange.unitId);
		if (unitEntry == null) {
			logger.severe("No unit assignment for: " + eExchange.unitId);
			return 0;
		}

		if (!ilcdFolder.exists(flowRef)) {

			// create the elementary flow
			ILCDFlow flow = ILCDHelper.makeFlow(uuid, name,
					eExchange.casNumber, eExchange.formula, "Elementary flow",
					unitEntry);

			// the flow category
			String catId = CompartmentMap.es2ToILCD(eExchange.compartmentId);
			if (catId != null) {
				ILCDCompartmentRec rec = ILCDCompartmentRec.forID(catId);
				if (rec != null) {
					flow.description.getElemFlowCategorizations().add(
							rec.toCategorization());
				}
			}

			// create the flow file
			ILCDHelper.writeFlow(ilcdFolder, flowRef, flow, createdFiles);
		}

		return unitEntry.getFactor();
	}

	private void numericValues(Object eExchange, ILCDExchange iExchange,
			double factor) {

		if (factor == 0) {
			logger.warning("Conversion factor is 0: " + eExchange);
		}

		double amount = 0;
		if (eExchange instanceof ES2IntermediateExchange) {
			amount = ((ES2IntermediateExchange) eExchange).amount;
		} else if (eExchange instanceof ES2ElementaryExchange) {
			amount = ((ES2ElementaryExchange) eExchange).amount;
		}

		iExchange.resultingAmount = amount * factor;
		iExchange.meanAmount = amount * factor;

		// TODO: uncertainty...

	}

	// exchanges end

	/**
	 * Creates an ILCD contact data set reference for the given attributes. If
	 * there is no contact file for this reference in the ILCD folder, this file
	 * is created.
	 */
	private DataSetReference contact(String id, String name, String email) {

		DataSetReference ref = new DataSetReference();
		ref.setName(name);
		ref.setRefObjectId(id);
		ref.setType(ILCDDataSetType.Contact.toString());
		ref.setUri("../contacts/" + id + "_01.00.000.xml");
		ref.setVersion("01.00.000");
		ref.getDescription().add(new LangString(name));

		if (!ilcdFolder.exists(ref)) {

			// create the contact data set
			ILCDContact contact = new ILCDContact();

			// contact description
			contact.description = new ILCDContactDescription();
			contact.description.email = email;
			contact.description.uuid = id;
			contact.description.getName().add(new LangString(name));
			contact.description.getShortName().add(new LangString(name));

			// classification
			ILCDClassification classification = new ILCDClassification();
			ILCDClass clazz = new ILCDClass();
			clazz.level = 0;
			clazz.name = "Persons";
			classification.getClasses().add(clazz);
			contact.description.getClassifications().add(classification);

			// data entry
			ILCDEntry entry = new ILCDEntry();
			contact.entry = entry;
			entry.timestamp = Time.now();
			entry.getDataSetFormats().add(
					ReferenceFactory.ILCD_FORMAT.createReference());
			entry.getDataSetFormats().add(
					ReferenceFactory.ECOSPOLD_FORMAT.createReference());

			// publication
			ILCDPublication publication = new ILCDPublication();
			contact.publication = publication;
			publication.dataSetVersion = "01.00.000";
			publication.permanentDataSetURI = "http://ecoinvent.org?personId="
					+ id;

			// save the file
			File outFile = ilcdFolder.file(ref);
			outputter.output(contact, TemplateType.ILCDContact, outFile, false);
			// reference for created files
			DataSetReference refCopy = ref.copy();
			refCopy.setUri(outFile.toURI().toString());
			createdFiles.add(refCopy);
		}

		return ref;
	}

	/**
	 * Creates an ILCD source data set reference for the given attributes. If
	 * there is no source file for this reference in the ILCD folder, this file
	 * is created.
	 */
	private DataSetReference source(String id, String author, String year) {

		String cit = year == null ? author : author + " " + year;

		// the reference to the contact data set
		DataSetReference ref = new DataSetReference();
		ref.setName(cit);
		ref.setRefObjectId(id);
		ref.setType(ILCDDataSetType.Source.toString());
		ref.setUri("../sources/" + id + "_01.00.000.xml");
		ref.setVersion("01.00.000");
		ref.getDescription().add(new LangString(cit));

		if (!ilcdFolder.exists(ref)) {

			// create the source file

			ILCDSource source = new ILCDSource();

			// the source description
			ILCDSourceDescription iDescription = new ILCDSourceDescription();
			source.description = iDescription;
			iDescription.uuid = id;
			iDescription.sourceCitation = cit;
			iDescription.getShortName().add(new LangString(cit));

			// classification
			ILCDClassification classification = new ILCDClassification();
			iDescription.getClassifications().add(classification);
			ILCDClass clazz = new ILCDClass();
			classification.getClasses().add(clazz);
			clazz.level = 0;
			clazz.name = "Publications and communications";

			// data entry
			ILCDEntry entry = new ILCDEntry();
			source.entry = entry;
			entry.timestamp = Time.now();
			entry.getDataSetFormats().add(
					ReferenceFactory.ILCD_FORMAT.createReference());
			entry.getDataSetFormats().add(
					ReferenceFactory.ECOSPOLD_FORMAT.createReference());

			// publication
			ILCDPublication publication = new ILCDPublication();
			source.publication = publication;
			publication.dataSetVersion = "01.00.000";
			publication.permanentDataSetURI = "http://ecoinvent.org?sourceId="
					+ id;

			// store the source
			File outFile = ilcdFolder.file(ref);
			outputter.output(source, TemplateType.ILCDSource, outFile, false);
			// reference for created files
			DataSetReference refCopy = ref.copy();
			refCopy.setUri(outFile.toURI().toString());
			createdFiles.add(refCopy);
		}

		return ref;
	}
}