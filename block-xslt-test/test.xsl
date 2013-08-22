<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="KOI8-R" indent="yes"/>

	<xsl:template match="Tree">
		<xsl:apply-templates select="child_list"/>
	</xsl:template>
    
	<xsl:template match="child_list">
		<ul>
			<xsl:for-each select="Node">
				<li>
					<a>
						<xsl:attribute name="href">
							<xsl:value-of select="id"/>
						</xsl:attribute>
						<xsl:value-of select="name"/>
					</a>
					<xsl:apply-templates select="child_list[ node()]"/>
				</li>
			</xsl:for-each>
		</ul>
	</xsl:template>

</xsl:stylesheet>
