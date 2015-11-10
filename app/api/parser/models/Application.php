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

	protected $appends = ['user','keywords','score','meet_requirements'];
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

	public function getMeetRequirementsAttribute() {
		$unfulfilled_requirements = \parser\models\JobRequirement::where('is_available','=',1)->where('is_required','=',1)->where('job_id','=',$this->job_id)->WhereNotIn('keyword_id', function($query) { 
			$query->select('id')->from('keywords')->whereIn('id', function($query2) {
				$query2->select('keyword_id')->from('application_keywords')->where('application_id','=',$this->id);
			});
		})->get()->toArray();

		if (sizeof($unfulfilled_requirements) == 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getScoreAttribute() {

		$totalScore = \parser\models\JobRequirement::where('job_id','=',$this->job_id)->where('is_available','=',1)->sum('weightage');

		$score = \parser\models\JobRequirement::where('is_available','=',1)->where('job_id','=',$this->job_id)->WhereIn('keyword_id', function($query) { 
			$query->select('id')->from('keywords')->whereIn('id', function($query2) {
				$query2->select('keyword_id')->from('application_keywords')->where('application_id','=',$this->id);
			});
		})->sum('weightage');

		$unfulfilled_requirements = \parser\models\JobRequirement::where('is_available','=',1)->where('job_id','=',$this->job_id)->WhereNotIn('keyword_id', function($query) { 
			$query->select('id')->from('keywords')->whereIn('id', function($query2) {
				$query2->select('keyword_id')->from('application_keywords')->where('application_id','=',$this->id);
			});
		})->get();

		$skillset = [];

		foreach($this->resumeKeywords as $keyword) {
			array_push($skillset,$keyword->keyword->id);
		}

		$edges = \parser\models\KeywordRelevance::get();
		$graph = new \parser\library\Graph();
		foreach($edges as $edge) {
			$graph->addEdge($edge->from_keyword_id,$edge->to_keyword_id,$edge->relevance);
		}

		$sssp = new \parser\library\Dijkstra($graph->output());

		foreach($unfulfilled_requirements as $requirement) {
			$min = 1;
			foreach($skillset as $skill) {
				$dist = -$sssp->shortestPath($skill,$requirement->keyword->id);
				if ($dist<$min) {
					$min = $dist;
				}
			}
			if ($min != 1) {
				$score+= $requirement->weightage * abs($min);
			}
		}

		if ($totalScore <= 0) {
			return 0;
		}
		return $score/$totalScore*100;
	}	

}