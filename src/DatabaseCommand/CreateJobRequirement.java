package DatabaseCommand;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.DriverManager;
import java.util.List;

import Models.Job;
import Models.JobRequirement;

public class CreateJobRequirement extends CreateCommand<Boolean> {

	private static Job _job;
    
	public CreateJobRequirement(Job job){
		this._job = job;
	}
	@Override
	public Boolean execute() {
		// TODO Auto-generated method stub
	    try{
	        config();
	        List<JobRequirement> jobRequirements = _job.getJobRequirements();
	        for(JobRequirement jobRequirement : jobRequirements){
	            int keywordId = jobRequirement.getKeyword().getValue();
	            insertData(keywordId,jobRequirement.isIs_required(),jobRequirement.isIs_available(),jobRequirement.getWeightage());
	        }
	        return true;
	    } catch (Exception e){
	        return null;
	    }
	}
	
    private static void config() throws IOException {
        String path = System.getProperty("user.dir");
        String fileName = "config.txt";
        path = path + "\\";
        path = path + fileName;
        System.out.println(path);
        List<String> lines = null;
        try{
            lines = Files.readAllLines(Paths.get(fileName));
        } catch (Exception e) {
            System.out.println("config file not exist");
        }
        if(lines.size()>=2){
            databaseURL = lines.get(0);
            user = lines.get(1);
        }
        if(lines.size()==3){
            password = lines.get(2);
        }
        else if(lines.size()==2){
            password = "";
        }
        else{
            System.out.println("Configure file is wrong");
        }
    }
	
    public static void insertData(int keyWordId, boolean isRequired, boolean isAvailable, double weightage) {
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("INSERT INTO parser.job_requirements (job_id, keyword_id, is_required, is_available, weightage) VALUES (?, ?, ?, ?, ?);");
            preparedStatement.setInt(1, _job.getId());
            preparedStatement.setInt(2, keyWordId);
            preparedStatement.setInt(3, (isRequired) ? 1 : 0);
            preparedStatement.setInt(4, (isAvailable) ? 1 : 0);
            preparedStatement.setDouble(5, weightage);
            preparedStatement.executeUpdate();
            
        } catch (java.sql.SQLException ex) {
            System.out.println(ex.getMessage());
        } finally {
            try {
                preparedStatement.close();
            } catch (java.sql.SQLException ex) {
                preparedStatement = null;
            }
            try {
                connection.close();
            } catch (java.sql.SQLException ex) {
                connection = null;
            }
        }
    }
}
