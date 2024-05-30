<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_letter_for_excel'))
{
	function get_letter_for_excel($num)
	{
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return get_letter($num2 - 1) . $letter;
        } else {
            return $letter;
        }
	}
}