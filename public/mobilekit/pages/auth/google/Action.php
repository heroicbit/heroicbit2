<?php


/** 
 * Edit profile page action
 * 
 */
class PageAction {

	public function __construct()
	{

	}

	// This method handle get request
	public function render()
	{
        // init configuration
        $clientID = setting_item('user.google_signin_client_id');
        $clientSecret = setting_item('user.google_signin_client_secret');
        $redirectUri = site_url('/auth/google');

        // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);

            // get profile info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            
            if($user = ci()->ci_auth->getUser('email', $google_account_info->email, 'active')) {
                ci()->ci_auth->forceLogin($user['id']);
                redirect('dashboard');
            } else {
                $random_password = random_string();
                $user = ci()->ci_auth->insertUser([
                    'name' => $google_account_info->name,
                    'email' => $google_account_info->email,
                    'username' => str_replace(['@','_','-','.'], '', $google_account_info->email),
                    'password' => $random_password,
                    'confirm_password' => $random_password,
                    'status' => 'active',
                    'role_id' => 3,
                    'avatar' => $google_account_info->picture ?? '',
                    'url' => $google_account_info->link ?? '',
                    'short_description' => '',
                ], []);
                
                ci()->ci_auth->forceLogin($user['user_id']);
                redirect('dashboard');
            }
        }
	}


	// This method handle POST request
	public function process()
	{

    }

}