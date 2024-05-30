<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('hitung_diskon'))
{
	function hitung_diskon($tipeDiskon, $nilaiDiskon, $harga)
	{
		$nilaiDiskon = (double) $nilaiDiskon;
		$harga = (double) $harga;
		
		$diskon = 0;
		if($tipeDiskon == 'Rupiah'){
			$diskon = $nilaiDiskon;
		}else if($tipeDiskon == 'Persen' OR $tipeDiskon == 'Percentage'){
			$diskon = ($nilaiDiskon/100) * $harga;
		}

		return $diskon;
	}
}

if (!function_exists('harga_setelah_diskon'))
{
	function harga_setelah_diskon($tipeDiskon, $nilaiDiskon, $harga, $additional = false, $tipeDiskonCampaign = null, $nilaiDiskonCampaign = 0)
	{
		if($harga == 0) return $harga;

		$harga_setelah_diskon = $harga;
		$harga_setelah_diskon -= hitung_diskon($tipeDiskon, $nilaiDiskon, $harga_setelah_diskon);

		if($additional){
			$harga_setelah_diskon -= hitung_diskon($tipeDiskonCampaign, $nilaiDiskonCampaign, $harga_setelah_diskon);
		}else{
			$harga_setelah_diskon -= hitung_diskon($tipeDiskonCampaign, $nilaiDiskonCampaign, $harga);
		}

		return $harga_setelah_diskon;
	}
}
