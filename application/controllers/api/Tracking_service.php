<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking_service extends CI_Controller {

	public function index()
	{
		
	}

	public function cekServis()
	{
		// atasi cors in php
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	        header('Access-Control-Allow-Origin: *');
	        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
	        header('Access-Control-Allow-Headers: token, Content-Type');
	        header('Access-Control-Max-Age: 1728000');
	        header('Content-Length: 0');
	        header('Content-Type: text/plain');
	        die();
	    }

	    header('Access-Control-Allow-Origin: *');
	    header('Content-Type: application/json');

		$input = file_get_contents("php://input");
		$decoded_data = json_decode($input);

		$no_mesin = $decoded_data->no_mesin;		
		// $decoded_data->no_mesin = 'JF51E13329187';

		$tgl = date('Y-m-d');

		$data = $this->db->query("
			select * from (
				select c.nama_customer , c.no_mesin , c.no_polisi , (case when a.status ='open' && a.start_at is not null then 'start' else a.status end) as status, a.created_at as tgl_service ,a.start_at as mulai_pengerjaan  
				,(select sum(waktu) as estimasi_lama_pengerjaan from tr_h2_wo_dealer_pekerjaan where id_work_order = a.id_work_order)  as estimasi_pengerjaan
				, a.closed_at as selesai_pengerjaan
				from tr_h2_wo_dealer a
				join tr_h2_sa_form b on a.id_sa_form = b.id_sa_form 
				join ms_customer_h23 c on b.id_customer  = c.id_customer
				where (a.created_at > '$tgl') or a.status ='pending'
				order by a.created_at DESC
			)z where z.no_mesin = '$no_mesin'
		")->row();

		if(count($data) > 0){
			$data = [
		        array(
		            'no_polisi' => $data->no_polisi,
		            'status' => $data->status,
		            'tgl_service'=>$data->tgl_service,
		            'mulai_pengerjaan'=>$data->mulai_pengerjaan,
		            'estimasi_pengerjaan'=> $data->estimasi_pengerjaan,
					'selesai_pengerjaan' => $data->selesai_pengerjaan
		        )
		    ];

		    $result = [
		    	'kode' => '200',
		    	'pesan' => 'berhasil',
		    	'data' => $data
		    ];
		} else {
			$result = [
		    	'kode' => '404',
		    	'pesan' => 'No Mesin tidak ditemukan',
		    	'data' => []
		    ];
		}
		echo json_encode($result);
	}

}
