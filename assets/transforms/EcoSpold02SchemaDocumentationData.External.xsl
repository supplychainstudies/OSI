<?xml version="1.0" encoding="UTF-8"?>
<!--
The contents of this file are subject to the EcoSpold Public License Version 1.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.ecoinvent.ch.
Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language governing rights and limitations under the License.
The Original Code consists of the EcoSpold data format and EcoSpold Access.
The Original Code was created by the ecoinvent Centre, Switzerland (Swiss Centre for Life Cycle Inventories) and ifu Hamburg GmbH, Germany. Portions created by the ecoinvent Centre and ifu Hamburg GmbH are Copyright (C) ecoinvent Centre. All Rights Reserved.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	<xsl:import href="EcoSpold02SchemaDocumentation.Emit.xsl"/>
	<xsl:output method="html" version="1.0" encoding="UTF-8" omit-xml-declaration="no" indent="no" media-type="text/html"/>
	<xsl:template name="emitSchemaAnnotationData">
		<a href="top"/>
		<table width="100%">
			<tr>
				<td class="ttl">
					<xsl:value-of select="/xsd:schema/@targetNamespace"/>
				</td>
			</tr>
			<br/>
			<br/>
			<tr>
				<td class="ttl4">This documentation provides a simplified view of the EcoSpold02 schemata. Developers should consult the schema files as well to make sure the format is implemented correctly.</td>
			</tr>
			<tr>
				<td class="ttl4">To distinguish Xml attributes from elements attribute names are prefixed by "@".</td>
			</tr>
			<tr>
				<td class="ttl4">Additions behind the numbers in the "SpoldID, version 1" column are used to indicate changes compared to EcoSpold01.</td>
			</tr>
			<tr>
				<td class="ttl4">The "SpoldID, version 1" column is used to help find EcoSpold01 fields. Please use the new "fieldId" column for communications regarding the format.</td>
			</tr>
			<tr>
				<td class="ttl4">The following version 1 SpoldIds have been removed in the EcoSpold02 format:</td>
			</tr>
			<tr>
				<td class="ttl4">
205	languageCode, 206	localLanguageCode, 208	impactAssessmentResult<br></br>
400	datasetRelatesToProduct, 403	unit, 404	amount<br></br>
490	localName, 493	infrastructureProcess, 494	infrastructureIncluded, 495	category, 496	subCategory, 497	localCategory, 498	localSubCategory, 499	formula<br></br>
502	CASNumber<br></br>
727	uncertaintyAdjustments, 761	countryCode<br></br>
2401	referenceToCoProduct, 2403	allocationMethod, 2404	fraction, 2492	referenceToInputOutput<br></br>
3506	category, 3507	subCategory, 3508	infrastructureProcess, 3509	localCategory, 3510	localSubCategory<br></br>
3703	location, 3709	standardDeviation95, 3711	formula, 3794	localName, 3795	minValue, 3796	maxValue, 3797	mostLikelyValue<br></br>
5807	companyCode, 5808	countryCode<br></br>
</td>
</tr>
		</table>
		<br/>
		<br/>
		<xsl:call-template name="emitHeader">
			<xsl:with-param name="title">Activity Description</xsl:with-param>
			<xsl:with-param name="elementName">TActivityDescription</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Activity</xsl:with-param>
			<xsl:with-param name="elementName">TActivity</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataType">
			<xsl:with-param name="title">Classification</xsl:with-param>
			<xsl:with-param name="elementName">TClassification</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Geography</xsl:with-param>
			<xsl:with-param name="elementName">TGeography</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Technology</xsl:with-param>
			<xsl:with-param name="elementName">TTechnology</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Time period</xsl:with-param>
			<xsl:with-param name="elementName">TTimePeriod</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Macro-economic Scenario</xsl:with-param>
			<xsl:with-param name="elementName">TMacroEconomicScenario</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitHeader">
			<xsl:with-param name="title">Flow Data</xsl:with-param>
			<xsl:with-param name="elementName">TFlowData</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowData">
			<xsl:with-param name="title">Exchanges</xsl:with-param>
			<xsl:with-param name="elementName">TCustomExchange</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowData">
			<xsl:with-param name="title">Only valid for Intermediate Exchanges</xsl:with-param>
			<xsl:with-param name="elementName">TIntermediateExchange</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowData">
			<xsl:with-param name="title">Only valid for Elementary Exchanges</xsl:with-param>
			<xsl:with-param name="elementName">TElementaryExchange</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowData">
			<xsl:with-param name="title">Parameters</xsl:with-param>
			<xsl:with-param name="elementName">TParameter</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitHeader">
			<xsl:with-param name="title">Complex Types</xsl:with-param>
			<xsl:with-param name="elementName">TComplexTypes</xsl:with-param>
			<xsl:with-param name="documentation">Contains detailed specifications of complex types used on other places of the format.</xsl:with-param>
			<xsl:with-param name="level">ttl2</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataType">
			<xsl:with-param name="title">TCompartment</xsl:with-param>
			<xsl:with-param name="elementName">TCompartment</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataType">
			<xsl:with-param name="title">TUncertainty</xsl:with-param>
			<xsl:with-param name="elementName">TUncertainty</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataType">
			<xsl:with-param name="title">TProperty</xsl:with-param>
			<xsl:with-param name="elementName">TProperty</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowData">
			<xsl:with-param name="title">TTransferCoefficient</xsl:with-param>
			<xsl:with-param name="elementName">TTransferCoefficient</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataType">
			<xsl:with-param name="title">TTextAndImage</xsl:with-param>
			<xsl:with-param name="elementName">TTextAndImage</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitHeader">
			<xsl:with-param name="title">Modelling and validation</xsl:with-param>
			<xsl:with-param name="elementName">TModellingAndValidation</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Representativeness</xsl:with-param>
			<xsl:with-param name="elementName">TRepresentativeness</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Review</xsl:with-param>
			<xsl:with-param name="elementName">TReview</xsl:with-param>
			<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitHeader">
			<xsl:with-param name="title">Administrative information</xsl:with-param>
			<xsl:with-param name="elementName">TAdministrativeInformation</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Data entry by</xsl:with-param>
			<xsl:with-param name="elementName">TDataEntryBy</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">Data generator and publication</xsl:with-param>
			<xsl:with-param name="elementName">TDataGeneratorAndPublication</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformation">
			<xsl:with-param name="title">File attributes</xsl:with-param>
			<xsl:with-param name="elementName">TFileAttributes</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataType">
			<xsl:with-param name="title">Required Context Reference</xsl:with-param>
			<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
			<xsl:with-param name="required">No</xsl:with-param>
		</xsl:call-template>
	</xsl:template>
</xsl:stylesheet>
