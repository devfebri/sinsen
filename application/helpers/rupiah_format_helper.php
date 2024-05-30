<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('rupiah_format'))
{
	function rupiah_format($nominal, $with_symbol = false, $decimal = 0)
	{
		$formatted = null;

		if($with_symbol) $formatted .= 'Rp ';

		$formatted .= number_format($nominal, $decimal, ',', '.');

		return $formatted;
	}
}
