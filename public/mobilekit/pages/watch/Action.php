<?php

/** 
 * Edit profile page action
 * 
 */
class PageAction {

	public function __construct()
	{

	}

	// This method handle get request
	public function render()
	{
        $id = ci()->uri->segment(2);

        // Get event detail
        $EventModel = setup_entry_model('event');
        $event = $EventModel->get($id);
        if(!$event) show_404();

        // Prevent non-premium-member to access
        if(ci()->shared['membership']['status'] != 'active' && $event['type'] == 'premium'){
        	ci()->session->set_flashdata('toastr', json_encode(['type'=>'warning','message'=>'Anda harus tergabung sebagai member premium terlebih dahulu untuk menyimak rekaman workshop.']));
        	redirect('online-event/detail/'.$id);
        }
        
        $presenters = ci()->db->from('event_presenter ep')
        					  ->join('presenter p', 'p.id = ep.presenter_id')
        					  ->where('event_id', $event['id'])
        					  ->get()->result_array();

        return compact('event','presenters');
	}


	// This method handle POST request
	public function process()
	{

	}

}