<?php

namespace parser\models;

class JobRecruiter extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'job_recruiter';
	public $timestamps = true;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
}