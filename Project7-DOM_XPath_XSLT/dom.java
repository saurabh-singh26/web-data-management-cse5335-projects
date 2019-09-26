//References:
//1) https://www.tutorialspoint.com/java_xml/java_dom_parse_document.htm

import java.net.URL;
//import java.io.File;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.*;

public class dom {
//	
	public static void main(String[] args) {
		try {
			String xmlSrc = "http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml";
//			File inputFile = new File("src/reed.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
	        DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
//	        Document doc = dBuilder.parse(inputFile);
	        Document doc = dBuilder.parse((new URL(xmlSrc)).openStream());
	        doc.getDocumentElement().normalize();
	        NodeList nList = doc.getElementsByTagName("course");
	        for(int i=0;i<nList.getLength();i++) {
	        	Node courseNode = nList.item(i);
	        	Element courseElement = (Element) courseNode;
	        	String subject = courseElement.getElementsByTagName("subj").item(0).getTextContent();
	        	
	        	Element placeElement = (Element) courseElement.getElementsByTagName("place").item(0);
	        	String building = placeElement.getElementsByTagName("building").item(0).getTextContent();
	        	String room = placeElement.getElementsByTagName("room").item(0).getTextContent();
	        	if(subject.equals("MATH") && building.equals("LIB") && room.equals("204")) {
	        		System.out.println(courseElement.getElementsByTagName("title").item(0).getTextContent());
	        	}
	        }
		} catch(Exception e) {
			e.printStackTrace();
		}
	}

}
