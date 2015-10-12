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
            'json.status' => false,
            'json.override_error' => true,
            'json.override_notfound' => true
        )));
    }

    private function addDefaultRoutes() {
        $app = $this->app;

        $app->response->headers->set('Access-Control-Allow-Origin', '*');

        //  http://relive.space/api
        $app->group('', function() use ($app) {

            //  GET: /api
            $app->get('/', function() use ($app) {
                $app->render(200, ['Status' => 'Running']);
            });
            
        });
    }
}
