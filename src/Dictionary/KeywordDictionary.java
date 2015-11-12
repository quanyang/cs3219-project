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
