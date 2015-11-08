<?php

namespace parser\controllers;

use parser\library\InputValidator;

class ApplicationController extends Controller {

	public function __construct() {
	}

    public static function hasAppliedForJobBefore($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $job  = \parser\models\Job::where('id','=',$job_id)->first();
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            if ($user && $job) {
                $job_application = \parser\models\Application::where('job_id','=',$job->id)->where('user_id','=',$user->id)->first();

                if ($job_application) {
                    echo json_encode($job_application, JSON_UNESCAPED_SLASHES);
                } else {
                    $app->render(404, ['Status' => 'Job application not found.']);
                    return;
                }
            } else {
                throw new \Exception('User or Job not found!');
            }     

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
            return;
        }
    }

    public static function isLogin() {
        return (isset($_SESSION['email']) && isset($_SESSION['name']));
    }

    public static function saveResumeFromUpload() {
        $allowedExts = array("pdf", "doc", "docx");
        $file = $_FILES['resume-file'];
        $extension = end(explode(".", $file["name"]));

        if ((($file["type"] == "application/pdf") || ($file["type"] == "application/msword") || ($file["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")) && ($file["size"] < 20000000) && in_array($extension, $allowedExts) && ($file['error'] == UPLOAD_ERR_OK)) {
            $fn = md5($file['name'].time());
            $fn = $fn. '.'. $extension;
            move_uploaded_file($file['tmp_name'],'./resume-uploads/' . $fn);
            return $fn;
        } else {
            throw new \Exception('Wrong file type!');
        }
    }

    public static function applyForJob($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $allPostVars = $app->request->post();
            $resume_path =  \parser\controllers\ApplicationController::saveResumeFromUpload();
            $telephone = @$allPostVars['telephone']?@trim(htmlspecialchars($allPostVars['telephone'], ENT_QUOTES, 'UTF-8')):NULL;

            if ( !InputValidator::isValidStringInput($telephone,10,8) || !preg_match("/^[0-9]{8,10}$/",$telephone) ) {
                $app->render(400, ['Status' => 'Invalid input.' ]);
                return;
            }

            $job  = \parser\models\Job::where('id','=',$job_id)->first();
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();

            $job_application = \parser\models\Application::where('job_id','=',$job->id)->where('user_id','=',$user->id)->first();

            if ($user && $job && !$job_application) {
                $job_application = new \parser\models\Application();
                $job_application->user_id = $user->id;
                $job_application->job_id = $job->id;
                $job_application->contact = $telephone;
                $job_application->resume_path = $resume_path;
                $job_application->save();

                echo json_encode($job_application, JSON_UNESCAPED_SLASHES);
            } else {
                throw new \Exception('Error!');
            }     

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }
}