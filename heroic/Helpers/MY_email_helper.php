<?php

function sendEmail($to, $subject, $data = [], $template = null, $body = null, $config = [])
{
	// Config harus disertakan ke payload job
	if (!$config) {
		if (setting_item('emailer.use_mailcatcher') == 'yes') {
			$config = [
				'smtp_host' => 'localhost',
				'smtp_port' => 1025,
				'smtp_username' => '',
				'smtp_password' => '',
			];
		} else {
			$config = [
				'smtp_host' => setting_item('emailer.smtp_host'),
				'smtp_port' => setting_item('emailer.smtp_port'),
				'smtp_username' => setting_item('emailer.smtp_username'),
				'smtp_password' => setting_item('emailer.smtp_password'),
				'smtp_secure' => 'tls',
				'smtp_auth' => true,
			];
		}
		$config['debug'] = setting_item('emailer.debug_emailer') == 'yes' ? true : false;
	}

	// Send email using Queue
	if (setting_item('emailer.send_by_queue') == 'yes') {
		$jobdata = [
			'from' => [setting_item('emailer.email_from'), setting_item('emailer.sender_name')],
			'to' => $to,
			'subject' => $subject,
			'data' => $data,
			'template' => $template ? str_replace('../', '', $template) : null,
			'body' => $body
		];
		$jobdata = array_merge($jobdata, $config);

		\App\libraries\Beanstalk::produce('email', $jobdata);
	}

	// Send email directly
	else {
		$emailer = new \App\libraries\Emailer(setting_item('emailer.debug_emailer') == 'yes' ? true : false);
		$emailer->setConfig($config);
		$emailer->from = [setting_item('emailer.email_from'), setting_item('emailer.sender_name')];
		$emailer->to = $to;
		$emailer->subject = $subject;
		if($body) $emailer->body = $body;
		$emailer->send($template, $data);
	}
}
