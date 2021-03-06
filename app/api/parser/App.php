<?php

namespace parser;

use parser\models;
use Slim\Slim;
use SlimJson\Middleware;
/**
 * Class App
 *
 * Main class of the REST API
 * @package relive
 */
class App {
    /**
     *  Construct a new App instance
     */
    public function __construct() {
        $this->app = new Slim();
        $this->startSession();
        $this->setupMiddleWare();
        $this->addDefaultRoutes();
    }

    /**
     *  Run the App instance
     */
    public function run() {
      $this->app->run();
  }

  private $app;

  private function startSession() {
    if(!session_id()) {
        session_start();
        date_default_timezone_set('Asia/Singapore');
    }
}

private function setupMiddleWare() {
    $this->app->contentType('application/json');
    $this->app->add(new Middleware(array(
        'json.status' => true,
        'json.override_error' => true,
        'json.override_notfound' => true
        )));
}

private function addDefaultRoutes() {
    $app = $this->app;

    $app->response->headers->set('Access-Control-Allow-Origin', '*');

        //
    $app->group('', function() use ($app) {

            //  GET: /api
        $app->get('/', function() use ($app) {
            $app->render(200, ['Status' => 'Running']);
        });

        $app->group('/user', function() use ($app) {
                // POST:/api/user
            $app->post('', 'parser\controllers\UserController::createUser');
                // GET:/api/user
            $app->get('', 'parser\controllers\UserController::getUser');
                // POST:/api/user/login
            $app->post('/login', 'parser\controllers\UserController::login');
        });

        $app->group('/application', function() use ($app) {
            $app->group('/:job_id', function() use ($app) {
                $app->post('', 'parser\controllers\ApplicationController::applyForJob');
                $app->get('', 'parser\controllers\ApplicationController::hasAppliedForJobBefore');
            });
            $app->get('', 'parser\controllers\ApplicationController::jobsAppliedBefore');
        });

        $app->group('/applicant', function() use ($app) {
            $app->group('/:job_id', function() use ($app) {
                $app->get('', 'parser\controllers\ApplicationController::getApplicationsForJob');
            }); 
        });

        $app->group('/resume', function() use ($app) {
            $app->group('/:application_id', function() use ($app) {
                $app->get('/download', 'parser\controllers\ApplicationController::downloadResume');
            });
        });

        $app->group('/recruiter', function() use($app) {
            $app->group('/job', function() use ($app) {
                $app->get('', 'parser\controllers\JobController::getAllRecruitmentPosts');
            });
        });

        $app->group('/job', function() use ($app) {
                // GET:/api/job
            $app->get('', 'parser\controllers\JobController::getAllJobs');
            $app->post('', 'parser\controllers\JobController::createNewJob');

            $app->group('/:job_id', function() use ($app) {

                $app->group('/settings', function() use ($app) {
                    $app->post('', 'parser\controllers\JobController:updateSettingsForJob');
                });

                $app->group('/applicant', function() use ($app) {
                    $app->post('', 'parser\controllers\ApplicationController::acceptApplicantForJob');
                });

                $app->group('/requirements', function() use ($app) {
                    $app->get('', 'parser\controllers\JobController::getRequirementsForJob');
                    $app->patch('', 'parser\controllers\JobController::updateRequirements');
                    $app->post('', 'parser\controllers\JobController::addRequirementsToJob');
                });
            });
        });
    });
}
}
