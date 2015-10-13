<?php

namespace parser\library;

class InputValidator {

	public static function isValidStringInput($input,$max_length=255,$min_length=0) {
		return !(is_null($input)||empty($input)||strlen($input) > $max_length||strlen($input) < $min_length);
	}

	public static function isValidEmail($input) {
		return filter_var($input, FILTER_VALIDATE_EMAIL);
	}

	

}