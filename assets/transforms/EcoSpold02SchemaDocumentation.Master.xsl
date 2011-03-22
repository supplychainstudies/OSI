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
	<xsl:template match="xsd:schema">
		<html>
			<head>
				<title>EcoSpold02 Schema Documentation</title>
				<style type="text/css">
					a:link{color:#808080;
						text-decoration:none;}
					a:visited{color:#808080;
						text-decoration:none;}
					a:hover{color:red;
						text-decoration:underline;}
					body {background-color:#C0FFC0;
						color:#000000;
						font-family:arial, verdana, sans-serif; 
						font-size:12pt;}
					table {background:#C0FFC0;
						height:20px;
						color:#000000;}
					td {	background:#C0FFC0;
						font-size:9pt;
						text-align:left;
						color:#000000;}
					td.ttl {background:#008000;
						font-size:16pt; 
						height:22;
						text-align:left;
						color:#FFFFFF;}
					td.ttl1 {background:#00C000;
						font-size:14pt; 
						font-weight: bold;
						height:22;
						text-align:left;
						color:#000000;}
					td.ttl2 {background:#40FF40;
						font-size:12pt; 
						font-style: italic;
						height:22;
						text-align:left;
						color:#000000;}
					td.ttl3 {background:#80FF80;
						font-weight: bold;
						font-size:12pt; 
						height:22;
						text-align:left;}
					td.ttc3 {background:#80FF80;
						font-size:10pt; 
						height:22;
						padding-left: 5;
						padding-right: 5;
						text-align:center;}
					td.ttl4 {background:#FFFFFF;
						font-size:10pt; 
						height:22;
						padding-left: 5;
						padding-right: 5;
						text-align:left;}
					td.ttc4 {background:#FFFFFF;
						font-size:10pt; 
						height:22;
						padding-left: 5;
						padding-right: 5;
						text-align:center;}
					th.ttc3 {background:#80FF80;
						font-size:10pt; 
						height:22;
						padding-left: 5;
						padding-right: 5;
						text-align:center;}
				</style>
			</head>
			<body>
				<table width="100%">
					<tr>
						<td>
							<xsl:call-template name="emitSchemaAnnotationData"/>
						</td>
					</tr>
				</table>
			</body>
		</html>
	</xsl:template>
	<xsl:template name="emitSchemaAnnotationData">
		<a href="top"/>
		<xsl:if test="xsd:complexType[@name='TValidIntermediateExchanges']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Intermediate Exchange</xsl:with-param>
				<xsl:with-param name="elementName">TValidIntermediateExchanges</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidIntermediateExchange</xsl:with-param>
				<xsl:with-param name="elementName">TValidIntermediateExchange</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">Classification</xsl:with-param>
				<xsl:with-param name="elementName">TClassification</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">TProperty</xsl:with-param>
				<xsl:with-param name="elementName">TProperty</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">TUncertainty</xsl:with-param>
				<xsl:with-param name="elementName">TUncertainty</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">Required Context Reference</xsl:with-param>
				<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidElementaryExchanges']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Elementary Exchanges</xsl:with-param>
				<xsl:with-param name="elementName">TValidElementaryExchanges</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidElementaryExchange</xsl:with-param>
				<xsl:with-param name="elementName">TValidElementaryExchange</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">TProperty</xsl:with-param>
				<xsl:with-param name="elementName">TProperty</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">TUncertainty</xsl:with-param>
				<xsl:with-param name="elementName">TUncertainty</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">Required Context Reference</xsl:with-param>
				<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidGeographies']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Geographies</xsl:with-param>
				<xsl:with-param name="elementName">TValidGeographies</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidGeography</xsl:with-param>
				<xsl:with-param name="elementName">TValidGeography</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidSystemModels']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid SystemModels</xsl:with-param>
				<xsl:with-param name="elementName">TValidSystemModels</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidSystemModel</xsl:with-param>
				<xsl:with-param name="elementName">TValidSystemModel</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidCompanies']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Companies</xsl:with-param>
				<xsl:with-param name="elementName">TValidCompanies</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidCompany</xsl:with-param>
				<xsl:with-param name="elementName">TValidCompany</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidMacroEconomicScenarios']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Macro Economic Scenarios</xsl:with-param>
				<xsl:with-param name="elementName">TValidMacroEconomicScenarios</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidMacroEconomicScenario</xsl:with-param>
				<xsl:with-param name="elementName">TValidMacroEconomicScenario</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidLanguages']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Languages</xsl:with-param>
				<xsl:with-param name="elementName">TValidLanguages</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidLanguage</xsl:with-param>
				<xsl:with-param name="elementName">TValidLanguage</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidCompartments']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Compartments</xsl:with-param>
				<xsl:with-param name="elementName">TValidCompartments</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidCompartment</xsl:with-param>
				<xsl:with-param name="elementName">TValidCompartment</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidSubcompartment</xsl:with-param>
				<xsl:with-param name="elementName">TValidSubcompartment</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidClassificationSystems']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Classification Systems</xsl:with-param>
				<xsl:with-param name="elementName">TValidClassificationSystems</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidClassificationSystem</xsl:with-param>
				<xsl:with-param name="elementName">TValidClassificationSystem</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidClassificationValue</xsl:with-param>
				<xsl:with-param name="elementName">TValidClassificationValue</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TActivityIndex']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Activity Index</xsl:with-param>
				<xsl:with-param name="elementName">TActivityIndex</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TActivityIndexEntry</xsl:with-param>
				<xsl:with-param name="elementName">TActivityIndexEntry</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TExchangeActivityIndex']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Exchange Activity Index</xsl:with-param>
				<xsl:with-param name="elementName">TExchangeActivityIndex</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TExchangeActivityIndexEntry</xsl:with-param>
				<xsl:with-param name="elementName">TExchangeActivityIndexEntry</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TExchangeActivityList</xsl:with-param>
				<xsl:with-param name="elementName">TExchangeActivityList</xsl:with-param>
				<xsl:with-param name="multiOccurence">No</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidActivityNames']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Activity Names</xsl:with-param>
				<xsl:with-param name="elementName">TValidActivityNames</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidActivityName</xsl:with-param>
				<xsl:with-param name="elementName">TValidActivityName</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidProperties']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Properties</xsl:with-param>
				<xsl:with-param name="elementName">TValidProperties</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidProperty</xsl:with-param>
				<xsl:with-param name="elementName">TValidProperty</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">Required Context Reference</xsl:with-param>
				<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidParameters']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Parameters</xsl:with-param>
				<xsl:with-param name="elementName">TValidParameters</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidParameter</xsl:with-param>
				<xsl:with-param name="elementName">TValidParameter</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">Required Context Reference</xsl:with-param>
				<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidTags']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Tags</xsl:with-param>
				<xsl:with-param name="elementName">TValidTags</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidTag</xsl:with-param>
				<xsl:with-param name="elementName">TValidTag</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidUnits']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Units</xsl:with-param>
				<xsl:with-param name="elementName">TValidUnits</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidUnit</xsl:with-param>
				<xsl:with-param name="elementName">TValidUnit</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidContext']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Context</xsl:with-param>
				<xsl:with-param name="elementName">TValidContext</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitDataType">
				<xsl:with-param name="title">Required Context Reference</xsl:with-param>
				<xsl:with-param name="elementName">TRequiredContextReference</xsl:with-param>
				<xsl:with-param name="multiOccurence">Yes</xsl:with-param>
				<xsl:with-param name="required">No</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidPersons']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Persons</xsl:with-param>
				<xsl:with-param name="elementName">TValidPersons</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidPerson</xsl:with-param>
				<xsl:with-param name="elementName">TValidPerson</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
		<xsl:if test="xsd:complexType[@name='TValidSources']">
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">Valid Sources</xsl:with-param>
				<xsl:with-param name="elementName">TValidSources</xsl:with-param>
			</xsl:call-template>
			<xsl:call-template name="emitData">
				<xsl:with-param name="title">TValidSource</xsl:with-param>
				<xsl:with-param name="elementName">TValidSource</xsl:with-param>
			</xsl:call-template>
		</xsl:if>
	</xsl:template>
	<xsl:template name="emitData">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="multiOccurence">No</xsl:param>
		<xsl:param name="required">Yes</xsl:param>
		<xsl:if test="/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(/descendant::*[@name = $elementName]/@maxOccurs)">
			<a>
				<xsl:attribute name="name"><xsl:value-of select="$elementName"/></xsl:attribute>
			</a>
			<table width="100%">
				<tr>
					<td class="ttl3">
						<xsl:value-of select="$title"/>
					</td>
				</tr>
			</table>
			<xsl:if test="/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
				<table width="100%">
					<tr>
						<td class="ttc3">Multiple occurences</td>
						<td class="ttl4">
							<xsl:value-of select="$multiOccurence"/>
						</td>
						<td class="ttc3">Required</td>
						<td class="ttl4">
							<xsl:value-of select="$required"/>
						</td>
					</tr>
					<xsl:for-each select="/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
						<tr>
							<td class="ttl4" colspan="4">
								<xsl:value-of select="."/>
							</td>
						</tr>
						<tr/>
					</xsl:for-each>
				</table>
			</xsl:if>
			<xsl:for-each select="/descendant::*[@name = $elementName]">
				<xsl:if test="name(.) != 'xsd:element'">
					<xsl:call-template name="emitDocumentation">
						<xsl:with-param name="elementName">
							<xsl:value-of select="$elementName"/>
						</xsl:with-param>
					</xsl:call-template>
				</xsl:if>
			</xsl:for-each>
			<xsl:call-template name="emitBackToTop"/>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>
