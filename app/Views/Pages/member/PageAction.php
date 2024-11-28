<?php namespace App\Views\Pages\member;

use App\Views\Pages\FrontAction;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PageAction extends FrontAction {

	use ResponseTrait;

	public function __construct($pagedata = [], RequestInterface $request = null, ResponseInterface $response = null, LoggerInterface $logger = null)
	{
		parent::__construct($pagedata, $request, $response, $logger);
		
		$this->data['themeURL'] = service('settings')->get('Theme.frontendThemeURL'); 
		$this->data['themePath'] = service('settings')->get('Theme.frontendThemePath'); 
		$this->data['title'] = service('settings')->get('Site.siteName');
	}

	// This method handle GET request
	public function render()
	{
		return $this->data;
	}

	// This method handle GET request via AJAX
	public function supply()
	{
		$db = $this->initDBPesantren();

		$settingQuery = $db->table('mein_options')
							->whereIn('option_group', ['site','tarbiyya'])
							->get()
							->getResultArray();
		
		if($settingQuery)
			$settingQuery = array_combine(array_column($settingQuery, 'option_name'), array_column($settingQuery, 'option_value'));

		dd($settingQuery);
		return ['tarbiyyaSetting' => $settingQuery];
	}

	// This method handle POST request
	public function process(){}

	public function initDBPesantren()
	{
		// Get header kodepesantren
        $headers = getallheaders();
        $kodePesantrenHashed = $headers['Pesantrenku-Id'] ?? $_GET['kodepesantren'] ?? null;

		if(! $kodePesantrenHashed)
			throw new \Exception('Pesantrenku-ID not set');

		$Encrypter = service('encrypter');
		$dbname = $Encrypter->decrypt(hex2bin($kodePesantrenHashed));
		
		// Use database client
		$db = db_connect();
		$db->setDatabase($dbname);

		return $db;
	}

	public function checkToken()
	{
		$headers = getallheaders();

		$token = $headers['Authorization'] ?? $this->request->getGet('authorization') ?? null;

		if(! $token) {
			$this->response->setStatusCode(401, 'Authorization token not found')->send();
			exit;
		}

		$jwt = explode(' ', $token)[1] ?? null;

		if (! $jwt) {
			$this->response->setStatusCode(401, 'Authorization token not found')->send();
			exit; 
		}
			
		try {
			$key = config('App')->jwtKey['secret'];
			$decodedToken = JWT::decode($jwt, new Key($key, 'HS256'));
		} catch (\Exception $e){
			$this->response->setStatusCode(401, 'Authorization token not found')->send();
			exit;
		}
		
		if (! $decodedToken) {				
			$this->response->setStatusCode(401, 'Authorization token not found')->send();
			exit;
		}

		return $decodedToken;
	}

}