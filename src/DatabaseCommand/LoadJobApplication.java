package DatabaseCommand;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.DriverManager;
import java.util.List;

import javafx.util.Pair;

public class LoadJobApplication extends LoadCommand <Pair<String,String>>{

    private static int applicationId;
    public LoadJobApplication(int id){
        this.applicationId = id;
    }
    
    @Override
    public Pair<String,String> execute() {
        // TODO Auto-generated method stub
        return loadEmailContact();
    }
    
    private static void config() throws IOException {
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
    
    public static Pair<String,String> loadEmailContact() {
        String email = "";
        String contact = "";
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("SELECT * FROM parser.applications WHERE id = "+ applicationId);
            rs = preparedStatement.executeQuery();
            while (rs.next()) {
                email = rs.getString("email");
               // System.out.print(keyword+" ");
                contact = rs.getString("contact");
           //     System.out.println(id);
            }
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
            return new Pair<String,String>(email,contact);
        }
    }
    
    
}