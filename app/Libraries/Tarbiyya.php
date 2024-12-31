<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Tarbiyya {

    /**
     * Get database name of pesantren by kodepesantren
     */
    public function initDBPesantren()
	{
		// Get header kodepesantren
        $headers = getallheaders();
        $kodePesantrenHashed = $headers['Pesantrenku-Id'] ?? $_SESSION['pesantrenID'] ?? $_GET['pesantrenID'] ?? null;

		if(! $kodePesantrenHashed){
			return false;
		}

		$Encrypter = service('encrypter');
		$dbname = $Encrypter->decrypt(hex2bin($kodePesantrenHashed));
		
		// Use database client
		$db = db_connect();
		$db->setDatabase($dbname);

		return $db;
	}

	/**
	 * Check user token
	 */
	public function checkToken()
	{
		$headers = getallheaders();
		$request = service('request');
		$response = service('response');

		$token = $headers['Authorization'] ?? $request->getGet('authorization') ?? null;

		if(! $token) {
			$response->setStatusCode(401, 'Authorization token not found')->send();
			exit;
		}

		$jwt = explode(' ', $token)[1] ?? null;

		if (! $jwt) {
			$response->setStatusCode(401, 'Authorization token not found')->send();
			exit; 
		}
			
		try {
			$key = config('App')->jwtKey['secret'];
			$decodedToken = JWT::decode($jwt, new Key($key, 'HS256'));
		} catch (\Exception $e){
			$response->setStatusCode(401, 'Authorization token not found')->send();
			exit;
		}
		
		if (! $decodedToken) {				
			$response->setStatusCode(401, 'Authorization token not found')->send();
			exit;
		}

		return $decodedToken;
	}

}