<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_diskon_part'))
{
	function get_diskon_part($id_dealer, $tipe_po, $produk, $kategori_po, $parts)
	{
		$CI = &get_instance();

		$CI->load->helper('jumlah_dus');
		$CI->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');
		$CI->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$CI->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$CI->load->model('H3_md_sales_campaign_model', 'sales_campaign');

		$jumlah_dus = jumlah_dus($parts);
		$data = [];
        foreach($parts as $part){
			$part['tipe_diskon'] = null;
			$part['diskon_value'] = null;
			$part['id_campaign_diskon'] = null;
			$part['tipe_diskon_campaign'] = null;
			$part['diskon_value_campaign'] = null;
			if(!in_array($tipe_po, ['HLO', 'URG'])){
				if($produk == 'Oil'){
					if($kategori_po == 'KPB'){
						$diskon = $CI->diskon_oli_kpb->get_diskon_oli_kpb($part['id_part'], $part['id_tipe_kendaraan']);
						if($diskon != null){
							$part['tipe_diskon'] = $diskon['tipe_diskon'];
							$part['diskon_value'] = $diskon['diskon_value'];
						}else{
							$part['tipe_diskon'] = null;
							$part['diskon_value'] = null;
						}
					}else{
						$diskon = $CI->diskon_oli_reguler->get_diskon($part['id_part'], $id_dealer, $jumlah_dus);
						if($diskon != null){
							$part['tipe_diskon'] = $diskon['tipe_diskon'];
							$part['diskon_value'] = $diskon['diskon_value'];
						}else{
							$part['tipe_diskon'] = null;
							$part['diskon_value'] = null;
						}
					}
				}else{
					$diskon = $CI->diskon_part_tertentu->get_diskon($part['id_part'], $id_dealer, $tipe_po, $produk);
					if($diskon != null){
						$part['tipe_diskon'] = $diskon['tipe_diskon'];
						$part['diskon_value'] = $diskon['diskon_value'];
					}else{
						$part['tipe_diskon'] = null;
						$part['diskon_value'] = null;
					}
				}

				if($kategori_po != 'KPB'){
					$sales_campaign = $CI->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['kuantitas']);
					if($sales_campaign != null){
						$part['id_campaign_diskon'] = $sales_campaign['id'];
						$part['tipe_diskon_campaign'] = $sales_campaign['tipe_diskon'];
						$part['diskon_value_campaign'] = $sales_campaign['diskon_value'];
					}
				}
			}

			$data[] = $part;
		}

		return $data;
	}
}