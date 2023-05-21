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
        $token = ci()->uri->segment(4);

        $isValid = ci()->ci_auth->validateActivationToken($token);

        if(!$isValid) show_404();
	}


	// This method handle POST request
	public function process()
	{
        $token = ci()->uri->segment(4);
        $otp = ci()->input->post('otp', true);
        $user = ci()->ci_auth->validateOTP($token, $otp);
        if(!$user){
            ci()->session->set_flashdata('message', '<div class="alert alert-danger">Kode OTP salah atau tidak valid.</div>');
            redirect(getenv('HTTP_REFERER'));
        }

        $password = ci()->input->post('password', true);
        $confirm_password = ci()->input->post('confirm_password', true);

        $reset = ci()->ci_auth->changePassword($token, $password, $confirm_password);
        if($reset['status'] == 'success'){
            ci()->session->set_flashdata('message', '<div class="alert alert-success">'.$reset['message'].'</div>');
            redirect('auth/login');
        } else {
            ci()->session->set_flashdata('message', '<div class="alert alert-danger">'.$reset['message'].'</div>');
            redirect(getenv('HTTP_REFERER'));
        }

	}

}