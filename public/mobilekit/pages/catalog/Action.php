<?php

class PageAction {

	public function render()
	{
		$total = ci()->cart->getTotalItem();
		
		return compact('total');
	}
}