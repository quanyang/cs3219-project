package DatabaseCommand;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.List;

import Models.JobApplication;

public class CreateJobApplication  extends CreateCommand{
    private static JobApplication application;
    private static String databaseURL;
    private static String user;
    private static String password;
    static Connection connection;
    static PreparedStatement preparedStatement;
    static ResultSet rs;
    
    public CreateJobApplication(JobApplication application){
        this.application = application;
    }
    @Override
    public Boolean execute() {
        try{
            config();
            insertData();
            
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
      //  System.out.println(path);
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
    
    public static void insertData() {
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            if (application.getEmail()!= ""){
                System.out.println("no email, updating application");
                preparedStatement = connection.prepareStatement("UPDATE parser.applications SET user_id = ?, contact = ?, job_id = ?, email = ? ,name = ?, is_parsed = ? WHERE id = ?;");
                preparedStatement.setInt(1, application.getUserId());
                preparedStatement.setString(2, application.getContact());
                preparedStatement.setInt(3, application.getJobId());
                preparedStatement.setString(4, application.getEmail());
                preparedStatement.setString(5, application.getName());
                preparedStatement.setBoolean(6, true);
                preparedStatement.setInt(7, application.getId());
            }
            else{
                System.out.println("gt email, updating application");
                System.out.println(application.getName());

                preparedStatement = connection.prepareStatement("UPDATE parser.applications SET user_id = ?, contact = ?, job_id = ?, name = ?, is_parsed = ? WHERE id = ?;");
                preparedStatement.setInt(1, application.getUserId());
                preparedStatement.setString(2, application.getContact());
                preparedStatement.setInt(3, application.getJobId());
              //  preparedStatement.setString(5, application.getEmail());
                preparedStatement.setString(4, application.getName());
                preparedStatement.setBoolean(5, true);
                preparedStatement.setInt(6, application.getId());
            }
           // System.out.println(application.getId());

            preparedStatement.executeUpdate();
            
        } catch (java.sql.SQLException ex) {
            System.out.println("something's wrong in database11" + ex.getMessage());

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
