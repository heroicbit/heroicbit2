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

		$perPage = 10;
		$pagenum = ci()->uri->segment(3) ?? 1;
		$filters = ci()->input->get(null, true);
		$uri = 'dashboard/invoice';

		// Get transactions
		$transactions = ci()->Transaction_model
					   ->with_items([
					   		'fields' => 'price,qty,subtotal',
					   		'with' => [
					   			'relation'=>'product',
					   			'fields'=>'product_name'
					   			]
					   		])
					   ->with_shipment()
                       ->getTransactions($perPage, $pagenum, $filters, $uri, true, ['user_id' => ci()->session->user_id]);

        return compact('transactions','filters');
	}

	// This method handle POST request
	public function process()
	{
		// Code here
	}

}