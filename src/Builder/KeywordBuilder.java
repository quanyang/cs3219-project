package Builder;

import java.util.HashMap;
import java.util.Set;

import Dictionary.KeywordDictionary;
import javafx.util.Pair;

public class KeywordBuilder {
	public static Pair<String, Integer> build(String keyword){
		KeywordDictionary dictionary = KeywordDictionary.getInstance();
		int id = dictionary.newKeyword(keyword);
		return new Pair<String, Integer>(keyword, id);
	}
	public static HashMap<String, Integer> build(Set<String> keywords){
		KeywordDictionary dictionary = KeywordDictionary.getInstance();
		HashMap<String, Integer> dic = new HashMap<String, Integer>();
		for(String keyword : keywords){
			int keyId = dictionary.newKeyword(keyword);
			dic.put(keyword, keyId);
		}
		return dic;
	}
}
