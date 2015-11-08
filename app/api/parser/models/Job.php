<?php
namespace parser\models;

class Job extends \Illuminate\Database\Eloquent\Model
{
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
    protected $appends = ['recruiters','requirements'];    
    protected $hidden = ['jobrequirements','jobrecruitersrelationship'];

    public function jobrequirements() {
        return $this->hasMany('parser\models\JobRequirement');
    }
    public function jobrecruitersrelationship() {
        return $this->hasMany('parser\models\JobRecruiter','job_id','id');
    }

    public function getRequirementsAttribute() {
        return $this->jobrequirements;
    }

    public function getRecruitersAttribute() {
        $recruiters = [];
        foreach($this->jobrecruitersrelationship as $recruiter) {
            array_push($recruiters,$recruiter->user);
        }

        return $recruiters;
    }
}