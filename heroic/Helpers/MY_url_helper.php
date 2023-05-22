<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('redirect'))
{
	/**
	 * OVERRIDED Header Redirect
	 *
	 * Adding support PJAX redirect
	 */
	function redirect($uri = '', $method = 'auto', $code = NULL)
	{
		if ( ! preg_match('#^(\w+:)?//#i', $uri))
		{
			$uri = site_url($uri);
		}

		// IIS environment likely? Use 'refresh' for better compatibility
		if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
		{
			$method = 'refresh';
		}
		elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
		{
			if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
			{
				$code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
					? 303	// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
					: 307;
			}
			else
			{
				$code = 302;
			}
		}

		switch ($method)
		{
			case 'refresh':
				ci()->output->set_header('X-PJAX-URL: ' . $uri);
				header('Refresh:0;url='.$uri);
				break;
			default:
				ci()->output->set_header('X-PJAX-URL: ' . $uri);
				header('Location: '.$uri, TRUE, $code);
				break;
		}
		exit;
	}
}
