<?php

namespace parser\models;

class Application extends \Illuminate\Database\Eloquent\Model {
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

	public function applicant() {
		return $this->hasOne('parser\models\User');
	}

	public function resumeKeywords() {
		return $this->hasMany('parser\models\Keyword','application_keywords','application_id','keyword_id');
	}

	

}