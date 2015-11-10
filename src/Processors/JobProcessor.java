package Processors;

import DatabaseCommand.CreateJobRequirement;
import Dictionary.KeywordDictionary;
import Models.Job;
import Parser.JobParser;

public class JobProcessor {
	private Job _job;
	
	public JobProcessor(int jobId, String descriptions){
		KeywordDictionary dictionary = KeywordDictionary.getInstance();
		//build job
		JobParser parser = new JobParser(dictionary.getKeywords(),jobId);
		this._job = (parser.parse(descriptions));
	}
	public void save(){
		//TODO database here
	    System.out.println("saving");
	    CreateJobRequirement createJobrequirement = new CreateJobRequirement(this._job);
	    createJobrequirement.execute();
	};
}
