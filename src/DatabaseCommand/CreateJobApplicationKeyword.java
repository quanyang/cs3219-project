package DatabaseCommand;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.DriverManager;
import java.util.HashMap;
import java.util.List;

import Models.JobApplication;

public class CreateJobApplicationKeyword extends CreateCommand {

    
    private JobApplication application;

    public CreateJobApplicationKeyword(JobApplication application){
        this.application = application;
       
    }
    
    @Override
    public Boolean execute()  {
        try{
            config();
            insertData();
            System.out.println("storing application keyword");
            return true;
            
        } catch (Exception e){
            return null;
        }
    }
    
    private void config() throws IOException {
        String path = System.getProperty("user.dir");
        String fileName = "config.txt";
        path = path + "\\";
        path = path + fileName;
//        System.out.println(path);
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
    
    public void insertData() {
        int id = 0;
        //System.out.println("inserting data");

        try {
            id = application.getId();
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("INSERT INTO parser.application_keywords (application_id, keyword_id) VALUES (?, ?);");
            
            HashMap<String, Integer> keywordsFound =  application.get_keywordsMatched();
            preparedStatement.setInt(1, id);
           
            //TODO Not sure if this is ok
            for(Integer i : keywordsFound.values()){
            	preparedStatement.setInt(2, i);
            	preparedStatement.executeUpdate();
            }            
        } catch (java.sql.SQLException ex) {
            System.out.println("something's wrong in database " + ex.getMessage());

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
    
    private int getApplicationId(String contact, int job_id) {
        int id = 0;
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("SELECT * FROM parser.applications WHERE contact = '"+ contact+ "' AND job_id = '"+ job_id + "'");
            rs = preparedStatement.executeQuery();
            if(rs.next()){
                id = rs.getInt("id");
            }
        }catch (java.sql.SQLException ex) {    
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
            return id;
        }
    }
}
