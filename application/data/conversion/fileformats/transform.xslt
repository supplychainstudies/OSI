<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
    <xsl:for-each select="mappingdoc">
		\n-<xsl:value-of select="mappingdoc"/>-\n
    	<xsl:for-each select="mappingdoc">		
     		-<xsl:value-of select="field"/>-\n
    	</xsl:for-each>
    </xsl:for-each>
</xsl:template>

</xsl:stylesheet>