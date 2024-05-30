<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('jumlah_dus'))
{
	function jumlah_dus($parts)
	{
		$CI = &get_instance();

        $total_dus = 0;
		foreach ($parts as $part) {
			if(!isset($part['id_part'])){
				throw new Exception('Tidak terdapat data id part untuk mengetahui jumlah dus');
			}

			if(!isset($part['kuantitas'])){
				throw new Exception('Tidak terdapat data kuantitas untuk mengetahui jumlah dus');
			}


			$dataPart = $CI->db
				->select('IFNULL(p.qty_dus, 1) as qty_dus')
				->from('ms_part as p')
				->where('p.id_part', $part['id_part'])
				->get()->row_array();

			if($dataPart != null){
				$total_dus += $part['kuantitas'] / $dataPart['qty_dus'];
			}
			
		}

		return floor($total_dus);
	}
}