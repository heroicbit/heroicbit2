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
        $redirect = $_GET['red'] ?? ci()->session->redirect ?? '';
        $disallow = ['http://','https://'];
        $redirect = str_replace($disallow,'',$redirect);


        if($redirect){
            ci()->session->set_userdata('redirect', $redirect);
        }

        // Do not show form login if user already login
        if(isLoggedIn())
        {
            // If user has logged in, redirect
            if(ci()->session->redirect)
                redirect(ci()->session->redirect);
            else
                redirect('dashboard');
        }

        // Create google sign in url
        if(setting_item('user.enable_google_signin') && setting_item('user.google_signin_client_id') && setting_item('user.google_signin_client_secret'))
        {
            $redirectUri = site_url('/auth/google');
            $client = new Google_Client();
            $client->setClientId(setting_item('user.google_signin_client_id'));
            $client->setClientSecret(setting_item('user.google_signin_client_secret'));
            $client->setRedirectUri($redirectUri);
            $client->addScope("email");
            $client->addScope("profile");
            return ['google_signin' => $client->createAuthUrl()];
        }
	}


	// This method handle POST request
	public function process()
	{
        $redirect = ci()->session->redirect ?? '';
        
        $username = ci()->input->post('username', true);
        $password = ci()->input->post('password');
        
        // Start logic
        $login = ci()->ci_auth->login($username, $password);

        if (in_array($login['status'], ['failed','inactive']))
        {
            ci()->session->set_flashdata('message', $login['message']);

            if (!empty($redirect))
                redirect('auth/login?red=' . $redirect);

            redirect('auth/login');
        }

        
        // Callback?
        if (!empty($redirect))
        {
            ci()->session->unset_userdata('redirect');
            redirect($redirect);
        }
        
        redirect('dashboard');
	}

}