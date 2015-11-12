package Parser;

import java.util.ArrayList;
import java.util.List;
import java.util.Set;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import javafx.util.Pair;
import Builder.ApplicationBuilder;
import DatabaseCommand.LoadJobApplication;
import Dictionary.KeywordDictionary;

/**
 * This class parse the given resume into a string array
 * 
 * @author Chen Tze Cheng(A0112092W)
 *
 */
public class ApplicationParser implements IParser<ApplicationBuilder> {

	private Set<String> keywords;

	private final static String REGX_PHONE = "([\\+(]?(\\d){2,}[)]?[- \\.]?(\\d){2,}[- \\.]?(\\d){2,}[- \\.]?(\\d){2,}[- \\.]?(\\d){2,})|([\\+(]?(\\d){2,}[)]?[- \\.]?(\\d){2,}[- \\.]?(\\d){2,}[- \\.]?(\\d){2,})|([\\+(]?(\\d){2,}[)]?[- \\.]?(\\d){2,}[- \\.]?(\\d){2,})";
	private final static String REGX_EMAIL = "([a-z_0-9A-Z]+[-|\\.]?)+[a-z_0-9A-Z]@([a-z_0-9A-Z]+(-[a-z_0-9A-Z]+)?\\.)+[a-zA-Z]{2,}";
	private final static String REGX_NAME = "(Name):(.*)";
	private boolean isLinkedIn;
	private String resume_path;
	private int jobId;
	private int userId;
	private int applicationId;
	private String email;
	private String contact;

	public ApplicationParser(Set<String> keywords, int userId, int jobId, int applicationId, String resume_path) {
		this.keywords = keywords;
		this.jobId = jobId;
		this.userId = userId;
		this.resume_path = resume_path;
		this.applicationId = applicationId;
	}

	@Override
	public ApplicationBuilder parse(ApplicationBuilder builder, String content) {
		LoadJobApplication loadApplication = new LoadJobApplication(applicationId);
		Pair<String, String> emailContact = loadApplication.execute();
		this.email = emailContact.getKey();
		this.contact = emailContact.getValue();
		isLinkedIn = LinkedInCVParser.isLinkedInCV(content);
		if (isLinkedIn) {
			LinkedInCVParser parser = new LinkedInCVParser(setNameFromContent(content), setEmailFromContent(content),
					setPhoneFromContent(content), this.userId, this.jobId, this.applicationId, this.resume_path);
			// System.out.println("sending to LinkedIn parser");
			return parser.parse(builder, content);
		} else {
			builder = new ApplicationBuilder(setNameFromContent(content), setEmailFromContent(content),
					setPhoneFromContent(content), this.userId, this.jobId, this.applicationId, this.resume_path);
			List<String> keywords = parseKeywords(content);

			for (String k : keywords) {
				String temp = k;
				//System.out.println(temp.substring(0, 4));
				int kw = KeywordDictionary.getInstance().newKeyword(k);
				if (kw != -1) {
					builder.addKeywordFound(new Pair<String, Integer>(k, kw));
				} else {
					System.out.println("Something wrong when calling newKeyword");
				}
			}
		}
		return builder;
	}

	private List<String> parseKeywords(String resumeLines) {
		List<String> matchedKeywords = new ArrayList<String>();

		for (String keyword : keywords) {
			int size = resumeLines.length();
			resumeLines = replaceString(resumeLines, keyword);
			if (size > resumeLines.length()) {
				matchedKeywords.add(keyword);
				// System.out.println("in app parser "+keyword);
			}
		}

		return matchedKeywords;
	}

	private String replaceString(String string1, String string2) {
		String patternString = "\\b(" + string2 + ")\\b";
		Pattern pattern = Pattern.compile(patternString);
		Matcher matcher = pattern.matcher(string1);

		if (matcher.find()) {
			String result = string1.substring(0, matcher.start())
					+ string1.substring(matcher.start() + string2.length());
			return result;
		}
		return string1;
	}

	private String setNameFromContent(String content) {
		String name = "";
		String[] words = null;
		words = content.split("\n");
		boolean gotName = false;
		for (int i = 0; i < words.length; i++) {
			if (setValue(REGX_NAME, words[i]).length() != 0) {
				name = setValue(REGX_NAME, words[i]);
				name = name.substring(5);
				gotName = true;
			}
		}
		if (!gotName) {
			if (isLinkedIn)
				name = words[1];
			else
				name = words[0];
		}
		System.out.println("Name: " + name);
		return name;
	}

	private String setValue(String reg, String con) {
		// String[] ans = pcre.preg_match_all(reg,con);
		Pattern p = Pattern.compile(reg, Pattern.CASE_INSENSITIVE);
		Matcher m = p.matcher(con);
		String res = "";
		if (m.find()) {
			res = m.group(0);
		}
		return res;
	}

	/**
	 * get Email
	 * 
	 * @param content
	 * @return
	 */
	private String setEmailFromContent(String content) {
		if (this.email != "") {
			return this.email;
		}
		// for(int i=0;i<content.length;++i){
		String email;
		email = setValue(REGX_EMAIL, content);
		if (!email.isEmpty()) {
			System.out.println("Email: " + email);
			return email;
		}
		// }
		return null;
	}

	/**
	 * get Phone
	 * 
	 * @param content
	 * @return
	 */
	public String setPhoneFromContent(String content) {
		if (this.contact != "") {
			return this.contact;
		}
		// for(int i=0;i<content.length;++i){
		String phone;
		phone = setValue(REGX_PHONE, content);
		if (!phone.isEmpty()) {
			System.out.println("Phone: " + phone);
			return phone;
		}
		// }
		return null;
	}

}
