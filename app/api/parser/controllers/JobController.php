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

			shell_exec("java - jar parser.jar '$description' ' $job->id'");

            echo json_encode($job, JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }

    public static function updateRequirements($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        $allPostVars = $app->request->post();
        $id = @$allPostVars['id']?@trim(htmlspecialchars($allPostVars['id'], ENT_QUOTES, 'UTF-8')):NULL;
        $keyword = @$allPostVars['keyword']?@trim(htmlspecialchars($allPostVars['keyword'], ENT_QUOTES, 'UTF-8')):NULL;
        $weightage = isset($allPostVars['weightage'])?@intval($allPostVars['weightage']):NULL;
        $is_required = @$allPostVars['is_required'] === "on"?true:false;
        $is_available = @$allPostVars['is_available'] === "on"?true:false;

        if ( !preg_match('/^\d+$/',$id) || !preg_match('/^\d+(.\d{1,2})?$/',$weightage) || !InputValidator::isValidStringInput($keyword,255,0)) {
            $app->render(400, ['Status' => 'Invalid input.' ]);
            return;
        }

        try {
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            $job  = \parser\models\Job::where('id','=',$job_id)->whereIn('id', function($query) use ($user) {
                    $query->select('job_id')->from('job_recruiters')->where('user_id','=',$user->id);
                })->first();

            if ($user && $job) {
                $keyword = \parser\models\Keyword::firstOrCreate(array('keyword' => $keyword));
                $requirement = \parser\models\JobRequirement::where('id','=',$id)->where('job_id','=',$job->id)->first();
                if ($requirement) {
                    $requirement->keyword_id = $keyword->id;
                    $requirement->weightage = $weightage;
                    $requirement->is_required = $is_required;
                    $requirement->is_available = $is_available;
                    $requirement->save();
                    echo json_encode($requirement, JSON_UNESCAPED_SLASHES);
                } else {
                    $app->render(401, ['Status' => 'Unauthorised.' ]);
                    return;
                }
            } else {
                $app->render(401, ['Status' => 'Unauthorised.' ]);
                return;
            }

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
            return;
        }
    }

	public static function addRequirementsToJob($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        $allPostVars = $app->request->post();
        $keyword = @$allPostVars['keyword']?@trim(htmlspecialchars($allPostVars['keyword'], ENT_QUOTES, 'UTF-8')):NULL;
        $weightage = @$allPostVars['weightage']?@trim(htmlspecialchars($allPostVars['weightage'], ENT_QUOTES, 'UTF-8')):NULL;
        $is_required = @$allPostVars['is_required'] === "on"?true:false;
        $is_available = @$allPostVars['is_available'] === "on"?true:false;

        if ( !preg_match('/^\d+(.\d{1,2})?$/',$weightage) || !InputValidator::isValidStringInput($keyword,255,0)) {
            $app->render(400, ['Status' => 'Invalid input.' ]);
            return;
        }

        try {
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            $job  = \parser\models\Job::where('id','=',$job_id)->whereIn('id', function($query) use ($user) {
                    $query->select('job_id')->from('job_recruiters')->where('user_id','=',$user->id);
                })->first();

            if ($user && $job) {
                $keyword = \parser\models\Keyword::firstOrCreate(array('keyword' => $keyword));
                $requirement = new \parser\models\JobRequirement();
                $requirement->job_id = $job->id;
                $requirement->keyword_id = $keyword->id;
                $requirement->weightage = $weightage;
                $requirement->is_required = $is_required;
                $requirement->is_available = $is_available;
                $requirement->save();
                echo json_encode($requirement, JSON_UNESCAPED_SLASHES);
            } else {
                $app->render(401, ['Status' => 'Unauthorised.' ]);
                return;
            }

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
            return;
        }
	}

	public static function removeRequirementsToJob($job_id) {

	}
}
