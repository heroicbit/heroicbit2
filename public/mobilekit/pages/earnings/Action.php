<?php

/** 
 * Home page action
 * 
 */
class PageAction {

	// This method handle get request
	public function render(){
		if(!isLoggedIn())
			redirect('affiliate');
	}


	// This method handle POST request
	public function process(){
		$referral_code = trim(ci()->input->post('referral_code', true));
		if(!$referral_code){
			ci()->session->set_flashdata('message', '<div class="alert alert-warning">Silakan tuliskan kode referral yang Anda ingin gunakan.</div>');
			redirect('earnings');
		}

		$Referral = setup_entry_model('referral_user');
		$referralUser = $Referral->where('referral_code', $referral_code)->get();
		if($referralUser){
			ci()->session->set_flashdata('message', '<div class="alert alert-warning">Kode referral sudah digunakan oleh user lain. Silakan buat kode referral yang lain.</div>');
			redirect('earnings');
		}

		// Get parent referral
		$currentUser = isLoggedIn();
		$parentCode = $currentUser['referrer_code'] ?? get_cookie('referrer') ?? '';
		if($parentCode)
			$parentUser = $Referral->where('referral_code', $parentCode)->get();

		// Create referral row
		$data = [
			'user_id' => ci()->session->user_id,
			'referral_code' => $referral_code,
			'parent_id' => $parentUser['id'] ?? '',
			'partner_id' => $parentUser['partner_id'] ?? 1
		];
		$res = $Referral->insert($data);
		if($res){
			ci()->session->set_flashdata('message', '<div class="alert alert-success">Kode referral berhasil dibuat.</div>');
		} else {
			ci()->session->set_flashdata('message', '<div class="alert alert-danger">Terjadi kesalahan saat membuat kode referral. Silakan hubungi admin untuk bantuan.</div>');
		}
		redirect('earnings');
	}

}