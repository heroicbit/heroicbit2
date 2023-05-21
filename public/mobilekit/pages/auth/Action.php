<?php

class PageAction {

	public function __construct(){}

	// This method handle get request
	public function render()
	{
		$method = ci()->uri->segment(2);
		if($method && method_exists($this, $method))
			$this->$method();

        if(isLoggedIn())
            redirect('dashboard');
        else
            redirect('auth/login');
	}

	// This method handle POST request
	public function process(){}

	private function logout()
	{
		ci()->ci_auth->logout();
		redirect('auth/login');
	}

}