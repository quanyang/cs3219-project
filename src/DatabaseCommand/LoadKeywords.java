package DatabaseCommand;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.DriverManager;
import java.util.HashMap;
import java.util.List;

public class LoadKeywords extends LoadCommand<HashMap<String,Integer>>{
    private static HashMap<String, Integer> keywords;

    @Override
    public HashMap<String, Integer> execute(){
    	try{
	        config();
	        HashMap<String, Integer> map = new HashMap<String, Integer>();
	        map = loadData(map);
	        return map;
    	}
    	catch (Exception ex){
    		return null;
    	}
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
    
    public static HashMap<String, Integer> loadData(HashMap<String, Integer> map) {
        String keyword = "";
        int id = 0;
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("SELECT * FROM parser.keywords");
            rs = preparedStatement.executeQuery();
            while (rs.next()) {
                keyword = rs.getString("keyword");
               // System.out.print(keyword+" ");
                id = rs.getInt("id");
           //     System.out.println(id);
                map.put(keyword, new Integer(id));
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
            return map;
        }
    }
 
}
