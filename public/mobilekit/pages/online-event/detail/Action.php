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
        $id = ci()->uri->segment(3);

		if(ci()->uri->segment(4) == 'enroll')
			$this->enroll($id);
		elseif(ci()->uri->segment(3) == 'confirm')
			$this->confirm();

		// Get event detail
        $EventModel = setup_entry_model('event');
        $event = $EventModel->get($id);
        if(!$event) show_404();
        
        $presenters = ci()->db->from('event_presenter ep')
        					  ->join('presenter p', 'p.id = ep.presenter_id')
        					  ->where('event_id', $event['id'])
        					  ->get()->result_array();

        // Get user referral data
        $this->ReferralModel = setup_entry_model('referral_user');
	    $referral = $this->ReferralModel
	                     ->where('user_id',ci()->session->user_id)
	                     ->get();

		// Register user as participant
		$participantData = [];
		if(ci()->session->user_id ?? null){
			$ParticipantModel = setup_entry_model('event_participant');
			$participantData = $ParticipantModel->where('event_id', $event['id'])
        									->where('user_id', ci()->session->user_id)
        									->get();
		}

	    ci()->shared['page_title'] = $event['title'];
	    ci()->shared['og_image'] = $event['poster'];
	    ci()->shared['meta_description'] = $event['notes'];

        return compact('event','presenters','referral','participantData');
	}


	// This method handle POST request
	public function process()
	{

	}

	public function enroll($id)
	{
        $user = isLoggedIn();
        if(!$user) redirect('auth/login?red=online-event/detail/'.$id);

		$useConfirmCode = setting_item('workshop.use_confirm_code');
		$userWhatsapp = ci()->uri->segment(5);
		$adminWhatsapp = setting_item('workshop.whatsapp_reg_number');

		if(empty($userWhatsapp)){
			ci()->session->set_flashdata('toastr', json_encode(['type'=>'warning','message'=>'Nomor WhatsApp yang Anda masukkan tidak valid.']));
        	redirect('online-event/detail/'.$id);
		}

		// Get event detail
        $EventModel = setup_entry_model('event');
        $event = $EventModel->get($id);
        if(!$event) show_404();
        
        // Prevent non-premium-member to enroll
        if(ci()->shared['membership']['status'] != 'active' && $event['type'] == 'premium'){
        	ci()->session->set_flashdata('toastr', json_encode(['type'=>'warning','message'=>'Anda harus tergabung sebagai member premium terlebih dahulu untuk dapat mengikuti sessi workshop ini.']));
        	redirect('online-event/detail/'.$id);
        }

        // Register user as participant
        $ParticipantModel = setup_entry_model('event_participant');
        $participantData = $ParticipantModel->where('event_id', $event['id'])
        									->where('user_id', $user['id'])
        									->where('confirmed_with',$userWhatsapp)
        									->get();
        if($participantData){
        	if($participantData['confirmed'] == 1){
	        	ci()->session->set_flashdata('toastr', json_encode(['type'=>'info','message'=>'Anda sudah terdaftar sebagai peserta workshop ini.']));
	        	redirect('online-event/detail/'.$id);
        	}
        } else {
	        $participantData = [
	        	'event_id' => $event['id'],
	        	'user_id' => $user['id'],
	        	'registration_code' => date('mdHi').strtoupper(random_string('alnum', 4)),
	        	'confirmed_with' => $userWhatsapp,
	        	'confirmed_to' => $adminWhatsapp,
	        ];
	        $ParticipantModel->insert($participantData);
        }

        // Set whatsapp template message
        if($useConfirmCode == '1'){
			$message = "Halo Evapora,\nsaya sudah Mendaftarkan diri di acara {$event['title']}, atas nama {$user['name']}.\nMohon admin mengaktifkan pendaftaran saya melalui link ini: ".site_url('appr')."/{$participantData['registration_code']}\nTerima kasih.";
        } else {
	        $time = PHP81_BC\strftime("%A, %d %B %Y", strtotime($event['date']), 'id_ID').' pukul '.PHP81_BC\strftime("%H:%M", strtotime($event['date']), 'id_ID');
			$message = "Halo Evapora,\nsaya sudah Mendaftarkan diri di acara {$event['title']}, atas nama {$user['name']}.\nInsyaAllah, {$time} s/d selesai di aplikasi Zoom Meeting {$event['zoom_link']} atau Meeting ID : {$event['zoom_meeting_id']}";
			$message .= ($event['zoom_passcode'] ?? null) ? " dengan password: {$event['zoom_passcode']}." : " tanpa password.";
			$message .= "\nTerima Kasih Evapora.";
        }

		$message = urlencode($message);

		redirect("https://wa.me/{$adminWhatsapp}?text={$message}");
		exit;
	}

	public function confirm()
	{
		// Only role with approve permission can approve confirmation
		if(! isPermitted('approve','event_participant')) show_error('Only admin can approve registartion', 200);

		$registration_code = ci()->uri->segment(4);
		$confirm = ci()->uri->segment(5);
		
        $ParticipantModel = setup_entry_model('event_participant');
        if($confirm)
        {
	        $updated = $ParticipantModel->where('registration_code', $registration_code)
	        							->update(['confirmed'=>1]);
	        if($updated)
	        	echo "<h2>Pendaftaran berhasil dikonfirmasi</h2>";
	        else
	        	echo "<h2>Pendaftaran gagal dikonfirmasi</h2>";
        } else {
        	$participant = $ParticipantModel->with_event()->with_user()->where('registration_code', $registration_code)->get();
        	if($participant)
        	{
        		echo "<h2>Mengkonfirmasi Pendaftaran</h2>";
        		echo "<p>Pendaftar atas nama <strong>{$participant['user']['name']}</strong> mendaftar<br>";
        		echo "untuk event <strong>{$participant['event']['title']}</strong> (".PHP81_BC\strftime("%d %B %Y %H:%M", strtotime($participant['event']['date']), 'id_ID').")<br>";
        		echo "dengan nomor WhatsApp <strong>{$participant['confirmed_with']}</strong>.<br>";
        		echo "Apakah nomor WhatsApp sudah valid?<p>";
        		echo "<a href='".site_url('online-event/detail/confirm/'.$registration_code.'/1')."'>Valid dan terima pendaftaran</a> <br><br> <a href='".site_url('admin/entry/event_participant/delete/'.$participant['id'])."'>Tidak valid, hapus pendaftaran</a>";
        		echo "<p><small><em>Ingatkan pendaftar untuk mendaftar ulang dengan nomor WhatsApp yang valid.</em></small></p>";
        	} else
	        	echo "<h2>Kode pendaftaran tidak valid.</h2>";
        }

    	exit;
	} 

}