package DatabaseCommand;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public abstract class CreateCommand<T> implements DbCommand<T> {
    protected static String databaseURL;
    protected static String user;
    protected static String password;
    protected static Connection connection;
    protected static PreparedStatement preparedStatement;
    protected static ResultSet rs;
}
