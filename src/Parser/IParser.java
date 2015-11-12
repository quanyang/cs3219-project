package Parser;


public interface IParser<T> {
	T parse(T builder,String content);
}
