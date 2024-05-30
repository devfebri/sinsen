<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('session_message'))
{
	function session_message($tipe, $pesan)
	{
		$ci = &get_instance();

		$ci->session->set_userdata('tipe', $tipe);
        $ci->session->set_userdata('pesan', $pesan);
	}
}