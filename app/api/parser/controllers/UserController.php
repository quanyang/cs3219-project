<?php

namespace parser\controllers;

class UserController extends Controller {

	public function __construct() {
	}

	public static function isValidStringInput($input,$max_length=255,$min_length=0) {
		return !(is_null($input)||empty($input)||strlen($input) > $max_length||strlen($input) < $min_length);
	}

	public static function createUser() {
		$app = \Slim\Slim::getInstance();
		//$jsonData = $app->request->getBody();
		//$allPostVars = json_decode($jsonData,true);
		$allPostVars = $app->request->post();

		$name = @$allPostVars['name']?@trim($allPostVars['name']):NULL;
		$email = @$allPostVars['email']?@trim($allPostVars['email']):NULL;
		$password = @$allPostVars['password']?@trim($allPostVars['password']):NULL;

		if ( !UserController::isValidStringInput($name,255,0) || !UserController::isValidStringInput($email,255,0) || !UserController::isValidStringInput($password,60,8) ) {
			$app->render(400, ['Status' => 'Invalid input.' ]);
			return;
		}
		try {
			$user = new \parser\models\User();
			$user->name = $name;
			$user->email = $email;
			$user->password = md5($password);
            $user->save();
			echo json_encode($user, JSON_UNESCAPED_SLASHES);
		} catch (\Exception $e) {
			$app->render(500, ['Status' => 'An error occurred.' ]);
		}
	}

}