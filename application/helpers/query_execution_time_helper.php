<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('query_execution_time'))
{
	function query_execution_time()
	{
		$ci = &get_instance();

        $queries = $ci->db->queries;
        $query_times = $ci->db->query_times;

		$execution_time = [];
		foreach($queries as $index => $query){
			$data = [];
			$data['query'] = trim(preg_replace('/\s+/', ' ', $query));
			$data['time'] = $query_times[$index];
			
			$execution_time[] = $data;
		}

		return $execution_time;
	}
}