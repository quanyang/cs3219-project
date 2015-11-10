package Models;

import java.util.ArrayList;
import java.util.List;

public class Job {
	private int id;
	private String job_title;
	private String company_name;
	private String description;
	private boolean isAvailable;
	private double minimum;
	private List<JobRequirement> jobRequirements;
	public Job(){
		this.jobRequirements = new ArrayList<JobRequirement>();
	}
	public Job(int id){
		this.jobRequirements = new ArrayList<JobRequirement>();
		this.id = id;
	}
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getJob_title() {
		return job_title;
	}
	public void setJob_title(String job_title) {
		this.job_title = job_title;
	}
	public String getCompany_name() {
		return company_name;
	}
	public void setCompany_name(String company_name) {
		this.company_name = company_name;
	}
	public String getDescription() {
		return description;
	}
	public void setDescription(String description) {
		this.description = description;
	}
	public boolean isAvailable() {
		return isAvailable;
	}
	public void setAvailable(boolean isAvailable) {
		this.isAvailable = isAvailable;
	}
	public double getMinimum() {
		return minimum;
	}
	public void setMinimum(double minimum) {
		this.minimum = minimum;
	}
	public List<JobRequirement> getJobRequirements() {
		return jobRequirements;
	}
	public void setJobRequirements(List<JobRequirement> jobRequirements) {
		double total = jobRequirements.size();
		for(JobRequirement jr: jobRequirements){
			jr.setWeightage(1/total);
		}
		this.jobRequirements = jobRequirements;
	}
}
