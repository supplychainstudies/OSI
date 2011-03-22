<?xml version="1.0" encoding="UTF-8"?>
<!--
The contents of this file are subject to the EcoSpold Public License Version 1.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.ecoinvent.ch.
Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language governing rights and limitations under the License.
The Original Code consists of the EcoSpold data format and EcoSpold Access.
The Original Code was created by the ecoinvent Centre, Switzerland (Swiss Centre for Life Cycle Inventories) and ifu Hamburg GmbH, Germany. Portions created by the ecoinvent Centre and ifu Hamburg GmbH are Copyright (C) ecoinvent Centre. All Rights Reserved.
-->
<xsl:stylesheet 
	version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:fo="http://www.w3.org/1999/XSL/Format"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	
	<xsl:import href="EcoSpold02SchemaDocumentationTOC.xsl"/>
	<xsl:import href="EcoSpold02SchemaDocumentationData.External.xsl"/>
	
	<xsl:output 
		method="html" 
		version="1.0" 
		encoding="UTF-8" 
		omit-xml-declaration="no" 
		indent="no" 
		media-type="text/html"/>
	
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
						font-weight: bold;
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
							<xsl:call-template name="emitSchemaAnnotationTOC"/>
							<xsl:call-template name="emitSchemaAnnotationData"/>
						</td>
					</tr>
				</table>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
