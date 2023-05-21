<?php

use Package\Learning\modules\course\models\CourseEntity;

/** 
 * Home page action
 * 
 */
class PageAction {

	private $previewMode = false;

	public function __construct()
	{
		ci()->load->model('course/Course_model');
        ci()->load->model('course/Lesson_model');
        ci()->load->model('course/Student_model');
        ci()->load->model('course/Path_model');

        ci()->load->helper('membership/membership'); 
        ci()->load->helper('course/course');

		if(ci()->input->get('preview') && isPermitted('preview', 'course'))
			$this->previewMode = true;
	}

	// This method handle get request
	public function render()
	{
		$courseSlug = ci()->uri->segment(3);
		$topic = ci()->uri->segment(4);
		$lessonSlug = ci()->uri->segment(5);

        $courseEntity = new CourseEntity($courseSlug);
        $courseEntity->getLessons();

        // Take lesson tree
		$lesson_tree = $courseEntity->lesson_tree;
		// $lesson_tree = ci()->Lesson_model->getLessonTree($courseSlug);
		if (!$lesson_tree) show_404();
		
		// Get first topic
		if (!$topic) {
			reset($lesson_tree);
			$topic = key($lesson_tree);
		
		} else if(!isset($lesson_tree[$topic]))
			show_404();

		// Prepare lesson content and attributes, if lessonSlug not defined, choose first file
		reset($lesson_tree[$topic]['lessons']);
		if(!$lessonSlug) $lessonSlug = key($lesson_tree[$topic]['lessons']);

		// Get lesson content and attributes
		if (isset($lesson_tree[$topic]['lessons'][$lessonSlug]['file'])) {
			$lessonFile = $lesson_tree[$topic]['lessons'][$lessonSlug]['file']; 
		} else {
			$lessonFile = null;
		}
		
		$currentTopic = $lesson_tree[$topic];
		$currentLesson = $currentTopic['lessons'][$lessonSlug];
		$currentLesson['content'] = ci()->Lesson_model->getContent($currentLesson);

		// Get quiz if setted.
		if ($currentLesson['quiz_id'] != null) {
			ci()->load->model('quiz/Group_model');
			$quiz = ci()->Group_model->get($currentLesson['quiz_id']);
			$currentLesson['quiz_slug'] = $quiz['slug'] ?? [];
		}

		if(! $currentLesson) show_404();

        ci()->load->model('membership/Subscription_model');
        $isSubscribeActive = ci()->Subscription_model->isSubscribeActive(ci()->session->user_id, $courseEntity->id, 'course');

		// Check if premium course can be accessed by user
        if ($courseEntity->premium 
			&& $currentTopic['free'] == false 
			&& $currentLesson['free'] == false 
			&& $isSubscribeActive === false
			&& $this->previewMode == false)
        {
			ci()->session->set_flashdata('message', json_encode(['type'=>'warning', 'message'=>'Untuk melanjutkan belajar, silakan mendaftar kelas terlebih dahulu.']));
			redirect('courses/intro/'.$courseSlug);
        }

		// Load view
		ci()->shared['page_title'] = $currentLesson['lesson_title'];
		$data['lesson_tree'] = $lesson_tree; // Complete topics and lessons tree
		$data['course_detail'] = $courseEntity->asArray(); // Course detail
		$data['course'] = $courseSlug; // Course slug
		$data['topic'] = $lesson_tree[$topic]; // current topic
		$data['lesson'] = $currentLesson; // current lesson content
		$data['lesson_checked'] = array_keys(ci()->Lesson_model->getMarkedLessons(ci()->session->user_id, $courseEntity->id));
		$data['isSubscribeActive'] = $isSubscribeActive;
		$data['opened_lesson'] = $this->_getOpenedLesson($data['lesson_tree'], $data['lesson_checked']);
		$data['lesson_order'] = array_keys($data['opened_lesson']);
		$data['preview_mode'] = $this->previewMode;
		// dd($data['course_detail']);

		return $data;
	}


	// Check lesson, requested by ajax
	public function process(){
		$courseID = ci()->uri->segment(3);
		$hash = ci()->uri->segment(4);
		$nextLessonID = ci()->uri->segment(5);

		// TODO: Check lesson and redirect to next lesson
		ci()->load->model('course/Lesson_model');
		$lesson = ci()->Lesson_model->where('course_id', $courseID)->where('hash', $hash)->get();
		if(ci()->Lesson_model->markLesson(ci()->session->user_id, $lesson['id'], $lesson['course_id']))
			echo json_encode($result = ['status'=>'success', 'lesson_id'=>$lesson['id'], 'next_lesson' => $nextLessonID]);
		else
			echo json_encode($result = ['status'=>'failed', 'message'=>'Ada kesalahan teknis saat menandai materi selesai dipelajari.']);
		exit;
	}

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