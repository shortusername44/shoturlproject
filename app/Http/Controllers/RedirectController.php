<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class RedirectController extends Controller
{

	public function index()
	{

		$hash = $_SERVER["REQUEST_URI"];
		$hash = explode('/', $hash);
		$hash = end($hash);
		$hash = $this->validateHash($hash);

		//  check if the hash exists in the DB
		$url = $this->checkIfHashExistsAndReturnUrl($hash);


		if(is_string($url)){
			header("Location: $url");
			die;
		}
		else{
			abort(404);
		}
	}

	/*
	 * We check if the hash exists in our DB and we return the original Url or error if the hash is not registered
	 *
	 * @param string $hash
	 * @return mixed
	 */
	public function checkIfHashExistsAndReturnUrl($hash) {

		$objectUrl = $this->getHash($hash);
		if($objectUrl->count()){
			$dbHash = $objectUrl->first()->url_hash;
			if($hash === $dbHash){
				$url = $objectUrl->first()->url;
				return $url;
			}
		}else{
			$error[] = 1;
			return $error;
		}
	}

	/*
	 * We search the has into the DB
	 *
	 * @param string $hash
	 * @return object $result
	 */
	public function getHash($hash) {

		$result = \DB::table('urls')
		          ->select('*')
		          ->where('url_hash', $hash)
		          ->get();

		return $result;
	}

	/*
	 * Validates the hash before the search into DB
	 *
	 * @param string $str
	 * @return string $result
	 */
	public function validateHash($str) {
		$str = str_replace(' ', '', $str);
		$result = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
		return $result;
	}
}
