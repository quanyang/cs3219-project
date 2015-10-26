<?php

namespace parser\models;

class Keyword extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'keyword';
	public $timestamps = true;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function relevantTo() {
		return $this->hasMany('parser\models\Keyword','keyword_relevance','first_keyword','second_keyword')->withPivot('relevance');
	}

	public function category() {
		return $this->belongsTo('parser\models\KeywordCategory','keyword_category_id','id');
	}

	public function jobRequirements() {
		return $this->hasMany('parser\models\jobRequirement');
	}

	public function applications() {
		return $this->hasMany('parser\models\Application','application_keywords','keyword_id','application_id');
	}

}