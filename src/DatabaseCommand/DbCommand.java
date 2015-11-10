package DatabaseCommand;

import java.io.IOException;

public interface DbCommand<T> {
	T execute() throws IOException;
}
