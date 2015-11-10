package Processors;

import Builder.ApplicationBuilder;
import DatabaseCommand.CreateJobApplication;
import DatabaseCommand.CreateJobApplicationKeyword;
import Dictionary.KeywordDictionary;
import Models.JobApplication;
import Parser.ApplicationParser;
import Parser.PdfConverter;

public class ApplicationProcessor {
	ApplicationBuilder applicantBuilder;
	JobApplication application;
	private int userId;
	private int jobId;
	private int applicationId;
	private String filePath;
	private String content;

	public ApplicationProcessor(int userId, int jobId,int applicationId, String filePath) {
		// List<JobRequirement> requirements =
		// DatabaseCommand.getJobRequirements(jobId);

		// pdf to string
		content = PdfConverter.ToString(filePath);
		this.userId = userId;
		this.jobId = jobId;
		this.filePath = filePath;
		this.applicationId = applicationId;
	}

	public boolean run() {
	    if(this.content == null){
	        return false;
	    }
		try {
			// retrieve all related keywords
			KeywordDictionary dictionary = KeywordDictionary.getInstance();
			ApplicationParser parser = new ApplicationParser(dictionary.getKeywords().keySet(), userId, jobId, applicationId,
					filePath);
			this.application = parser.parse(content);
		} catch (Exception ex) {
			System.out.println("Something went wrong in App Proc "+ex.getMessage());
			return false;
		}
		return true;
	}

	public void save() {
		// TODO database here
	       
        CreateJobApplication createJobApplication = new CreateJobApplication(this.application);
        createJobApplication.execute();
        CreateJobApplicationKeyword createJobApplicationKeyword = new CreateJobApplicationKeyword(this.application);
        createJobApplicationKeyword.execute();
	}
}
