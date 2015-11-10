package Builder;

import Dictionary.KeywordDictionary;

public class KeywordBuilder {
	public static void build(String keyword){
		KeywordDictionary dictionary = KeywordDictionary.getInstance();
		dictionary.newKeyword(keyword);
	}
}
