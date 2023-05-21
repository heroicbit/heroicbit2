<?php

/** 
 * Home page action
 * 
 */
class PageAction {

	public $site;
	
	public function __construct()
	{
        ci()->load->model('product/Product_model');
        $this->ReferralModel = setup_entry_model('referral_user');
	}

	// This method handle get request
	public function render(){
		$id = ci()->uri->segment(3);

        $data['product'] = new Package\Commerce\modules\product\models\ProductEntity($id);
        $data['referral'] = $this->ReferralModel
		                         ->where('user_id',ci()->session->user_id)
		                         ->get();

		return $data;
	}


	// This method handle POST request
	public function process(){}

}