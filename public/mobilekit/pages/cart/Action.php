<?php

class PageAction {

	public function process()
	{
		if($qtys = ci()->input->post('qty', true)) {
			foreach($qtys as $id => $items) {
				foreach($items as $hash => $qty) {
					$product = ci()->cart->getItem($id, $hash);
					ci()->cart->update($id, $qty, $product['attributes']);
				}
			}
		}

		redirect(getenv('HTTP_REFERER'));
	}

	public function render()
	{
		$method = ci()->uri->segment(2);
		$productId = ci()->uri->segment(3);

		// Run remove endpoint
		if($method == 'remove'){
			$productHash = ci()->uri->segment(4);
			$this->removeItem($productId, $productHash);
		}

		// Run add endpoint
		if($method == 'add'){
			$qty = ci()->uri->segment(4);
			$this->addItem($productId);
		}

		$data = [
			'products' => ci()->cart->getItems(),
			'qty' => ci()->cart->getTotalQuantity(),
			'subtotal' => ci()->cart->getAttributeTotal('price')
		];

		return $data;
	}

	private function addItem($slug, $qty = 1)
	{
		$resetCart = ci()->input->get('reset', true);
		$gotoCheckout = ci()->input->get('checkout', true);

		$Product = new Package\Commerce\modules\product\models\ProductEntity;
		
		if($Product->getBy('product_slug', $slug))
		{
			if($resetCart) ci()->cart->clear();

			ci()->cart->add($Product->id, $qty, [
				'name' => $Product->product_name,
				'price' => $Product->retail_price,
				'weight' => $Product->weight,
			]);
		}
		
		redirect($gotoCheckout ? 'checkout' : 'cart');
	}

	private function removeItem($id, $hash)
	{
		$item = ci()->cart->getItem($id, $hash);
		if($item)
			ci()->cart->remove($id, $item['attributes']);

		redirect('cart');
	}

}