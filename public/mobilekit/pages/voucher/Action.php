<?php

class PageAction {

	public function render()
	{
		$code = ci()->input->get('code', true);

		if($code)
		{
			ci()->load->model('payment/Voucher_model');
			$result = ci()->Voucher_model->isValid($code);

			if($result['status'] == 'failed')
				ci()->session->set_flashdata('message', '<div class="alert alert-warning">'.$result['message'].'</div>');
		}

		return ['code' => $code, 'voucher' => $result['voucher'] ?? []];
	}

	public function process()
	{
		if(! $user = isLoggedIn())
			redirect('auth/login?red='.$this->uri->uri_string().'?'.$_SERVER['QUERY_STRING']);

		$code = ci()->input->post(null, true);

		ci()->load->model('payment/Voucher_model');
		$result = ci()->Voucher_model->isValid($code);

		if($result['status'] == 'failed'){
			ci()->session->set_flashdata('message', '<div class="alert alert-warning">'.$result['message'].'</div>');
			redirect('voucher?code='.$code);
		}

		ci()->Voucher_model->use($user['id'], $result['voucher']);
	}
}