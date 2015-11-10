package Models;

public class KeywordRelevance {
	private int id;
	private int firstKeywordId;
	private int secondKeywordId;
	private double relevancy;
	
	public KeywordRelevance(){
		
	}
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public int getFirstKeywordId() {
		return firstKeywordId;
	}
	public void setFirstKeywordId(int firstKeywordId) {
		this.firstKeywordId = firstKeywordId;
	}
	public int getSecondKeywordId() {
		return secondKeywordId;
	}
	public void setSecondKeywordId(int secondKeywordId) {
		this.secondKeywordId = secondKeywordId;
	}
	public double getRelevancy() {
		return relevancy;
	}
	public void setRelevancy(double relevancy) {
		this.relevancy = relevancy;
	}
}
