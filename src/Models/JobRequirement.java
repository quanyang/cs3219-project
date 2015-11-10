package Models;

import javafx.util.Pair;

public class JobRequirement {
	private int id;
	private int job_id;
	private Pair<String,Integer> keyword;
	private double weightage;
	private boolean is_required;
	private boolean is_available;
	
	public JobRequirement(int jobId){
		this.job_id = jobId;
	}
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public int getJob_id() {
		return job_id;
	}
	public void setJob_id(int job_id) {
		this.job_id = job_id;
	}
	
	public double getWeightage() {
		return weightage;
	}
	public void setWeightage(double weightage) {
		this.weightage = weightage;
	}
	public boolean isIs_required() {
		return is_required;
	}
	public void setIs_required(boolean is_required) {
		this.is_required = is_required;
	}
	public boolean isIs_available() {
		return is_available;
	}
	public void setIs_available(boolean is_available) {
		this.is_available = is_available;
	}
	public Pair<String,Integer> getKeyword() {
		return keyword;
	}
	public void setKeyword(Pair<String,Integer> keyword) {
		this.keyword = keyword;
	}
	
	
}
