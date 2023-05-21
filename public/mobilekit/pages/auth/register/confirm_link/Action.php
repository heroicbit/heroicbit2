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
        $token = ci()->input->get('token');

        $isValid = ci()->ci_auth->validateActivationToken($token);

        if(!$isValid) show_404();

        return ['user' => $isValid];
	}


	// This method handle POST request
	public function process()
	{
        $redirect = ci()->session->redirect ?? '';
        $token = ci()->input->get('token');
        $otp = ci()->input->post('otp', true);
        
        $activated = ci()->ci_auth->activateByOTP($token, $otp);
        if($activated['status'] == 'success'){
            ci()->ci_auth->forceLogin($activated['user_id']);
            ci()->session->set_flashdata('toastr', json_encode(['type'=>'success', 'message'=>'Akun Anda telah aktif']));

            if ($redirect) {
                ci()->session->unset_userdata('redirect');
                redirect($redirect);
            }
            
            redirect('dashboard');
        } else {
            ci()->session->set_flashdata('toastr', json_encode(['type'=>'danger', 'message'=>$activated['message']]));
            redirect(getenv('HTTP_REFERER'));
        }

	}

}