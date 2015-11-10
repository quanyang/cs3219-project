package DatabaseCommand;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.List;

import javafx.util.Pair;

public class CreateKeyword extends CreateCommand<Pair<String,Integer>>{
	private String keyword;
    
    public CreateKeyword(String keyword){
        this.keyword = keyword;
    }

    
    @Override
    public Pair<String,Integer> execute() {
        try{
            config();
            return insertData();
            
        } catch (Exception e){
        	
        }
        return null;
    }
    
    private void config() throws IOException {
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
         //   System.out.println(databaseURL);
            user = lines.get(1);
        //    System.out.println(user);

        }
        if(lines.size()==3){
            password = lines.get(2);
        //    System.out.println(password);

        }
        else if(lines.size()==2){
            password = "";
      //      System.out.println(password);

        }
        else{
            System.out.println("Configure file is wrong");
        }
    }
    
    public Pair<String, Integer>insertData() {
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("INSERT INTO parser.keywords (keyword, keyword_category_id) VALUES (?, ?);", PreparedStatement.RETURN_GENERATED_KEYS);
            preparedStatement.setString(1, keyword);
            //TODO this is category ID
            preparedStatement.setInt(2, 1);

            preparedStatement.executeUpdate();
            ResultSet rs =preparedStatement.getGeneratedKeys() ;
            if(rs.next()) {
                String i = rs.getString(1);
            //    System.out.println(i);
                return new Pair<String,Integer>("keyword",Integer.parseInt(i));
              //what you get is only a RowId ref, try make use of it anyway U could think of
              //System.out.println(rid);
            }
            
        } catch (java.sql.SQLException ex) {
            System.out.println("Database error"+ ex.getMessage());
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
        return null;
    }


    private  int getKeywordCategoryId(String keyword_category) {
        int id = 0;
        try {
            connection = DriverManager.getConnection(databaseURL, user, password);
            preparedStatement = connection.prepareStatement("SELECT * FROM parser.key_categories WHERE category = '"+ keyword_category+ "'");
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
