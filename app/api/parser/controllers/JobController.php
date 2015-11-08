<?php

namespace parser\controllers;

use parser\library\InputValidator;

class JobController extends Controller {

	public function __construct() {
	}

    public static function getAllRecruitmentPosts() {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\JobController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            if ($user) {
                $jobs = \parser\models\Job::whereIn('id', function($query) use ($user) { 
                    $query->select('job_id')->from('job_recruiters')->where('user_id','=',$user->id);
                })->get();
                if ($jobs) {
                    echo json_encode($jobs, JSON_UNESCAPED_SLASHES);
                } else {
                    echo json_encode([], JSON_UNESCAPED_SLASHES);
                }
            } else {
                $app->render(500, ['Status' => 'An error occured.']);
                return;
            }
        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occured.']);
            return;
        }
    }

    public static function getAllJobs() {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\JobController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            if ($user) {
                $jobs = \parser\models\Job::where('is_available','=','1')->whereNotIn('id', function($query) use ($user) { 
                    $query->select('job_id')->from('job_recruiters')->where('user_id','=',$user->id);
                })->whereNotIn('id', function($query) use ($user) { 
                    $query->select('job_id')->from('applications')->where('user_id','=',$user->id);
                })->get();
                if ($jobs) {
                    echo json_encode($jobs, JSON_UNESCAPED_SLASHES);
                } else {
                    echo json_encode([], JSON_UNESCAPED_SLASHES);
                }
            } else {
                $app->render(500, ['Status' => 'An error occured.']);
                return;
            }
        } catch (\Exception $e) {
            print $e;
            $app->render(500, ['Status' => 'An error occured.']);
            return;
        }
    }

	public static function getRequirementsForJob($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\JobController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $jobs = \parser\models\Job::where('id','=',$job_id)->first();
            if ($jobs) {
                echo json_encode($jobs, JSON_UNESCAPED_SLASHES);
            } else {
                $app->render(404, ['Status' => 'Job not found.']);
            }
        } catch (\Exception $e) {
            print $e;
            $app->render(500, ['Status' => 'An error occured.']);
        }
	}

    public static function isLogin() {
        return (isset($_SESSION['email']) && isset($_SESSION['name']));
    }

    public static function createNewJob() {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\JobController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        $allPostVars = $app->request->post();
        $job_title = @$allPostVars['job_title']?@trim(htmlspecialchars($allPostVars['job_title'], ENT_QUOTES, 'UTF-8')):NULL;
        $company_name = @$allPostVars['company_name']?@trim(htmlspecialchars($allPostVars['company_name'], ENT_QUOTES, 'UTF-8')):NULL;
        $description = @$allPostVars['description']?@trim(htmlspecialchars($allPostVars['description'], ENT_QUOTES, 'UTF-8')):NULL;
        $minimum_score = @$allPostVars['minimum_score']?@trim(htmlspecialchars($allPostVars['minimum_score'], ENT_QUOTES, 'UTF-8')):NULL;

        $description = preg_replace("/(\r?\n)+/s","<br/>",$description);

        if ( (intval($minimum_score) > 100 && intval($minimum_score) < 0) || !InputValidator::isValidStringInput($job_title,255,0) || !InputValidator::isValidStringInput($company_name,255,0) || !InputValidator::isValidStringInput($description,5000,0)) {
            $app->render(400, ['Status' => 'Invalid input.' ]);
            return;
        }
        try {
            $job = new \parser\models\Job();
            $job->job_title = $job_title;
            $job->company_name = $company_name;
            $job->description = $description;
            $job->minimum = intval($minimum_score);
            $job->is_available = 1;
            $job->save();

            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();

            $jobrecruiter = new \parser\models\JobRecruiter();
            $jobrecruiter->job_id = $job->id;
            $jobrecruiter->user_id = $user->id;
            $jobrecruiter->save();

            echo json_encode($job, JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }

	public static function addRequirementsToJob($job_id) {

	}

	public static function removeRequirementsToJob($job_id) {
		
	}
}