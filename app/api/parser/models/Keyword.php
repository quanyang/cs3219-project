<?php

namespace parser\models;

class Keyword extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = ['keyword'];
	protected $appends = ['category'];
	protected $hidden = ['belongsToCategory'];

	public function relevantTo() {
		return $this->hasMany('parser\models\Keyword','keyword_relevance','first_keyword','second_keyword')->withPivot('relevance');
	}

	public function belongsToCategory() {
		return $this->belongsTo('parser\models\KeywordCategory','keyword_category_id','id');
	}

	public function jobRequirements() {
		return $this->hasMany('parser\models\jobRequirement');
	}

	public function applications() {
		return $this->hasMany('parser\models\Application','application_keywords','keyword_id','application_id');
	}

	public function getCategoryAttribute() {
		return $this->belongsToCategory;
	}

}