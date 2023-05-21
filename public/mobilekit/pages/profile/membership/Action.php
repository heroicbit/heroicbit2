<?php

/** 
 * Home page action
 * 
 */
class PageAction {

	// This method handle get request
	public function render()
	{
		$membership_slug = setting_item('membership.product_slug');
		$Product = new App\modules\product\models\ProductEntity;
		$Product->getBy('product_slug', $membership_slug);
		if(!$Product->id)
			throw new Exception('Membership product not found. Please create it first by run membership seeder.');

		return ['harga_membership' => $Product->retail_price];
	}

	// This method handle POST request
	public function process(){}

}