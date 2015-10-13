<?php

namespace parser\controllers;

use parser\library\InputValidator;

class UserController extends Controller {

	public function __construct() {

	}

	

	public static function login() {
		$app = \Slim\Slim::getInstance();

		$allPostVars = $app->request->post();
		$email = @$allPostVars['email']?@trim(htmlspecialchars($allPostVars['email'], ENT_QUOTES, 'UTF-8')):NULL;
		$password = @$allPostVars['password']?@trim($allPostVars['password']):NULL;

		if ( !InputValidator::isValidStringInput($email,255,0) || !InputValidator::isValidStringInput($password,60,8)) {
			$app->render(400, ['Status' => 'Invalid input.' ]);
			return;
		}
		try {
			$userExists = \parser\models\User::where('email','=',$email)->where('password','=',md5($password))->first();
			if ($userExists) {
				$app->render(200, ['Status' => 'Login success.']);
				$_SESSION['email'] = $userExists->email;
				$_SESSION['name'] = $userExists->name;
			} else {
				$app->render(400, ['Status' => 'Login failed. Incorrect email or password.' ]);
			}
		} catch (\Exception $e) {
			$app->render(500, ['Status' => 'An error occurred.' ]);
		}		
	}


	public static function getUser() {
		$app = \Slim\Slim::getInstance();

		$allGetVars = $app->request->get();
		$email = @$allGetVars['email']?@trim($allGetVars['email']):NULL;

		if (!InputValidator::isValidStringInput($email,255,0) || !InputValidator::isValidEmail($email)) {
			$app->render(400, ['Status' => 'Invalid input.' ]);
			return;
		}

		try {
			$user = \parser\models\User::where('email','=',$email)->first();
			if ($user) {
				echo json_encode($user, JSON_UNESCAPED_SLASHES);
			} else {
				$app->render(404, ['Status' => 'User not found.']);
			}
		} catch (\Exception $e) {
			$app->render(500, ['Status' => 'An error occured.']);
		}
	}

	public static function createUser() {
		$app = \Slim\Slim::getInstance();
		//$jsonData = $app->request->getBody();
		//$allPostVars = json_decode($jsonData,true);
		$allPostVars = $app->request->post();
		$name = @$allPostVars['name']?@trim(htmlspecialchars($allPostVars['name'], ENT_QUOTES, 'UTF-8')):NULL;
		$email = @$allPostVars['email']?@trim(htmlspecialchars($allPostVars['email'], ENT_QUOTES, 'UTF-8')):NULL;
		$password = @$allPostVars['password']?@trim($allPostVars['password']):NULL;
		$cfmpassword = @$allPostVars['confirm-password']?@trim($allPostVars['confirm-password']):NULL;
		if ( $password !== $cfmpassword || !InputValidator::isValidStringInput($name,255,0) || !InputValidator::isValidStringInput($email,255,0) || !InputValidator::isValidEmail($email) || !InputValidator::isValidStringInput($password,60,8) ) {
			$app->render(400, ['Status' => 'Invalid input.' ]);
			return;
		}
		try {
			$userExists = \parser\models\User::where('email','=',$email)->first();
			if ($userExists) {
				$app->render(400, ['Status' => 'A user with that email exists already.']);
			} else {
				$user = new \parser\models\User();
				$user->name = $name;
				$user->email = $email;
				$user->password = md5($password);
	            $user->save();
				echo json_encode($user, JSON_UNESCAPED_SLASHES);
			}
		} catch (\Exception $e) {
			$app->render(500, ['Status' => 'An error occurred.' ]);
		}
	}

}