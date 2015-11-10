<?php

namespace parser\controllers;

use parser\library\InputValidator;

class ApplicationController extends Controller {

	public function __construct() {
	}

    public static function acceptApplicantForJob($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        $allPostVars = $app->request->post();
        $application_id = @$allPostVars['applicant']?@trim(htmlspecialchars($allPostVars['applicant'], ENT_QUOTES, 'UTF-8')):NULL;
        $action = @$allPostVars['action']?1:0;

        if ( !preg_match('/^\d+$/',$application_id) || !preg_match('/^\d+$/',$action)) {
            $app->render(400, ['Status' => 'Invalid input.' ]);
            return;
        }

        try {
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            $job  = \parser\models\Job::where('id','=',$job_id)->whereIn('id', function($query) use ($user) {
                    $query->select('job_id')->from('job_recruiters')->where('user_id','=',$user->id);
                })->first();
            //checks for permission to modify job
            if ($user && $job) {
                $application = \parser\models\Application::where('id','=',$application_id)->where('job_id','=',$job->id)->first();
                if ($application) {
                    $application->is_selected = $action;
                    $application->save();
                    echo json_encode($application, JSON_UNESCAPED_SLASHES);
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

    public static function jobsAppliedBefore() {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            if ($user) {
                $job_applications = \parser\models\Application::where('user_id','=',$user->id)->get()->toArray();
                $job_applications_buffer = [];
                foreach ($job_applications as $application) {

                    $job = \parser\models\Job::where('id','=',$application['job_id'])->first()->toArray();
                    //unset($application['score']);
                    $application['job'] = $job;
                    array_push($job_applications_buffer,$application);
                }
                if ($job_applications) {
                    echo json_encode($job_applications_buffer, JSON_UNESCAPED_SLASHES);
                } else {
                    $app->render(404, ['Status' => 'Job application not found.']);
                    return;
                }
            } else {
                throw new \Exception('User not found!');
            }

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
            return;
        }
    }

    public static function downloadResume($application_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $application  = \parser\models\Application::where('id','=',$application_id)->first();
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            if ($user && $application) {
                $job = \parser\models\Job::where('id','=', $application->job_id)->WhereIn('id', function($query) use ($user) {
                    $query->select('job_id')->from('job_recruiters')->where('user_id','=',$user->id);
                })->get();
                if (sizeof($job) > 0){
                    //authorized to download file
                    $dir = './resume-uploads/';
                    $fileName = $application->resume_path;
                    $fileUri = $dir . $fileName;

                    if (!file_exists($fileUri) || empty($fileUri)) {
                        $app->render(404, ['Status' => 'File not found.' ]);
                        return;
                    }

                    $fileUri = $dir . $fileName;
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $fileUri);
                    $extension = end(explode(".", $fileName));
                    finfo_close($finfo);
                    $fileModTime = filemtime($fileUri);
                    // Getting headers sent by the client.
                    $headers = $app->request->headers;
                    // Checking if the client is validating his cache and if it is current.
                    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $fileModTime)) {

                        // Client's cache IS current, so we just respond '304 Not Modified'.
                        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 304);
                    } else {
                        // Image not cached or cache outdated, we respond '200 OK' and output the image.
                        header('Content-Disposition: inline; filename="resume-'.preg_replace('/\ /','-',$user->name).'-'.$application->contact.'.'.$extension.'"');
                        $app->response()->header("Content-Type", $mime);
                        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 200);
                        header('Content-transfer-encoding: binary');
                        header('Content-length: '.filesize($fileUri));
                        readfile($fileUri);
                    }
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

    public static function getApplicationsForJob($job_id) {
        $app = \Slim\Slim::getInstance();

        if (!\parser\controllers\ApplicationController::isLogin()) {
            $app->render(401, ['Status' => 'Unauthorised.' ]);
            return;
        }

        try {
            $job  = \parser\models\Job::where('id','=',$job_id)->first();
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();
            if ($user && $job) {
                $job_recruiter = \parser\models\JobRecruiter::where('job_id','=',$job->id)->where('user_id','=',$user->id)->get()->toArray();
                if (sizeof($job_recruiter) > 0) {
                    //ensure access rights
                    $candidates = \parser\models\Application::where('job_id','=',$job->id)->get()->toArray();

                    echo json_encode($candidates, JSON_UNESCAPED_SLASHES);
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
				shell_exec("/usr/bin/java -jar ../../parser.jar './resume-uploads/$resume_path' '$user->id' '$job->id' '$job_application->id'");
                echo json_encode($job_application, JSON_UNESCAPED_SLASHES);
            } else {
                throw new \Exception('Error!');
            }

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }
}
