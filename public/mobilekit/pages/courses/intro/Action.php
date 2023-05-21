<?php

use Package\Learning\modules\course\models\CourseEntity;

/** 
 * Home page action
 * 
 */
class PageAction {

	public function __construct()
	{
		ci()->load->model('course/Course_model');
        ci()->load->model('course/Lesson_model');
        ci()->load->model('course/Student_model');
        ci()->load->model('course/Path_model');
      
        ci()->load->helper('membership/membership'); 
        ci()->load->helper('course/course'); 
	}

	// This method handle get request
	public function render(){
		$course = ci()->uri->segment(3);

        // Get course data
        $courseEntity = new CourseEntity($course);
        $data = $courseEntity->getAuthors()->getLessons()->asArray();
		
		// Don't show draft course except for permitted user 
		if($data['status'] == 'draft' && !isPermitted('preview','course')) show_404();

		if($data['total_module'] < 1)
			show_error('Topic dan lesson course masih kosong.', 201);

		$data['page_title'] = $data['course_title'];

		// Subscription status
		ci()->load->model('membership/Subscription_model');
		$data['subscribed'] = ci()->Subscription_model->isSubscribeActive(ci()->session->user_id, $courseEntity->id, 'course');
		
        // Take lesson checked
		$data['lesson_checked'] = array_keys(ci()->Lesson_model->getMarkedLessons(ci()->session->user_id, $data['id']));
		$last_checked = last($data['lesson_checked']);

		// Lesson order list
		$data['opened_lesson'] = $this->_getOpenedLesson($data['lesson_tree'], $data['lesson_checked']);

		// Status
		$data['isCompleted'] = ci()->Lesson_model->isCompleted(ci()->session->user_id, $data['id']);
		$data['completion'] = round(count($data['lesson_checked']) / $data['total_module'] * 100);
		
		// Labels
		$data['labels'] = ci()->Course_model->getLabels($courseEntity->id, 'text');

		// Referral
		if (setting_item('referral.enable') == 1 && isLoggedIn())
		{
			$ReferralModel = setup_entry_model('referral_user');
			$data['referral'] = $ReferralModel->where('user_id',ci()->session->user_id)->get();
		}
		
		return $data;
	}


	// This method handle POST request
	public function process(){}

	private function _getOpenedLesson($lesson_tree, $checkedLessons)
	{
		$lessonIndex = [];
		$checkNext = true; // Buat nandain yang terakhir checked supaya yang nextnya opened
		foreach($lesson_tree as $k => $v){
			foreach($v['lessons'] as $kk => $vv){
				if(in_array($vv['id'], $checkedLessons))
					$lessonIndex[$vv['id']] = true;
				else {
					if($checkNext) {
						$lessonIndex[$vv['id']] = true;
						$checkNext = false;
					} else
						$lessonIndex[$vv['id']] = false;
				}
			}
		}
		return $lessonIndex;
	}

}