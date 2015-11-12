package Processors;

import Builder.JobBuilder;
import DatabaseCommand.CreateJobRequirement;
import Dictionary.KeywordDictionary;
import Models.Job;
import Parser.JobParser;

public class JobProcessor implements IProcessor {
	private Job _job;
	private String descriptions;
	private int jobId;
	
	public JobProcessor(int jobId, String descriptions){
		this.descriptions = descriptions;
		this.jobId = jobId;
	}

	@Override
	public boolean process() {
		KeywordDictionary dictionary = KeywordDictionary.getInstance();
		JobParser parser = new JobParser(dictionary.getKeywords(),jobId);
		JobBuilder builder = null;
		this._job = (parser.parse(builder, descriptions)).build();
		return true;
	};
	
	public void save(){
		//TODO database here
	    System.out.println("saving");
	    CreateJobRequirement createJobrequirement = new CreateJobRequirement(this._job);
	    createJobrequirement.execute();
	}
}
