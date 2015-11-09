<?php

namespace parser\models;

class Application extends \Illuminate\Database\Eloquent\Model {
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

	protected $appends = ['user','keywords','score'];
	protected $hidden = ['applicant'];

	public function applicant() {
		return $this->hasOne('parser\models\User','id','user_id');
	}

	public function resumeKeywords() {
		return $this->hasMany('parser\models\ApplicationKeyword','application_id','id');
	}

	public function getUserAttribute() {
		return $this->applicant;
	}

	public function getKeywordsAttribute() {
		$keywords = [];
		foreach($this->resumeKeywords as $keyword) {
			array_push($keywords,$keyword->keyword->keyword);
		}
		return $keywords;
	}

	public function getScoreAttribute() {

		$totalScore = \parser\models\JobRequirement::where('job_id','=',$this->job_id)->sum('weightage');
		$score = \parser\models\JobRequirement::where('job_id','=',$this->job_id)->WhereIn('keyword_id', function($query) { 
			$query->select('id')->from('keywords')->whereIn('id', function($query2) {
				$query2->select('keyword_id')->from('application_keywords')->where('application_id','=',$this->id);
			});
		})->sum('weightage');
		if ($totalScore <= 0) {
			return 0;
		}
		return $score/$totalScore*100;
	}	

}