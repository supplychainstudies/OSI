<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:transform version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes"/>
<xsl:template match="/">
	<html>
	  <body>
		<h2>Stuff</h2>	
		<h3><xsl:value-of select="mappingDoc/@TargetFormat"/></h3>

</body>
</html>
</xsl:template>

</xsl:transform>