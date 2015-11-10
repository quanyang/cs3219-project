package Builder;

import java.util.ArrayList;
import java.util.List;

import Models.Job;
import Models.JobRequirement;

public class JobBuilder {
	private int jobId;
	private List<JobRequirement> _jobRequirements;
	public JobBuilder(int jobId){
		this.jobId = jobId;
		this._jobRequirements = new ArrayList<JobRequirement>();
	}
	public void addJobRequirement(JobRequirement requirement){
		this._jobRequirements.add(requirement);
	}
	public Job build(){
        System.out.println("building job");
		Job job = new Job(jobId);
		/*****At setJobRequirement, it actually assign the weightage into it****/
		job.setJobRequirements(_jobRequirements);
		return job;
	}
}
