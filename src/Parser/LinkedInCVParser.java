package Parser;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Set;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import javafx.util.Pair;
import Builder.ApplicationBuilder;
import Dictionary.KeywordDictionary;
import Models.JobApplication;

public class LinkedInCVParser implements IParser<JobApplication> {

	final String[] headers = new String[] { "Associations", "Certifications", "Courses", "Education",
			"Honors and Awards", "Interests", "Languages", "Organizations", "Projects", "Publications",
			"Skills & Expertise", "Skill Set", "Experimental Skills", "Summary", "Volunteer Experience",
			"Volunteer experiences", "Experience", "Work experience", "Employment History" };
	final String eduPattern = "(.*), (.*), (.*) - (.*)";
	// final static String pattern = "Contact (.*) on LinkedIn";
	final static String pattern = "Contact (.*) on LinkedIn";

	private String name;
	private String email;
	private String contact;
	private String resume_path;

	private int userId;
	private int jobId;
	private int applicationId;

	// CreateKeyword createCommand;
	public LinkedInCVParser(String name, String email, String contact, int userId, int jobId, int applicationId, String resumePath) {
		// createCommand = new CreateKeyword(); //get the create keyword
		// instance
		this.name = name;
		this.email = email;
		this.contact = contact;
		this.userId = userId;
		this.jobId = jobId;
		this.resume_path = resumePath;
		this.applicationId = applicationId;
	}

	public static boolean isLinkedInCV(String content) {
		// has Contact xxxxxx on LinkedIn at the end (excluding page number)
		content = content.replaceAll("\r", "");
		String[] lines = content.split("\n");
//		System.out.println("Checking is LinkedIn");
		Pattern p = Pattern.compile(pattern);
//		System.out.println(content);

		boolean isLinkedIn = false;
		for (String line : lines) {
			Matcher m = p.matcher(line);
			isLinkedIn = isLinkedIn || m.matches();
		}
		return isLinkedIn;
		// return true;
	}

	@Override
	public JobApplication parse(String content) {
		String contentArr = removePageNumber(content);
		HashMap<String, String> sessions = breakIntoSessions(contentArr);
		Set<String> keywords = new HashSet<String>();
		// Allocate Skills & Expertise (as a string line)
		if (sessions.containsKey("Skills & Expertise")) {
			keywords.addAll(Arrays.asList(
					extractLines(sessions.get("Skills & Expertise").substring("Skills & Expertise\n".length()))));
		} else if (sessions.containsKey("Skill Set")) {
			keywords.addAll(Arrays.asList(extractLines(sessions.get("Skills Set").substring("Skills Set\n".length()))));
		}
		// Allocate Education (as a string line)
		if (sessions.containsKey("Education")) {
			Pattern p = Pattern.compile(eduPattern);
			String[] edus = sessions.get("Education").split("\\n");
			for (String line : edus) {
				Matcher m = p.matcher(line);
				if (m.matches()) {

					String degree = m.group(1);
					String course = m.group(2);
					keywords.add(degree);
					keywords.add(course);
				}
			}
		}
		ApplicationBuilder ab = new ApplicationBuilder(name, email, contact, userId, jobId ,applicationId);
		for (String keyword : keywords) {
			if (keyword != "" && keyword!= null && keyword != " ") {
				int kw = KeywordDictionary.getInstance().newKeyword(keyword);
				if (kw != -1) {
					ab.addKeywordFound(new Pair<String, Integer>(keyword, kw));
				} else {
					System.out.println("Something wrong when calling newKeyword");
				}
			} else if(keyword.substring(0, 4)== "Page" && keyword.length()== 5){
                System.out.println("Page");
            }
		}
		return ab.buildApplication(resume_path);
	}

	@Override
	public void postParse(JobApplication results) {
		// TODO Auto-generated method stub

	}

	private String removePageNumber(String content) {
		ArrayList<String> result = new ArrayList<String>();
		content = content.replaceAll("\r", "");
		content = content.replaceAll("\"\"\n", "");
		String[] lines = content.split("\n");

		Pattern p1 = Pattern.compile("Page\\d*");
		for (String line : lines) {
			Matcher m1 = p1.matcher(line);
			line = m1.replaceAll("");
			if (!line.equals("")) {
				result.add(line);
			}
		}
		String[] resultArr = new String[result.size()];
		result.toArray(resultArr);
		String finalResult = "";
		for (String str : resultArr) {
			finalResult += (str + "\n");
		}
		return finalResult;
	}

	private String[] extractLines(String content) {
		String[] lines = content.split("\\r?\\n");
		return lines;
	}

	private HashMap<String, String> breakIntoSessions(String content) {

		HashMap<String, String> cvSessions = new HashMap<String, String>();
		List<Pair<String, Integer>> indexList = new ArrayList<Pair<String, Integer>>();
		for (String header : headers) {

			int index = content.indexOf(header);
			if (index != -1) {
				indexList.add(new Pair<String, Integer>(header, index));
			}
		}
		indexList.sort(new PairComparator());

		String myHeader = null;
		int prevIndex = 0;
		for (Pair<String, Integer> pair : indexList) {
			if (myHeader == null) {
				cvSessions.put("FRONT", content.substring(0, pair.getValue()));
			} else {
				cvSessions.put(myHeader, content.substring(prevIndex, pair.getValue()));
			}
			myHeader = pair.getKey();
			prevIndex = pair.getValue();
		}
		if (myHeader != null) {
			cvSessions.put(myHeader, content.substring(prevIndex));
		}
	//	System.out.println("ComeCOme");

		//System.out.println(cvSessions.get(myHeader));
		return cvSessions;

	}
}

class PairComparator implements Comparator<Pair<String, Integer>> {

	@Override
	public int compare(Pair<String, Integer> o1, Pair<String, Integer> o2) {
		// TODO Auto-generated method stub
		return o1.getValue().compareTo(o2.getValue());
	}

}
