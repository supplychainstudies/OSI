<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:lca="http://www.openLCA.org/mappingDoc">
	<xsl:output method="html" indent="yes"/>
	<xsl:template match="/">

		<xsl:variable name="sourceFormat">
			<xsl:value-of select="/lca:mappingDoc/@SourceFormat"/>
		</xsl:variable>
		<xsl:variable name="targetFormat">
			<xsl:value-of select="/lca:mappingDoc/@TargetFormat"/>
		</xsl:variable>
		<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
				<title>
					<xsl:value-of select="$sourceFormat"/>
					<xsl:text> to </xsl:text>
					<xsl:value-of select="$targetFormat"/>
				</title>
				<style type="text/css">
					.style1 { font-family: Arial, Helvetica, sans-serif;
					font-weight: bold; color: #333333; } .style3
					{font-family: Arial, Helvetica, sans-serif;
					font-weight: bold; color: #333333; font-size: 16px;
					} .style4 { font-size: 14px; font-family: Arial,
					Helvetica, sans-serif; font-weight: bold; color:
					#333333; border-style: solid; border-color:#FFFFFF;
					} .styleDiv { border-style: solid;
					border-color:#666666; border-width:thin; } a:link {
					text-decoration:none; color:#333333; } a:visited {
					text-decoration:none; color:#333333; } a:hover {
					text-decoration:none; color:#333333;
					background-color: #CCCCCC; } a:active {
					text-decoration:none; color:#333333;
					background-color: #CCCCCC; } a:focus {
					text-decoration:none; color:#333333;
					background-color: #CCCCCC; }
				.style5 {color: #84007B}
                .style6 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #6C154A;
	font-size: 24px;
}
.style8 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #005243; font-size: 18px; }
                .style9 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #6f124b; }
                .style10 {font-weight: bold; color: #005243; font-family: Arial, Helvetica, sans-serif;}
.style12 {color: #6C154A; font-size: 18px; font-family: Arial, Helvetica, sans-serif;}
                </style>
			</head>

						<body>
				
			<table width="100%" border="0">
              <tr>
                <td width="28%"><p align="right" class="style6"><span class="style12">Converter </span></p>
                <p align="right" class="style6"><span class="style12">Mapping </span></p>
                <p align="right" class="style6"><span class="style12">Documentation</span></p>
                </td>
                <td width="36%"><span class="style6"><img src="pictures/openLCAFormatconverterLogo.gif" width="600" height="220"/></span></td>
                <td width="36%"><p class="style6"/>
                <p class="style6"/>
                <p align="left" class="style6"><span class="style12">Version 1.1, August 2007.</span></p></td>
              </tr>
			  <tr>
                <td/>
                <td bgcolor="#D3D6BD"><div align="left">
                  <xsl:text>
		          </xsl:text>
                </div> <div align="left"><span class="style1"><xsl:text>
                    Conversion: 
                </xsl:text>
			        <xsl:value-of select="/lca:mappingDoc/@SourceFormat"/>
			        <xsl:text/>
				      <xsl:text/>
		                  <xsl:text>
		        </xsl:text>
				  <xsl:text>
					  to 
				</xsl:text>
			        <xsl:value-of select="/lca:mappingDoc/@TargetFormat"/>
			       </span></div></td>
                <td/>
              </tr>
            </table>
				<table width="100%" border="0">
                  <tr>
                    <td><p class="style8">Changes to the previous version 1.0:</p>
                      <p class="style1">Since complex types are detected automatically when creating this mapping doc, also integer values in intervals, e.g. "thisElement is Element of [1;3]", are marked as complex elements. As an effect, a data type warning is issued when these elements are mapped to integer values of the other format. This is misleading and we corrected it therefore in the present version. </p>
                      <p class="style1">Specifically, the following elements were changed:</p>
                      <p class="style1">EcoSpold-MetaInformation-TSource-volumeNo: Anonymous type; was 'complexType' -&gt; changed to 'integer' size:=3;</p>
                    <p class="style1">EcoSpold-MetaInformation-TSource-sourceType: Anonymous type; was 'complexType' -&gt; changed to 'integer' enum:=true;</p>
                    <p class="style1">EcoSpold-MetaInformation-TSource-pageNumbers: Anonymous type; was 'complexType' -&gt; changed to 'string' size:=15;</p>
                    <p class="style1">EcoSpold-MetaInformation-TDataSetInformation-type: Anonymous type; was 'complexType' -&gt; changed to 'integer' enum:=true;</p>
                    <p class="style1">EcoSpold-MetaInformation-TDataSetInformation-energyValues: Anonymous type; was 'complexType' -&gt; changed to 'integer' enum:=true;</p>
                    <p class="style1">EcoSpold-MetaInformation-TDataGeneratorAndPublication-dataPublishedIn: Anonymous type; was 'complexType' -&gt; changed to 'integer' enum:=true;</p>
                    <p class="style1">EcoSpold-MetaInformation-TDataGeneratorAndPublication-accessRestrictedTo: Anonymous type; was 'complexType' -&gt; changed to 'integer' enum:=true;</p>
                    <p><span class="style1">ELCD-CommonValidation-ValidationGroup1-method: Complex type, but in fact only an enumeration -&gt; changed to string enum:=true;</span></p>
                    <p/></td>
                  </tr>
                </table>
				<table width="100%" border="0">
                  <tr>
                    <td bordercolor="#6C154A">
					<p class="style8">Explanation ("How to read this document") </p>
					<p class="style1">This document shows how the elements of the target format are addressed by the source format. </p>
                      <p class="style1"> Elements of the target format are shown as Parent, Key, and Fields; matching fields of the source format are in each case shown below, as source fields. </p>
                      <p class="style1"> In each case, the conversion is evaluated with the following criteria:</p>
                    </td>
                  </tr>
                  <tr>
                    <td bordercolor="#6C154A"><table width="100%" border="0" bgcolor="#D6D3BD" class="style4">
                      <tr>
                        <td width="15%">Requirement</td>
                        <td width="85%"><br/>
                        </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_resolved.gif"/> </div></td>
                        <td width="85%"> Both target and source required or target optional </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_warn.gif"/> </div></td>
                        <td width="85%"> Target required and source optional </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_problem.gif"/> </div></td>
                        <td width="85%"> Target format default value needs to be set </td>
                      </tr>
                      <tr>
                        <td width="15%">Occurrence</td>
                        <td width="85%"><br/>
                        </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_resolved.gif"/> </div></td>
                        <td width="85%"> 1:1 relationship (source field : target field) </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_warn.gif"/> </div></td>
                        <td width="85%"> 0:1 or n:1 relationship (source field : target field), no or several related fields in the source format </td>
                      </tr>
                      <tr>
                        <td width="15%">Nomenclature / pattern</td>
                        <td width="85%"><br/>
                        </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_resolved.gif"/> </div></td>
                        <td width="85%"> Target field has no nomenclature or is not a pattern </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_warn.gif"/> </div></td>
                        <td width="85%"> Target field has nomenclature or is a pattern </td>
                      </tr>
                      <tr>
                        <td width="15%">Data type</td>
                        <td width="85%"><br/>
                        </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_resolved.gif"/> </div></td>
                        <td width="85%"> Target and source of same data type </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_warn.gif"/> </div></td>
                        <td width="85%"> Target and source of same data type but of different length (esp. strings) </td>
                      </tr>
                      <tr bgcolor="#FFFFFF">
                        <td width="15%"><div align="center"> <img src="pictures/show_problem.gif"/> </div></td>
                        <td width="85%"> At least one source field is of a different data type than the target field </td>
                      </tr>
                    </table>
                    <p class="style1">Links to the source code open the source code as it is implemented in XSLT. </p></td>
                  </tr>
            </table>
				<p class="style8">
            Elements</p>
				<p>
			<dl class="style4">
						<xsl:for-each select="/lca:mappingDoc/lca:parentElement">
							<xsl:sort select="./@key"/>
							<dt>
								<xsl:element name="a">
									<xsl:attribute name="name">
                      					<xsl:text>list</xsl:text>
                      					<xsl:value-of select="./@key"/>                      					
                      				</xsl:attribute>
									<xsl:attribute name="href">
                      					<xsl:text>#spec</xsl:text>
                      					<xsl:value-of select="./@key"/> 
                      				</xsl:attribute>
									<img src="pictures/element.gif" title="Parent element" border="0"/>
									<xsl:text> </xsl:text>
									<xsl:value-of select="./@key"/>
								</xsl:element>
							</dt>
							<xsl:for-each select="./lca:field">
								<xsl:sort select="./@key"/>
								<dd>
									<xsl:element name="a">
										<xsl:attribute name="name">
                      						<xsl:text>list</xsl:text>
                      						<xsl:value-of select="./@key"/>
                      					</xsl:attribute>
										<xsl:attribute name="href">
                      						<xsl:text>#spec</xsl:text>
                      						<xsl:value-of select="./@key"/>
                      					</xsl:attribute>
										<img src="pictures/attribute.gif" title="Field" border="0"/>
										<xsl:text/>
										<xsl:value-of select="./@key"/>
									</xsl:element>
								</dd>
							</xsl:for-each>
						</xsl:for-each>
			</dl>
				</p>
				<p/>
				<xsl:for-each select="/lca:mappingDoc/lca:parentElement">
					<xsl:sort select="./@key"/>
					<div class="styleDiv">
						<table width="100%" cellpadding="1" cellspacing="1" class="style4">
							<tr border="1">
								<td>
									<p><span class="style3">
										<xsl:element name="a">
										  <xsl:attribute name="name">                   						  </xsl:attribute>
										</xsl:element>
									</span>
									  <xsl:element name="a">
									    <xsl:attribute name="name">
									      <xsl:text/>
								        </xsl:attribute>
								      </xsl:element>
									  <span class="style12">
									  <xsl:element name="a">
											  <xsl:attribute name="name">
                      						      <xsl:text>spec</xsl:text>
                      						      <xsl:value-of select="./@key"/>
               					        </xsl:attribute>
										  <xsl:attribute name="href">
                      						  <xsl:text>#list</xsl:text>
                      						  <xsl:value-of select="./@key"/>
                   					      </xsl:attribute>
										 <xsl:text>Parent name: </xsl:text>
								      </xsl:element>
									  <xsl:element name="a">										  <xsl:value-of select="./@name">
									    </xsl:value-of>
								      </xsl:element>
									  </span></p>
									<table width="100%" border="0">
										<tr>
											<td width="10%">
												<xsl:text>Key</xsl:text>
											</td>
											<td width="90%">
												<xsl:value-of select="./@key"/>
											</td>
										</tr>
										<tr>
											<td width="10%">
												<xsl:text>Requirement</xsl:text>
											</td>
											<td width="90%">
												<xsl:value-of select="./@requirement"/>
											</td>
										</tr>
										<tr>
											<td width="10%">
												<xsl:text>Occurrence</xsl:text>
											</td>
											<td width="90%">
												<xsl:value-of select="./@occurrence"/>
											</td>
										</tr>
										<tr>
											<td width="10%"/>
											<td width="90%">
												<xsl:text>Reference to code </xsl:text>
												<xsl:element name="a">
													<xsl:attribute name="href">
														<xsl:text>resources/</xsl:text>
														<xsl:value-of select="$sourceFormat">
														</xsl:value-of>
														<xsl:text>_to_</xsl:text>
														<xsl:value-of select="$targetFormat">
														</xsl:value-of>
														<xsl:text>.html#</xsl:text>
														<xsl:value-of select="./@conversionKey">
														</xsl:value-of>
													</xsl:attribute>
													<xsl:element name="img">
														<xsl:attribute name="src">
															<xsl:text>pictures/goto_source.gif</xsl:text>
														</xsl:attribute>
														<xsl:attribute name="border">
															<xsl:text>0</xsl:text>
														</xsl:attribute>
													</xsl:element>
												</xsl:element>
											</td>
										</tr>
										<tr>
											<td width="10%">
												<br/>
											</td>
											<td width="90%"/>
										</tr>
										<tr>
											<td colspan="2">
												<!-- parent picture -->
												<div align="center">
													<xsl:element name="img">
														<xsl:attribute name="src">
															<xsl:value-of select="./@pathToPicture"/>
														</xsl:attribute>
														<xsl:attribute name="title">
															<xsl:text>Element specification</xsl:text>
														</xsl:attribute>
														<xsl:attribute name="alt">
															<xsl:value-of select="./@pathToPicture"/>
														</xsl:attribute>
													</xsl:element>
												</div>
											</td>
										</tr>
									</table>
							  </td>
							</tr>
							<tr border="1">
								<td>
								  <p class="style10">
									  <xsl:text>Fields</xsl:text>
								  </p>
									<xsl:for-each select="./lca:field">
										<table width="100%" border="0">
											<tr>
												<td width="10%"/>
												<td>
													<div class="styleDiv">
														<table width="100%" border="0">
															<tr bgcolor="#D3D6BD">
																<td colspan="2">
																	<xsl:text>Field	summary</xsl:text>
															  </td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Name</xsl:text>
																</td>
																<td width="75%">
																	<xsl:element name="a">
																		<xsl:attribute name="name">
								                      						<xsl:text>spec</xsl:text>
								                      						<xsl:value-of select="./@key"/>
								                      					</xsl:attribute>
																		<xsl:attribute name="href">
								                      						<xsl:text>#list</xsl:text>
								                      						<xsl:value-of select="./@key"/>
								                      					</xsl:attribute>
																		<xsl:value-of select="./@name"/>
																	</xsl:element>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Key</xsl:text>
																</td>
																<td width="75%">
																	<xsl:value-of select="./@key"/>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Requirement</xsl:text>
																</td>
																<td width="75%">
																	<xsl:value-of select="./@requirement"/>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Occurrence</xsl:text>
																</td>
																<td width="75%">
																	<xsl:value-of select="./@occurrence"/>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Enumeration or pattern</xsl:text>
																</td>
																<td width="75%">
																	<xsl:value-of select="./@isEnumerationOrPattern"/>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Data type</xsl:text>
																</td>
																<td width="75%">
																	<xsl:value-of select="./@dataType"/>
																</td>
															</tr>
															<xsl:if test="./@fieldLength">
																<tr>
																	<td width="25%">
																		<xsl:text>Field length</xsl:text>
																	</td>
																	<td width="75%">
																		<xsl:value-of select="./@fieldLength"/>
																	</td>
																</tr>
															</xsl:if>
															<tr>
																<td width="25%">
																</td>
																<td width="75%">
																	<xsl:text>Reference to code </xsl:text>
																	<xsl:element name="a">
																		<xsl:attribute name="href">
																			<xsl:text>resources/</xsl:text>
																			<xsl:value-of select="$sourceFormat">
																			</xsl:value-of>
																			<xsl:text>_to_</xsl:text>
																			<xsl:value-of select="$targetFormat">
																			</xsl:value-of>
																			<xsl:text>.html#</xsl:text>
																			<xsl:value-of select="./@conversionKey">
																			</xsl:value-of>
																		</xsl:attribute>
																		<xsl:element name="img">
																			<xsl:attribute name="src">
																				<xsl:text>pictures/goto_source.gif</xsl:text>
																			</xsl:attribute>
																			<xsl:attribute name="border">
																				<xsl:text>0</xsl:text>
																			</xsl:attribute>
																		</xsl:element>
																	</xsl:element>
																</td>
															</tr>
															<tr>
																<td width="25%">
																</td>
																<td width="75%">
																	<br/>
																</td>
															</tr>
															<tr bgcolor="#D6D3BD">
																<td colspan="2">
																	<xsl:text>Mapping summary</xsl:text>
															  </td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Requirement</xsl:text>
																</td>
																<td width="75%">
																	<xsl:choose>
																		<xsl:when test="./@requirement='optional'">
																			<xsl:element name="img">
																				<xsl:attribute name="src">
																					<xsl:text>pictures/show_resolved.gif</xsl:text>
																				</xsl:attribute>
																				<xsl:attribute name="title">
																					<xsl:text>Field is optional</xsl:text>
																				</xsl:attribute>
																			</xsl:element>
																		</xsl:when>
																		<xsl:when test="./@requirement='required'">
																			<xsl:choose>
																				<xsl:when test="./lca:mappingDescription/@required='bothRequired'">
																					<xsl:element name="img">
																						<xsl:attribute name="src">
																						<xsl:text>pictures/show_resolved.gif</xsl:text>
																						</xsl:attribute>
																						<xsl:attribute name="title">
																						<xsl:text>Field is required and a soure field is also required</xsl:text>
																						</xsl:attribute>
																					</xsl:element>
																				</xsl:when>
																				<xsl:when test="./lca:mappingDescription/@required='sourceOptional'">
																					<xsl:element name="img">
																						<xsl:attribute name="src">
																						<xsl:text>pictures/show_warn.gif</xsl:text>
																						</xsl:attribute>
																						<xsl:attribute name="title">
																						<xsl:text>Field is required, but source field(s) is (are) optional</xsl:text>
																						</xsl:attribute>
																					</xsl:element>
																				</xsl:when>
																				<xsl:when test="./lca:mappingDescription/@required='defaultValue'">
																					<xsl:element name="img">
																						<xsl:attribute name="src">
																						<xsl:text>pictures/show_problem.gif</xsl:text>
																						</xsl:attribute>
																						<xsl:attribute name="title">
																						<xsl:text>Field is required, default value is set.</xsl:text>
																						</xsl:attribute>
																					</xsl:element>
																				</xsl:when>
																			</xsl:choose>
																		</xsl:when>
																	</xsl:choose>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Occurrence</xsl:text>
																</td>
																<td width="75%">
																	<xsl:choose>
																		<xsl:when test="./lca:mappingDescription/@occurrence='oneToOne'">
																			<xsl:element name="img">
																				<xsl:attribute name="src">
																				<xsl:text>pictures/show_resolved.gif</xsl:text>
																				</xsl:attribute>
																				<xsl:attribute name="title">
																				<xsl:text>1:1 mapping</xsl:text>
																				</xsl:attribute>
																			</xsl:element>
																		</xsl:when>
																		<xsl:when test="./lca:mappingDescription/@occurrence='zeroToOne_or_nToOne'">
																			<xsl:element name="img">
																				<xsl:attribute name="src">
																				<xsl:text>pictures/show_warn.gif</xsl:text>
																				</xsl:attribute>
																				<xsl:attribute name="title">
																				<xsl:text>0:1 or n:1 mapping</xsl:text>
																				</xsl:attribute>
																			</xsl:element>
																		</xsl:when>
																	</xsl:choose>
																</td>
															</tr>
															<tr>
																<td width="25%">
																	<xsl:text>Nomenclature/Pattern</xsl:text>
																</td>
																<td width="75%">
																	<xsl:choose>
																		<xsl:when test="./@isEnumerationOrPattern='false'">
																			<xsl:element name="img">
																				<xsl:attribute name="src">
																				<xsl:text>pictures/show_resolved.gif</xsl:text>
																				</xsl:attribute>
																				<xsl:attribute name="title">
																				<xsl:text>Field is no nomenclature or pattern field</xsl:text>
																				</xsl:attribute>
																			</xsl:element>
																		</xsl:when>
																		<xsl:when test="./@isEnumerationOrPattern='true'">
																			<xsl:element name="img">
																				<xsl:attribute name="src">
																				<xsl:text>pictures/show_warn.gif</xsl:text>
																				</xsl:attribute>
																				<xsl:attribute name="title">
																				<xsl:text>Nomenclature or pattern mapping</xsl:text>
																				</xsl:attribute>
																			</xsl:element>
																		</xsl:when>
																	</xsl:choose>
																</td>
															</tr>
															<xsl:if test="./lca:mappingDescription/@dataType">
																<tr>
																	<td width="25%">
																		<xsl:text>Data type</xsl:text>
																	</td>
																	<td width="75%">
																		<xsl:choose>
																			<xsl:when test="./lca:mappingDescription/@dataType='sameTypeSameLength'">
																				<xsl:element name="img">
																					<xsl:attribute name="src">
																					<xsl:text>pictures/show_resolved.gif</xsl:text>
																					</xsl:attribute>
																					<xsl:attribute name="title">
																					<xsl:text>Data type is equal</xsl:text>

																					</xsl:attribute>
																				</xsl:element>
																			</xsl:when>
																			<xsl:when test="./lca:mappingDescription/@dataType='sameTypeDifferentLenght'">
																				<xsl:element name="img">
																					<xsl:attribute name="src">
																					<xsl:text>pictures/show_warn.gif</xsl:text>
																					</xsl:attribute>
																					<xsl:attribute name="title">
																					<xsl:text>Data type is equal, problems may occur with field length</xsl:text>
																					</xsl:attribute>
																				</xsl:element>
																			</xsl:when>
																			<xsl:when test="./lca:mappingDescription/@dataType='differentTypes'">
																				<xsl:element name="img">
																					<xsl:attribute name="src">
																					<xsl:text>pictures/show_problem.gif</xsl:text>
																					</xsl:attribute>
																					<xsl:attribute name="title">
																					<xsl:text>Different data types in source field(s)</xsl:text>
																					</xsl:attribute>
																				</xsl:element>
																			</xsl:when>
																		</xsl:choose>
																	</td>
																</tr>
															</xsl:if>
															<tr>
																<td width="25%">
																</td>
																<td width="75%">
																	<br/>
																</td>
															</tr>
															<xsl:if test="./lca:mapping/lca:sourceField">
																<tr bgcolor="#D6D3BD">
																  <td colspan="2">
																	 <span class="style10"><xsl:text>Source fields</xsl:text></span>
																  </td>
																</tr>
															</xsl:if>
															<xsl:for-each select="./lca:mapping/lca:sourceField">
																<tr>
																	<td colspan="2">
																		<table width="100%">
																			<tr>
																				<td width="25%">

																				</td>
																				<td width="75%">
																					<div class="styleDiv">
																						<table width="100%">
																							<tr>
																								<td width="25%">
																									<xsl:text>Name</xsl:text>
																								</td>
																								<td width="75%">
																									<xsl:value-of select="./@name">
																									</xsl:value-of>
																								</td>
																							</tr>
																							<tr>
																								<td width="25%">
																									<xsl:text>Key</xsl:text>
																								</td>
																								<td width="75%">
																									<xsl:value-of select="./@key">
																									</xsl:value-of>
																								</td>
																							</tr>
																							<tr>
																								<td width="25%">
																									<xsl:text>Requirement</xsl:text>
																								</td>
																								<td width="75%">
																									<xsl:value-of select="./@requirement">
																									</xsl:value-of>
																								</td>
																							</tr>
																							<tr>
																								<td width="25%">
																									<xsl:text>Occurrence</xsl:text>
																								</td>
																								<td width="75%">
																									<xsl:value-of select="./@occurrence">
																									</xsl:value-of>
																								</td>
																							</tr>
																							<tr>
																								<td width="25%">
																									<xsl:text>Enumeration or pattern</xsl:text>
																								</td>
																								<td width="75%">
																									<xsl:value-of select="./@isEnumerationOrPattern">
																									</xsl:value-of>
																								</td>
																							</tr>
																							<tr>
																								<td width="25%">
																									<xsl:text>Data type</xsl:text>

																								</td>
																								<td width="75%">
																									<xsl:value-of select="./@dataType">
																									</xsl:value-of>
																								</td>
																							</tr>
																							<xsl:if test="./@fieldLength">
																								<tr>
																									<td width="25%">
																										<xsl:text>Field length</xsl:text>
																									</td>
																									<td width="75%">
																										<xsl:value-of select="./@fieldLength">
																										</xsl:value-of>
																									</td>
																								</tr>
																							</xsl:if>
																						</table>
																					</div>
																				</td>
																			</tr>
																		</table>

																	</td>

																</tr>
															</xsl:for-each>
														</table>
													</div>
												</td>
											</tr>
										</table>
									</xsl:for-each>
								</td>
							</tr>
					  </table>

					</div>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
</xsl:transform>