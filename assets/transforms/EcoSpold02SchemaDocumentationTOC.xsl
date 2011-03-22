<?xml version="1.0" encoding="UTF-8"?>
<!--
The contents of this file are subject to the EcoSpold Public License Version 1.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.ecoinvent.ch.
Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language governing rights and limitations under the License.
The Original Code consists of the EcoSpold data format and EcoSpold Access.
The Original Code was created by the ecoinvent Centre, Switzerland (Swiss Centre for Life Cycle Inventories) and ifu Hamburg GmbH, Germany. Portions created by the ecoinvent Centre and ifu Hamburg GmbH are Copyright (C) ecoinvent Centre. All Rights Reserved.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	<xsl:output method="html" version="1.0" encoding="UTF-8" omit-xml-declaration="no" indent="no" media-type="text/html"/>
	<xsl:variable name="slDataType">..\..\Schemas\EcoSpold02DataTypes.xsd</xsl:variable>
	<xsl:variable name="slMetainformation">..\..\Schemas\EcoSpold02MetaInformation.xsd</xsl:variable>
	<xsl:variable name="slFlowData">..\..\Schemas\EcoSpold02FlowData.xsd</xsl:variable>
	<xsl:template name="emitSchemaAnnotationTOC">
		<tr>
			<td class="ttl">
				<xsl:value-of select="/xsd:schema/@targetNamespace"/>
			</td>
		</tr>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">Activity Description</xsl:with-param>
			<xsl:with-param name="elementName">TActivityDescription</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Activity</xsl:with-param>
			<xsl:with-param name="elementName">TActivity</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Classification</xsl:with-param>
			<xsl:with-param name="elementName">TClassification</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Geography</xsl:with-param>
			<xsl:with-param name="elementName">TGeography</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Technology</xsl:with-param>
			<xsl:with-param name="elementName">TTechnology</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Time Period</xsl:with-param>
			<xsl:with-param name="elementName">TTimePeriod</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Macro-economic Scenario</xsl:with-param>
			<xsl:with-param name="elementName">TMacroEconomicScenario</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">Flow Data</xsl:with-param>
			<xsl:with-param name="elementName">TFlowData</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowDataTOC">
			<xsl:with-param name="title">Exchanges</xsl:with-param>
			<xsl:with-param name="elementName">TCustomExchange</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowDataTOC">
			<xsl:with-param name="title">Only valid for Intermediate Exchanges</xsl:with-param>
			<xsl:with-param name="elementName">TIntermediateExchange</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowDataTOC">
			<xsl:with-param name="title">Only valid for Elementary Exchanges</xsl:with-param>
			<xsl:with-param name="elementName">TElementaryExchange</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowDataTOC">
			<xsl:with-param name="title">Parameters</xsl:with-param>
			<xsl:with-param name="elementName">TParameter</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">Complex Types</xsl:with-param>
			<xsl:with-param name="elementName">TComplexTypes</xsl:with-param>
			<xsl:with-param name="level">ttl2</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataTypeTOC">
			<xsl:with-param name="title">TCompartment</xsl:with-param>
			<xsl:with-param name="elementName">TCompartment</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataTypeTOC">
			<xsl:with-param name="title">TUncertainty</xsl:with-param>
			<xsl:with-param name="elementName">TUncertainty</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowDataTOC">
			<xsl:with-param name="title">TProperty</xsl:with-param>
			<xsl:with-param name="elementName">TProperty</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitFlowDataTOC">
			<xsl:with-param name="title">TTransferCoefficient</xsl:with-param>
			<xsl:with-param name="elementName">TTransferCoefficient</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitDataTypeTOC">
			<xsl:with-param name="title">TTextAndImage</xsl:with-param>
			<xsl:with-param name="elementName">TTextAndImage</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">Modelling and validation</xsl:with-param>
			<xsl:with-param name="elementName">TModellingAndValidation</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Representativeness</xsl:with-param>
			<xsl:with-param name="elementName">TRepresentativeness</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Review</xsl:with-param>
			<xsl:with-param name="elementName">TReview</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">Administrative information</xsl:with-param>
			<xsl:with-param name="elementName">TAdministrativeInformation</xsl:with-param>
			<xsl:with-param name="level">ttl1</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Data entry by</xsl:with-param>
			<xsl:with-param name="elementName">TDataEntryBy</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitMetaInformationTOC">
			<xsl:with-param name="title">Data generator and publication</xsl:with-param>
			<xsl:with-param name="elementName">TDataGeneratorAndPublication</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">File attributes</xsl:with-param>
			<xsl:with-param name="elementName">TFileAttributes</xsl:with-param>
			<xsl:with-param name="level">ttl3</xsl:with-param>
		</xsl:call-template>
		<xsl:call-template name="emitTOC">
			<xsl:with-param name="title">Required Context Reference</xsl:with-param>
			<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
			<xsl:with-param name="level">ttl3</xsl:with-param>
		</xsl:call-template>
	</xsl:template>
	<xsl:template name="emitMetaInformationTOC">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:if test="document($slMetainformation)/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(document($slMetainformation)/descendant::*[@name = $elementName]/@maxOccurs)">
			<tr>
				<td>
					<table width="100%" style="background-color:#80FF80;">
						<tr>
							<td class="ttl3">
								<a>
									<xsl:attribute name="href">#<xsl:value-of select="$elementName"/></xsl:attribute>
									<xsl:value-of select="$title"/>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</xsl:if>
	</xsl:template>
	<xsl:template name="emitDataTypeTOC">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:if test="document($slDataType)/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(document($slDataType)/descendant::*[@name = $elementName]/@maxOccurs)">
			<tr>
				<td>
					<table width="100%" style="background-color:#80FF80;">
						<tr>
							<td class="ttl3">
								<a>
									<xsl:attribute name="href">#<xsl:value-of select="$elementName"/></xsl:attribute>
									<xsl:value-of select="$title"/>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</xsl:if>
	</xsl:template>
	<xsl:template name="emitFlowDataTOC">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:if test="document($slFlowData)/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(document($slFlowData)/descendant::*[@name = $elementName]/@maxOccurs)">
			<tr>
				<td>
					<table width="100%" style="background-color:#80FF80;">
						<tr>
							<td class="ttl3">
								<a>
									<xsl:attribute name="href">#<xsl:value-of select="$elementName"/></xsl:attribute>
									<xsl:value-of select="$title"/>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</xsl:if>
	</xsl:template>
	<xsl:template name="emitTOC">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="level">ttl3</xsl:param>
		<xsl:if test="/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(/descendant::*[@name = $elementName]/@maxOccurs)">
			<tr>
				<td>
					<table width="100%" style="background-color:#80FF80;">
						<tr>
							<td>
								<xsl:attribute name="class"><xsl:value-of select="$level"/></xsl:attribute>
								<a>
									<xsl:attribute name="href">#<xsl:value-of select="$elementName"/></xsl:attribute>
									<xsl:value-of select="$title"/>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>
