<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;




class ShorturlController extends Controller
{

	public function post()
	{
		$url = $_POST['url'];
		$result = $this->validateURL($url);

		if(!empty($result['url'])){
			$urlHash = $this->createUrlHash();
			$params = [
				'url' => $result['url'],
				'url_hash' => $urlHash
			];
			$this->insertUrl($params);

			$shorUrl =  'Your new URL is: http://localhost/urlshortener/public/'.$urlHash;
			$result['short_url'] = $shorUrl;


		}
		return json_encode($result);
	}


/*
 * Creates the hash for the short url
 *
 * @return base56 encode hash
 */
	public function createUrlHash() {
		$time = strtotime(now());
		$newtime = $time * rand(1,10);
		$newtime = $newtime % rand(10000,100000);
		return	base64_encode($newtime);
	}

	/*
	 * Get URL validation and checks if URL exists
	 *
	 * @param  string|null  $url
	 * @@return mixed
	 */
	public function validateURL ($url) {
		$error = [];
		if(!empty($url)){
			$url = trim($url, '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~');
			$urlExists = $this->getUrl($url);
			if($urlExists->count()){
				$error[] = 'URL already exists: http://localhost/urlshortener/public/'.$urlExists->first()->url_hash;
			}else{
				$file_headers = @get_headers($url);
				if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
					$error[] = 'Invalid URL';
				}
			}
		}else{
			$error[] = 'Invalid URL';
		}
		if(!empty($error)){
			$response['error'] = $error[0];
			return $response;
		}else{
			$response['url'] = $url;
			return $response;
		}
	}

/*
 * Get URL from DB in order to check if already exists
 *
 * @param string $url
 * @return mixed
 */
	public function getUrl($url) {

		$url = \DB::table('urls')
		          ->select('*')
		          ->where('url', $url)
		          ->get();

		return $url;
	}

	/*
	 * Insert new row into the DB
	 *
	 * @param array $params
	 */
	public function insertUrl($params) {

		\DB::table('urls')->insert(
				['url' => $params['url'], 'created' => now(), 'url_hash' => $params['url_hash']]

		);
	}
}
