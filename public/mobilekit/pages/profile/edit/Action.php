<?php

/** 
 * Edit profile page action
 * 
 */
class PageAction {

	public function __construct()
	{
        $this->profile_entry = config_item('entries')['user_profile'];
	}

	// This method handle get request
	public function render()
	{
		$profile_entry = $this->profile_entry;
		$profiles = ci()->ci_auth->getUser('id', ci()->session->user_id);

		return compact('profiles','profile_entry');
	}


	// This method handle POST request
	public function process()
	{
        if (! $post = ci()->input->post(null, true)) {
            redirect('profile/edit');
        }

        $profileField = array_keys($this->profile_entry['fields']);
        $profileValue = [];

        foreach($post as $p => $value){
            ci()->session->set_flashdata($p, $value);
            if(in_array($p, $profileField)){
                $profileValue[$p] = $value;
            }
        }
        
        $user_id = isLoggedIn('user_id');
        $userValue = [
            'user_id' => $user_id,
            'name' => htmlspecialchars($post['name']),
            'username' => htmlspecialchars($post['username']),
            'email' => isLoggedIn('email'),
            'password' => ci()->input->post('password'),
            'confirm_password' => ci()->input->post('confirm_password'),
            'role_id' => isLoggedIn('role_id'),
            'short_description' => $post['short_description'],
            'avatar' => $post['avatar'],
            'status' => 'active'
        ];

        $updateUser = ci()->ci_auth->updateUser(['id' => $user_id], $userValue, $profileValue);
        
        if ($updateUser['status'] == 'failed')
            ci()->session->set_flashdata('message', '<div class="alert alert-danger">'. $updateUser['message'] .'</div>');
        else
            ci()->session->set_flashdata('message', '<div class="alert alert-success">'. $updateUser['message'] .'</div>');    
        
        redirect('profile/edit');
	}

}