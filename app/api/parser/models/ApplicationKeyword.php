<?php

namespace parser\models;

class ApplicationKeyword extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'application_keywords';
	public $timestamps = true;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */


	public function keyword() {
		return $this->hasOne('parser\models\Keyword','id','keyword_id');
	}
}