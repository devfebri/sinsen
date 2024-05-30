<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('send_json'))
{
	function send_json($arr, $httpCode = 200)
	{
		$ci = &get_instance();
		$ci->output->set_status_header($httpCode);
		$ci->output->set_content_type('application/json');
		$ci->output->set_output(json_encode($arr));
		$ci->output->_display();
		die;
	}
}