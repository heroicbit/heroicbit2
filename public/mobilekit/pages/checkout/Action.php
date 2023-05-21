<?php

use Symfony\Component\Yaml\Yaml;

class PageAction {

	public $products;
	public $customer;
	public $shipping;
	public $coupon;

	public function __construct()
	{
		// Mandatory data
		$this->products = ci()->cart->getItems();
		
		// Optional data
		$this->shipping = $orderdata['shipping'] ?? '';
		$this->coupon = $orderdata['coupon'] ?? '';
	}

	public function render()
	{
		if(!$this->products) redirect('cart');

		$data = [
			'products' => $this->products,
			'customer' => $this->customer,
			'subtotal' => ci()->cart->getAttributeTotal('price'),
			'weight' => ci()->cart->getAttributeTotal('weight') ?? 0,
			'user' => isLoggedIn(),
			'payment_methods'=> $this->_getPaymentMethods(),
		];
		
		// Hitung pajak di luar harga produk
		if(setting_item('payment.calculate_tax') == 'yes'){
			$data['enableTax'] = true;
			$data['tax'] = $data['subtotal'] * 10/100;
		}

		$data['enableShipping'] = $data['weight'] > 0 ? true : false;

		if(setting_item('payment.calculate_transaction_fee') == 'yes'
			&& $data['enableShipping'])
			$data['transaction_fee'] = setting_item('payment.static_transaction_fee');
		else
			$data['transaction_fee'] = 0;
		
		$data['total'] = $data['subtotal'] + ($data['tax'] ?? 0) + $data['transaction_fee'];

		$activePaymentGateway = explode(',', setting_item('payment.active_payment_gateway'));

		return $data;
	}

	public function process()
	{
		$postdata = ci()->input->post('payload', true);
		$postdata = json_decode($postdata, true);

		$_SESSION['order']['customer'] = [
			'fullname' => $postdata['fullname'],
			'email' => $postdata['email'],
			'phone' => $postdata['phone'],
		];
		$_SESSION['order']['subtotal'] = ci()->cart->getAttributeTotal('price');
		
		// Get shipping cost
		if(ci()->input->post('enableShipping', true) == true)
		{
			ci()->load->library('payment/RajaOngkir');

			$weight = ceil(ci()->cart->getAttributeTotal('weight')/1000)*1000;

			$costs =ci()->rajaongkir->getCost(
				setting_item('payment.static_origin_id'), 
				$postdata['destination_id'], 
				$weight, 
				$postdata['courier']);

	        $costs = json_decode($costs, true);

	        list($courierKey,$costKey) = explode('-',$postdata['shipping_price_key']);

	        if(isset($costs['rajaongkir']) 
	        	&& $costs['rajaongkir']['status']['code'] == 200) {
	
				$this->shipping = [
					'address' => $postdata['address'],
					'city' => $costs['rajaongkir']['destination_details']['city_name'],
					'province' => $costs['rajaongkir']['destination_details']['province'],
					'cost' => $costs['rajaongkir']['results'][$courierKey]['costs'][$costKey]['cost'][0]['value'],
					'origin_id' => setting_item('payment.static_origin_id'),
					'destination_id' => $postdata['destination_id'],
					'weight' => ci()->cart->getAttributeTotal('weight'),
					'courier' => $postdata['courier'],
					'courier_service' => $costs['rajaongkir']['results'][$courierKey]['costs'][$costKey]['service'],
					'cost_index_id' => $postdata['shipping_price_key'],
				];

				$_SESSION['order']['shipping'] = $this->shipping;
	        }
			
			$_SESSION['order']['subtotal'] = ci()->cart->getAttributeTotal('price') + ($this->shipping['cost'] ?? 0);
		}

		redirect('pay');
	}

	private function _getPaymentMethods()
	{
		$fileList = glob(APPPATH.'modules/payment/methods/*.yml');

		$methods = [];
		foreach ($fileList as $methodFile) {
			$fileinfo = pathinfo($methodFile);
			if(in_array($fileinfo['filename'], explode(',',setting_item('payment.active_payment_gateway'))))
				$methods[$fileinfo['filename']] = Yaml::parseFile($methodFile);
		}
		
		return $methods;
	}
}
