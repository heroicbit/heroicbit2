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
		$data['carousels'] = [];
		if(ci()->db->table_exists('slider')){
			$SliderModel = setup_entry_model('slider');
			$data['carousels'] = $SliderModel->where('status','1')->getAll();
		}

		$data['upcoming'] = [];
		$data['events'] = [];
		if(ci()->db->table_exists('event'))
		{
			$EventModel = setup_entry_model('event');
			$data['upcoming'] = $EventModel
									->where('type', 'reguler')
									->where('status', '1')
									->where('date >=', date('Y-m-d H:i:s'))
									->order_by('date','desc')
									->limit(4)
									->getAll();
			$data['events'] = $EventModel
									->where('type', 'reguler')
									->where('status', '1')
									->where('date <', date('Y-m-d H:i:s'))
									->order_by('date','desc')
									->limit(8)
									->getAll();

			// dd($data['upcoming'], $data['events']);
		}

		ci()->load->model('post/Post_model');
		$data['feed'] = ci()->Post_model->getPosts('post', 'array', 'publish');

		return $data;
	}


	// This method handle POST request
	public function process(){}

}