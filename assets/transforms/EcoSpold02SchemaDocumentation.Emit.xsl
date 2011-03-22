<?xml version="1.0" encoding="UTF-8"?>
<!--
The contents of this file are subject to the EcoSpold Public License Version 1.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.ecoinvent.ch.
Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language governing rights and limitations under the License.
The Original Code consists of the EcoSpold data format and EcoSpold Access.
The Original Code was created by the ecoinvent Centre, Switzerland (Swiss Centre for Life Cycle Inventories) and ifu Hamburg GmbH, Germany. Portions created by the ecoinvent Centre and ifu Hamburg GmbH are Copyright (C) ecoinvent Centre. All Rights Reserved.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:es="http://www.EcoInvent.org/EcoSpold02" >
	<xsl:variable name="slDataType">../../../Schemas\EcoSpold02DataTypes.xsd</xsl:variable>
	<xsl:variable name="slMetainformation">../../../Schemas\EcoSpold02MetaInformation.xsd</xsl:variable>
	<xsl:variable name="slFlowData">../../../Schemas\EcoSpold02FlowData.xsd</xsl:variable>

	<xsl:template name="emitDocumentation">
		<xsl:param name="elementName"/>
		<table width="100%">
			<col width="60"/>
			<col width="5"/>
			<col width="5"/>
			<col width="15"/>
			<col width="5"/>
			<col width="80"/>
			<col width="5"/>
			<col width="5"/>
			<col width="5"/>
			<col width="5"/>
			<tr>
				<th class="ttc3">Name</th>
				<th class="ttc3">FieldID</th>
				<th class="ttc3">SpoldID, <br/>version 1</th>
				<th class="ttc3">Options</th>
				<th class="ttc3">Type</th>
				<th class="ttc3">Size</th>
				<th class="ttc3">Multiple <br/>Occurence</th>
				<th class="ttc3">Req</th>
				<th class="ttc3">Multi <br/>Language</th>
				<th class="ttc3">Redundant <br/> Master Data</th>
			</tr>
			<xsl:for-each select="descendant::*[local-name()='annotation' and ../@name != $elementName and not(../@name = 'startYear' and not(xsd:appinfo/es:fieldId)) and ../@name != 'startYearMonth' and not(../@name = 'endYear' and not(xsd:appinfo/es:fieldId)) and ../@name != 'endYearMonth' and not(local-name(..)='unique')]">
				<xsl:sort data-type="text" order="ascending" select="descendant::*[local-name()='fieldId']"/>
				<tr>
					<td class="ttl4">
						<xsl:if test="(local-name(..)='attribute')">@</xsl:if>
						<xsl:value-of select="../@name"/>
					</td>
					<td class="ttl4">
						<xsl:value-of select="xsd:appinfo/es:fieldId"/>
					</td>
					<td class="ttl4">
						<xsl:value-of select="xsd:appinfo/es:spoldID"/>
					</td>
					<td class="ttl4">
						<xsl:value-of select="xsd:appinfo/es:options"/>
					</td>
					<td class="ttl4">
						<xsl:choose>
							<xsl:when test="../@type">
								<xsl:call-template name="emitSchemaType">
									<xsl:with-param name="type">
										<xsl:value-of select="../@type"/>
									</xsl:with-param>
								</xsl:call-template>
							</xsl:when>
							<xsl:when test="../xsd:simpleType/descendant::*[@base]">
								<xsl:call-template name="emitSchemaType">
									<xsl:with-param name="type">
										<xsl:value-of select="../xsd:simpleType/descendant::*[@base]/@base"/>
									</xsl:with-param>
								</xsl:call-template>
							</xsl:when>
							<xsl:when test="../xsd:simpleType/descendant::*[@restriction]">
								<xsl:call-template name="emitSchemaType">
									<xsl:with-param name="type">
										<xsl:value-of select="../xsd:simpleType/descendant::*[@restriction]/@restriction"/>
									</xsl:with-param>
								</xsl:call-template>
							</xsl:when>
							<xsl:when test="../xsd:complexType/descendant-or-self::*[@base]">
								<xsl:call-template name="emitSchemaType">
									<xsl:with-param name="type">
										<xsl:value-of select="../xsd:complexType/descendant-or-self::*[@base]/@base"/>
									</xsl:with-param>
								</xsl:call-template>
							</xsl:when>
							<xsl:otherwise></xsl:otherwise>
						</xsl:choose>
					</td>
					<td class="ttl4">
						<xsl:call-template name="getSizeOfType">
							<xsl:with-param name="type"><xsl:value-of select="../@type"/></xsl:with-param>
						</xsl:call-template>
					</td>
					<td class="ttl4">
						<xsl:choose>
							<xsl:when test="((../../@maxOccurs and not(../../@maxOccurs=0) and not(../../@maxOccurs=1)) or (../@maxOccurs and not(../@maxOccurs=0) and not(../@maxOccurs=1))) and not(contains(../@type, 'TString') or contains(../@type, 'TCompartmentName') or contains(../@type, 'TTextAndImage'))">Yes</xsl:when>
							<xsl:otherwise>No</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class="ttl4">
						<xsl:choose>
							<xsl:when test="(not(local-name(..)='attribute') and not(../@minOccurs) or ../@minOccurs=1) or (local-name(..)='attribute' and ../@use='required')">Yes</xsl:when>
							<xsl:otherwise>No</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class="ttl4">
						<xsl:choose>
							<xsl:when test="contains(../@type, 'TString') or contains(../@type, 'TCompartmentName') or contains(../@type, 'TTextAndImage') or contains(../@type, 'TSynonym')">Yes</xsl:when>
							<xsl:otherwise>No</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class="ttl4">
						<xsl:choose>
							<xsl:when test="xsd:appinfo/es:redundantMasterDataField">Yes</xsl:when>
							<xsl:otherwise>No</xsl:otherwise>
						</xsl:choose>
					</td>
				</tr>
				<xsl:for-each select="xsd:documentation">
					<tr>
						<td class="ttl4"/>
						<td class="ttl4" colspan="9">
							<xsl:value-of select="."/>
						</td>
					</tr>
				</xsl:for-each>
				<xsl:if test="../@type='TTextAndImage'">
					<tr>
						<td class="ttl4"/>
						<td class="ttl4" colspan="9">List of text, imageUri and variable elements. The text and imageUri elements can used to describe the current section and they can be combined in any order given by their index attribute. Text variables are defined by the variable elements, which may be used in the text as {{variablename}}. If a parent text field includes a variable, this variable may be redefined by the child activity dataset while keeping the rest of the parent text intact. This allows easy changes of text parts in child processes.</td>
					</tr>
				</xsl:if>
			</xsl:for-each>
		</table>
	</xsl:template>
	<xsl:template name="emitBackToTop">
		<br/>
		<center>
			<a href="#top">Top</a>
		</center>
		<br/>
	</xsl:template>
	<xsl:template name="emitDataType">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="multiOccurence">No</xsl:param>
		<xsl:param name="required">Yes</xsl:param>
		<xsl:if test="document($slDataType)/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(document($slDataType)/descendant::*[@name = $elementName]/@maxOccurs)">
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
			<xsl:if test="document($slDataType)/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
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
					<xsl:for-each select="document($slDataType)/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
						<tr>
							<td class="ttl4" colspan="4">
								<xsl:value-of select="."/>
							</td>
						</tr>
					</xsl:for-each>
					<tr/>
				</table>
			</xsl:if>
			<xsl:for-each select="document($slDataType)/descendant::*[@name = $elementName]">
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
	<xsl:template name="emitMetaInformation">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="multiOccurence">No</xsl:param>
		<xsl:param name="required">Yes</xsl:param>
		<xsl:if test="document($slMetainformation)/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(document($slMetainformation)/descendant::*[@name = $elementName]/@maxOccurs)">
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
			<xsl:if test="document($slMetainformation)/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
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
					<xsl:for-each select="document($slMetainformation)/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
						<tr>
							<td class="ttl4" colspan="4">
								<xsl:value-of select="."/>
							</td>
						</tr>
					</xsl:for-each>
					<tr/>
				</table>
			</xsl:if>
			<xsl:for-each select="document($slMetainformation)/descendant::*[@name = $elementName]">
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
	<xsl:template name="emitFlowData">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="multiOccurence">No</xsl:param>
		<xsl:param name="required">Yes</xsl:param>
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
			<xsl:for-each select="document($slFlowData)/descendant::*[@name = $elementName]/xsd:annotation/xsd:documentation">
				<tr>
					<td class="ttl4" colspan="4">
						<xsl:value-of select="."/>
					</td>
				</tr>
				<tr/>
			</xsl:for-each>
		</table>
		<!--xsl:if test="$elementName='TIntermediateExchange' or $elementName='TExchangesWithEnvironment'">
					<xsl:for-each select="document($slFlowData)/descendant::*[@name = 'TCustomExchange']">
						<xsl:if test="name(.) != 'xsd:element'">
					<xsl:call-template name="emitDocumentation">	
						<xsl:with-param name="elementName"><xsl:value-of select="$elementName"/></xsl:with-param>
					</xsl:call-template>
						</xsl:if>
					</xsl:for-each>
				</xsl:if-->
		<xsl:for-each select="document($slFlowData)/descendant::*[@name = $elementName]">
			<xsl:if test="name(.) != 'xsd:element'">
				<xsl:call-template name="emitDocumentation">
					<xsl:with-param name="elementName">
						<xsl:value-of select="$elementName"/>
					</xsl:with-param>
				</xsl:call-template>
			</xsl:if>
		</xsl:for-each>
		<xsl:call-template name="emitBackToTop"/>
	</xsl:template>
	<xsl:template name="emitData">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="multiOccurence">No</xsl:param>
		<xsl:param name="required">Yes</xsl:param>
		<xsl:param name="level">ttl3</xsl:param>
		<xsl:if test="/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(/descendant::*[@name = $elementName]/@maxOccurs)">
			<a>
				<xsl:attribute name="name"><xsl:value-of select="$elementName"/></xsl:attribute>
			</a>
			<table width="100%">
				<tr>
					<td>
						<xsl:attribute name="class"><xsl:value-of select="$level"/></xsl:attribute>
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
	<xsl:template name="emitHeader">
		<xsl:param name="title"/>
		<xsl:param name="elementName"/>
		<xsl:param name="multiOccurence">No</xsl:param>
		<xsl:param name="required">Yes</xsl:param>
		<xsl:param name="level">ttl3</xsl:param>
		<xsl:if test="/descendant::*[@name = $elementName]/@maxOccurs != 0 or not(/descendant::*[@name = $elementName]/@maxOccurs)">
			<a>
				<xsl:attribute name="name"><xsl:value-of select="$elementName"/></xsl:attribute>
			</a>
			<table width="100%">
				<tr>
					<td>
						<xsl:attribute name="class"><xsl:value-of select="$level"/></xsl:attribute>
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
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="emitSchemaType">
		<xsl:param name="type"/>
		<xsl:choose>
			<xsl:when test="substring($type, 1, 4) = 'xsd:'">
				<xsl:value-of select="substring-after($type, 'xsd:')"/>
			</xsl:when>
			<xsl:when test="$type = 'TUncertainty' or $type='TProperty' or $type='TTransferCoefficient' or $type='TClassification' or $type='TTextAndImage' or $type='TRequiredContextReference'">
				<a>
					<xsl:attribute name="href">#<xsl:value-of select="$type"/></xsl:attribute>
					<xsl:value-of select="$type"/>
				</a>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$type"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template name="getSizeOfType">
		<xsl:param name="type"/>
			<xsl:choose>
				<xsl:when test="document($slDataType)/descendant::*[@name = $type]">
					<xsl:choose>
						<xsl:when test="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='maxLength']"><xsl:value-of select="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='maxLength']/@value"/></xsl:when>
						<xsl:when test="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='length']"><xsl:value-of select="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='length']/@value"/></xsl:when>
						<xsl:when test="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='restriction']">
							<xsl:call-template name="getSizeOfType">
								<xsl:with-param name="type">
									<xsl:value-of select="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='restriction']/@base"/>
								</xsl:with-param>
							</xsl:call-template>
						</xsl:when>
						<xsl:when test="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='extension']">
							<xsl:call-template name="getSizeOfType">
								<xsl:with-param name="type">
									<xsl:value-of select="document($slDataType)/descendant::*[@name = $type]/descendant::*[local-name()='extension']/@base"/>
								</xsl:with-param>
							</xsl:call-template>
						</xsl:when>
						<xsl:otherwise></xsl:otherwise>
					</xsl:choose>
			</xsl:when>
			<xsl:otherwise></xsl:otherwise>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>
