<?php

namespace parser\models;

class KeywordCategory extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'keyword_categories';
	public $timestamps = true;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function keywords() {
		return $this->hasMany('parser\models\Keyword');
	}

}