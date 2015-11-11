package Parser;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import javafx.util.Pair;
import Builder.JobBuilder;
import Models.Job;
import Models.JobRequirement;

/**
 * @author Chen Tze Cheng(A0112092W)
 *
 */
public class JobParser implements IParser<Job>{
	
	private HashMap<String, Integer> keywords;
	private int jobId;
	
	public JobParser(HashMap<String, Integer> keywords, int jobId){
		this.keywords = keywords;
		this.jobId = jobId;
	}
	
    public Job parse(String jobDescription) {
        System.out.println("inside parsing jobdescription");
        System.out.println("initializing job builder");

        JobBuilder jobBuilder = new JobBuilder(jobId);
        List<JobRequirement> jobRequirements = parseRequirement(jobDescription);
        System.out.println("adding jobderequirements");

        for(JobRequirement j : jobRequirements){
            jobBuilder.addJobRequirement(j);
        }
        Job job = jobBuilder.build();
       //postParse(requirements);
        return job;
    }
	@Override
	public void postParse(Job results) {
		// TODO Auto-generated method stub
		
	}
	
	private List<JobRequirement> parseRequirement(String jobDescription){
        System.out.println("parsing jobderequirements");
        jobDescription = jobDescription.replaceAll(",", "");
	       List<JobRequirement> requirements = new ArrayList<JobRequirement>();
	       for(String keyword: keywords.keySet()){
	    	   int size = jobDescription.length();
	           jobDescription = replaceString(jobDescription,keyword);
	           
	           if(size> jobDescription.length()){
	               System.out.println("keyword found " + keyword);

	               JobRequirement newJobRequirement = new JobRequirement(jobId);
	               System.out.println("jobderequirements found");
	               newJobRequirement.setKeyword(new Pair<String, Integer>(keyword, keywords.get(keyword)));
	               requirements.add(newJobRequirement);
	           }
	       }
	       return requirements;

	
	}
    
    private String replaceString(String string1, String string2){
        String patternString = "\\b(" + string2 + ")\\b";
        Pattern pattern = Pattern.compile(patternString, Pattern.CASE_INSENSITIVE);
        Matcher matcher = pattern.matcher(string1);
        if (matcher.find()) {
            System.out.println("matched");

            String result = string1.substring(0, matcher.start()) + string1.substring(matcher.start() + string2.length());
            return result;
        }
        return string1;
    }

}
