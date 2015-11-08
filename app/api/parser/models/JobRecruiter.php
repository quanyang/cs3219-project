<?php

namespace parser\models;

class JobRecruiter extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'job_recruiters';
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function user() {
		return $this->belongsTo('parser\models\User','user_id','id');
	}

	public function job() {
		return $this->belongsTo('parser\models\Job');
	}
}