import javax.xml.parsers.*;
import org.w3c.dom.*;
import javax.xml.transform.*;
import javax.xml.transform.dom.*;
import javax.xml.transform.stream.*;
import java.io.*;


class xslt {
    public static void main ( String argv[] ) throws Exception {
//    	File stylesheet = new File("src/test/xslt-example.xsl");
//    	File xmlfile  = new File("src/test/cs.xml");
		File stylesheet = new File("src/math.xsl");
		File xmlfile  = new File("src/reed.xml");
		
		OutputStream htmlFile = new FileOutputStream("math.html");
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		DocumentBuilder db = dbf.newDocumentBuilder();
		Document document = db.parse(xmlfile);
		StreamSource stylesource = new StreamSource(stylesheet);
		TransformerFactory tf = TransformerFactory.newInstance();
		Transformer transformer = tf.newTransformer(stylesource);
		DOMSource source = new DOMSource(document);
		StreamResult result = new StreamResult(htmlFile);
		transformer.transform(source,result);
    }
}