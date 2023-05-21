<?php

/** 
 * Home page action
 * 
 */
class PageAction {

	public function __construct()
	{

	}

	// This method handle get request
	public function render()
	{
		$data['upcoming'] = [];
		$data['events'] = [];
		if(ci()->db->table_exists('event'))
		{
			$EventModel = setup_entry_model('event');
			$data['upcoming'] = $EventModel
									->where('type', 'premium')
									->where('status', '1')
									->where('date >=', date('Y-m-d H:i:s'))
									->order_by('date','desc')
									->limit(4)
									->getAll();
			$data['events'] = $EventModel
									->where('type', 'premium')
									->where('status', '1')
									->where('date <', date('Y-m-d H:i:s'))
									->order_by('date','desc')
									->limit(8)
									->getAll();

			// dd($data['upcoming'], $data['events']);
		}
		
		$data['presenters'] = [];
		if(ci()->db->table_exists('presenter'))
		{
			$PresenterModel = setup_entry_model('presenter');
			$data['presenters'] = $PresenterModel->getAll();
		}

		$membership_slug = setting_item('membership.product_slug');
		$Product = new App\modules\product\models\ProductEntity;
		$Product->getBy('product_slug', $membership_slug);
		if(!$Product->id)
			throw new Exception('Membership product not found. Please create it first by run membership seeder.');
		$data['harga_membership'] = $Product->retail_price;

		$MembershipAnnual = new App\modules\product\models\ProductEntity;
		$MembershipAnnual->getBy('product_slug', 'membership-1-tahun');
		$data['harga_membership_tahunan'] = $MembershipAnnual->retail_price;

		return $data;
	}


	// This method handle POST request
	public function process(){}

}