<?php

/** 
 * Invoice page action
 * 
 */
class PageAction {

	// This method handle get request
	public function render()
	{
		ci()->load->model('payment/Transaction_model');

		$uri = 'dashboard/invoice';

		// Get transactions
		$transaction = ci()->Transaction_model
					   ->with_items([
					   		'fields' => 'price,qty,subtotal',
					   		'with' => [
					   			'relation'=>'product',
					   			'fields'=>'product_name'
					   			]
					   		])
					   ->with_shipment()
					   ->with_customer()
					   // ->where('user_id', ci()->session->user_id)
					   ->where('order_code', ci()->uri->segment(4))
                       ->get();

        return compact('transaction');
	}

	// This method handle POST request
	public function process()
	{
		// Code here
	}

}