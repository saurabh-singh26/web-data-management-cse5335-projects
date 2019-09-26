<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<html>
			<body>
				<h2>Math Courses</h2>
		 		<table border="1">
		    		<tr bgcolor="#9acd32">
		      			<th>Subject</th>
		      			<th>Course</th>
		      			<th>Title</th>
		      			<th>Section</th>
		    		</tr>
				    <xsl:for-each select="root/course[subj='MATH']">
				    <tr>
				      <td><xsl:value-of select="subj"/></td>
				      <td><xsl:value-of select="crse"/></td>
				      <td><xsl:value-of select="title"/></td>
				      <td><xsl:value-of select="sect"/></td>
				    </tr>
				    </xsl:for-each>
			  	</table>
		  	</body>
		</html>
	</xsl:template>
</xsl:stylesheet>