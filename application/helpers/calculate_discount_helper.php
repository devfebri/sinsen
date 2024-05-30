<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('calculate_discount')) {
	function calculate_discount($value, $tipe_diskon, $harga)
	{
		if ($tipe_diskon == 'Persen' or $tipe_diskon == 'Percentage') {
			if ($value == 0) return 0;

			return ($value / 100) * $harga;
		} else if ($tipe_diskon == 'Rupiah' or $tipe_diskon == 'Value') {
			return $value;
		}
		return 0;
	}
}
