package Models;

public class User {
	private int _id;
	private String _name;
	private String _email;
	private String _password;
	private String _picture_path;
	
	public User(String name, String email){
		this._name = name;
		this._email = email;
	}
	public User(){
		
	}
	
	public int getId() {
		return _id;
	}
	public void setId(int id) {
		this._id = id;
	}
	public String getName() {
		return _name;
	}
	public void setName(String name) {
		this._name = name;
	}
	public String getEmail() {
		return _email;
	}
	public void setEmail(String email) {
		this._email = email;
	}
	public String getPassword() {
		return _password;
	}
	public void setPassword(String password) {
		this._password = password;
	}
	public String getPicture_path() {
		return _picture_path;
	}
	public void setPicture_path(String picture_path) {
		this._picture_path = picture_path;
	}
	
}
