<?php

namespace parser\models;

class User extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public $timestamps = true;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public $hidden = ['password'];

	public function applications() {
		return $this->hasMany('parser\models\Application','user_id','id');
	}

	public function recruiterFor() {
		return $this->hasMany('parser\models\Job','job_recruiter','user_id','job_id');
	}

	

}