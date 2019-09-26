Description:

The goal of this project is to learn DOM, XPath, and XSLT to query XML data.

Project Requirements:

You will evaluate DOM, XPath, and XSLT over XML data that represents courses from Reed College, available at reed.xml with DTD reed.dtd. More specifically:
1) Write a plain Java program dom.java that uses the Java DOM API over the XML data in reed.xml to print the titles of all MATH courses that are taught in room LIB 204
2) Write a plain Java program xpath.java that evaluates the following XPath queries over the XML data in reed.xml:
  a. Print the titles of all MATH courses that are taught in room LIB 204
  b. Print the instructor name who teaches MATH 412
  c. Print the titles of all courses taught by Wieting
3) Write an XSLT program math.xsl to display all MATH courses in Reed College by transforming the XML file reed.xml to XHTML using XSLT. Your XHTML must contain a table, where each table row is a Math course. Modify the Java program xslt.java to test your XSLT and then load the resulting html output file on your web browser.
