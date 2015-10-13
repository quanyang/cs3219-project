<?php

namespace parser\controllers;

class JobController extends Controller {

	public function __construct() {
	}

	public static function getRequirementsForJob($job_id) {
        $app = \Slim\Slim::getInstance();

        try {
            $jobs = \parser\models\Jobs::where('jobid','=',$job_id)->first();
            if ($jobs) {
                echo json_encode($jobs, JSON_UNESCAPED_SLASHES);
            } else {
                $app->render(404, ['Status' => 'Job not found.']);
            }
        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occured.']);
        }
	}

	public static function addRequirementsToJob($job_id) {

	}

	public static function removeRequirementsToJob($job_id) {
		
	}
}