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
       
	}


	// This method handle POST request
	public function process()
	{
        $email = ci()->input->post('email', true);

        $data = ci()->ci_auth->resetPassword($email);

        if($data['status'] == 'success'){
            $user = ci()->ci_auth->getUser('email',$email);
            $this->sendOTP($user);
            redirect('auth/reset/confirm/'.$data['token']);
        } else {
            ci()->session->set_flashdata('message', '<div class="alert alert-warning">'.$data['message'].'</div>');
            redirect(getenv('HTTP_REFERER'));
        }
	}

    private function sendOTP($data)
    {
        $emailer = new App\libraries\Emailer(true);
        $emailer->to = [$data['email'], $data['name']];
        $emailer->subject = "Konfirmasi Reset Password";
        $emailer->body = ci()->load->render('pages/auth/reset/email.html', $data, true);
        $emailer->send();
    }

}