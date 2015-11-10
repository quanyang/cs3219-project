package Parser;


public interface IParser<T> {
	T parse(String content);
	void postParse(T results);
}
