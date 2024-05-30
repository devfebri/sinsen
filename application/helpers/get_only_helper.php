<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_only'))
{
	function get_only($keys = [], $stacks = [], $inject = [])
	{
		if($stacks == []){
            return [];
        }
		$final = [];
		foreach ($stacks as $each) {
            $subArr = [];
            if($keys === true OR ($keys == [])){
                $subArr = $each;
            }else{
                foreach ($keys as $key) {
                    if(isset($each[$key]) and $each[$key] != ''){
                        $subArr[$key] = $each[$key];
                    }else{
                        $subArr[$key] = null;
                    }
                }
            }
			
            $subArr = array_merge($subArr, $inject);
			$final[] = $subArr;
        }
		return $final;
	}
}