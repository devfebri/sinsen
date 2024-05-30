<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('clean_data'))
{
	function clean_data($data)
	{
		$result = [];
		foreach ($data as $key => $value) {
			if($data[$key] != '' && $data[$key] != null){
				$result[$key] = $value;
			}
        }
        return $result;
	}
}