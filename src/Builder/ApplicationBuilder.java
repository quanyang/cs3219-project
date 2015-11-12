package Builder;

import java.util.HashMap;

import javafx.util.Pair;
import Models.JobApplication;

public class ApplicationBuilder implements IBuilder<JobApplication> {
	private String _name;
	private String _email;
	private String _contact;
	private HashMap<String, Integer> keywords;
	
	//given by the program
	private int userId;
	private int jobId;
	private int applicationId;
	private String resume_path;
	
    public ApplicationBuilder(String name, String email, String contact, int userId, int jobId, int applicationId, String resume_path){
      _name = name;
      _email = email;
      _contact = contact;
      this.keywords = new HashMap<String, Integer>();
      
      this.userId = userId;
      this.jobId = jobId;
      this.applicationId = applicationId;
      this.resume_path = resume_path;
    }
    
    public void addKeywordFound(Pair<String, Integer> keywordFound){
    	this.keywords.put(keywordFound.getKey(), keywordFound.getValue());
    }

	@Override
	public JobApplication build() {
        JobApplication application = new JobApplication(userId, jobId);
        application.setName(_name);
        application.setEmail(_email);
        application.setContact(_contact);
        application.setResumePath(resume_path);
        application.set_keywordsMatched(keywords);
        application.setId(applicationId);
        return application;
	}
}
