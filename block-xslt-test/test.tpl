<html>
<head>
<title>Smarty XSLT block plugin</title>
</head>
<body>

<h1>Smarty XSLT block plugin</h1>


<h2>Using data from arrays</h2>

{xslt from=$ARRAY_DATA root_tag="child_list" default_tag="Node" assign_xml="DEBUG_XML_ARRAY"}
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="KOI8-R" indent="yes"/>
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
{/xslt}

<h3>Intermediate XML</h3>

<pre>{$DEBUG_XML_ARRAY|escape}</pre>



<h2>Using data from classes</h2>

{xslt from=$CLASS_DATA assign_xml="DEBUG_XML_CLASS"}
{include file="test.xsl"}
{/xslt}

<h3>Intermediate XML</h3>

<pre>{$DEBUG_XML_CLASS|escape}</pre>



<h2>Using data from XML text</h2>

{xslt from_xml=$XML_DATA}
{include file="test.xsl"}
{/xslt}

<h3>Source XML</h3>

<pre>{$XML_DATA|escape}</pre>

</body>
</html>
