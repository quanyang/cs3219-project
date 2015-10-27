<?php

namespace parser\models;

class JobRequirement extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'job_requirements';
	public $timestamps = true;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $appends = ['keyword'];
	protected $hidden = ['related_keyword'];

	public function job() {
		return $this->belongsTo('parser\models\Job');
	}

	public function related_keyword() {
		return $this->belongsTo('parser\models\Keyword','id','id');
	}

	public function getKeywordAttribute() {
		return $this->related_keyword;
	}

}