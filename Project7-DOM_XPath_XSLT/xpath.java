import javax.xml.xpath.*;
import org.xml.sax.InputSource;
import org.w3c.dom.*;

public class xpath {
	
	static void eval ( String query, String document ) throws Exception {
		XPathFactory xpathFactory = XPathFactory.newInstance();
		XPath xpath = xpathFactory.newXPath();
		InputSource inputSource = new InputSource(document);
		NodeList result = (NodeList) xpath.evaluate(query,inputSource,XPathConstants.NODESET);
		for (int i = 0; i < result.getLength(); i++) {
			System.out.println(result.item(i).getTextContent());
		}
		System.out.println();
	}
	
	public static void main(String[] args) {
		try {
			System.out.println("---Titles of all MATH courses that are taught in room LIB 204---");
			eval("//root//course[subj=\"MATH\" and place//building=\"LIB\" and place//room=\"204\"]//title","src/reed.xml");
			
			System.out.println("---Instructor name who teaches MATH 412---");
			eval("//root//course[subj=\"MATH\" and crse=\"412\"]//instructor","src/reed.xml");
			
			System.out.println("---Titles of all courses taught by Wieting---");
			eval("//root//course[instructor=\"Wieting\"]//title","src/reed.xml");
		} catch (Exception e) {
			e.printStackTrace();
		}		
	}
}
