package Models;

import java.util.HashMap;

public class JobApplication {
	private HashMap<String, Integer> _keywords;
	private String name;
	private String email;
	private String contact;
	private String resumePath;
	private Job job;
	
	//foreign key
	private int userId;
	private int jobId;
	private int id;
	
	//Constructor
	public JobApplication(int userId, int jobId){
		this._keywords = new HashMap<String, Integer>();
		this.userId = userId;
		this.jobId = jobId;
	}
	
	
	//at this point id is still unknown
	public void addKeywordFound(String keyword){
		this._keywords.put(keyword,-1);
	}
	public HashMap<String, Integer> get_keywordsMatched() {
		return _keywords;
	}
	public void set_keywordsMatched(HashMap<String, Integer> _keywordsMatched) {
		this._keywords = _keywordsMatched;
	}



	public String getContact() {
	    if(contact == null){
	        setContact("");
	    }
		return contact;
	}



	public void setContact(String contact) {
		this.contact = contact;
	}



	public String getResumePath() {
		return resumePath;
	}



	public void setResumePath(String resumePath) {
		this.resumePath = resumePath;
	}



	public Job getJob() {
		return job;
	}



	public void setJob(Job job) {
		this.job = job;
	}



	public String getName() {
		return name;
	}



	public void setName(String name) {
		this.name = name;
	}



	public String getEmail() {
	       if(this.email == null){
	            setEmail("");
	        }
		return email;
	}



	public void setEmail(String email) {
		this.email = email;
	}
	
	public int getUserId(){
	    return this.userId;
	  
	}
	
	public int getJobId(){
	    return this.jobId;
	}


    public void setId(int applicationId) {
        // TODO Auto-generated method stub
        this.id = applicationId;
    }
    
    public int getId(){
        return this.id;
    }
}
