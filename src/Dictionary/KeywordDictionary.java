package Dictionary;

import java.util.HashMap;

import DatabaseCommand.CreateKeyword;
import DatabaseCommand.LoadKeywords;


public class KeywordDictionary {
	private static KeywordDictionary _instance;
	private HashMap<String,Integer> keywords;

	private KeywordDictionary(){
		this.keywords = new HashMap<String,Integer>();

		this.keywords = new LoadKeywords().execute();
		
	}
	
	
	public int newKeyword(String keyword){
		int keywordId = -1;
		//test keyword equals
	//	System.out.println("Executing newKeyword @ Dictionary");

		 if(keywords.containsKey(keyword)){
			 keywordId = this.keywords.get(keyword);
			//	System.out.println(keyword+" keyword is found, id="+keywordId);

		}else{
			System.out.println("");
			//Test here
			CreateKeyword ck = new CreateKeyword(keyword);
			keywordId = ck.execute().getValue();
			this.keywords.put(keyword, keywordId);
		//	System.out.println(keyword+" keyword is created, id="+keywordId);
		}
		return keywordId;
	}
	public HashMap<String,Integer> getKeywords() {
//		keywords.put("Matlab",0);
//		keywords.put("Labview",0);
//		keywords.put("Molecular Biology",0);
//		keywords.put("Medical Devices",0);
//		keywords.put("Medical Research",0);
//		keywords.put("Motion Capture",0);
//		keywords.put("Data Mining",0);
//		keywords.put("Real-time Data Acquisition ",0);
//		keywords.put("System Administration ",0);
//		keywords.put("Blackberry Enterprise Server ",0);
//		keywords.put("Image Processing",0);
//		keywords.put("Network Administration ",0);
//		keywords.put("Biomaterials",0);
//		keywords.put("Windows Server",0);
//		keywords.put("Android",0);
//		keywords.put("Software Engineering ",0);
//		keywords.put("Web Development ",0);
//		keywords.put("Eclipse",0);
//		keywords.put("Embedded  ",0);
//		keywords.put("Objective-C",0);
//		keywords.put("Object Oriented ",0);
//		keywords.put("Java",0);
//		keywords.put("Python",0);
//		keywords.put("Programming",0);
//		keywords.put("Statistics",0);
//		keywords.put("Ruby on Rails",0);
//		keywords.put("Ruby",0);
//		keywords.put("HTML",0);
//		keywords.put("HTML 5",0);
//		keywords.put("Json",0);
//		keywords.put("SOAP",0);
//		keywords.put("REST",0);
//		keywords.put("IOS",0);
//		keywords.put("SWIFT",0);
//		keywords.put("AJAX",0);
//		keywords.put("JQUERY",0);
//		keywords.put("WEB Services",0);
//		keywords.put("JavaScript",0);
//		keywords.put("NodeJs",0);
//		
//		keywords.put("Microsoft Office",0);
//		keywords.put("Microsoft Excel",0);
//		keywords.put("Microsoft Word",0);
//		keywords.put("Microsoft Powerpoint",0);
//		keywords.put("Microsoft Exchange",0);
//		keywords.put("Adobe Creative Suite",0);
//		keywords.put("Photoshop",0);
//		keywords.put("Illustrator",0);
//		keywords.put("Premium Pro",0);
//		keywords.put("Computer Software Engineering ",0);
//		keywords.put("Biomedical Engineering",0);
//		keywords.put("Computer Science",0);
		
		return keywords;
	}
	public void setKeywords(HashMap<String,Integer> keywords) {
		this.keywords = keywords;
	}
	
	public static KeywordDictionary getInstance(){
		if(_instance == null){
			_instance = new KeywordDictionary();
		}
		return _instance;
	}
}
