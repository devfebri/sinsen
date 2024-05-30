<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking_stnk extends CI_Controller {

	public function index()
	{
		
	}

	public function cekStnk()
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

		$class1 = '';
		$waktu1 = '';
		$class2 = '';
		$waktu2 = '';
		$class3 = '';
		$waktu3 = '';
		$class4 = '';
		$waktu4 = '';
		$ket = "<br><br>";

		// cek ssu
		$this->db->where('no_mesin', $decoded_data->no_mesin);
		$ssu = $this->db->get('tr_sales_order');

		// cek ssu gc
		$this->db->select('b.tgl_create_ssu');
		$this->db->from('tr_sales_order_gc_nosin a');
		$this->db->join('tr_sales_order_gc b', 'a.id_sales_order_gc = b.id_sales_order_gc', 'inner');
		$this->db->where('a.no_mesin', $decoded_data->no_mesin);
		$ssu_gc = $this->db->get();

		if ( ($ssu->num_rows() > 0 AND $ssu->row()->tgl_create_ssu != null ) || ($ssu_gc->num_rows() > 0 AND $ssu_gc->row()->tgl_create_ssu != null) ) {

			if ($ssu->num_rows() > 0) {
				$class1 = 'sukses';
				$waktu1 = date('d-m-Y H:i:s',strtotime($ssu->row()->tgl_create_ssu));
			} elseif ($ssu_gc->num_rows() > 0) {
				$class1 = 'sukses';
				$waktu1 = date('d-m-Y H:i:s',strtotime($ssu_gc->row()->tgl_create_ssu));
			}

			

			//cek tgl bastd
			$no_bastd = get_data('tr_faktur_stnk_detail','no_mesin',$decoded_data->no_mesin,'no_bastd');
			$tgl_bastd = get_data('tr_faktur_stnk','no_bastd',$no_bastd,'created_at');
			if ($tgl_bastd != '') {
				$class2 = 'sukses';
				$waktu2 = date('d-m-Y H:i:s',strtotime($tgl_bastd));
			}

			// cek serah terima dealer
			$no_serah_stnk = get_data('tr_penyerahan_stnk_detail','no_mesin',$decoded_data->no_mesin,'no_serah_stnk');
			$tgl_serah_terima = get_data('tr_penyerahan_stnk','no_serah_stnk',$no_serah_stnk,'created_at');
			if ($tgl_serah_terima != '') {
				$class3 = 'sukses';
				$waktu3 = date('d-m-Y H:i:s',strtotime($tgl_serah_terima));
			}

			// cek serah terima konsumen
			$kd_stnk_konsumen = get_data('tr_tandaterima_stnk_konsumen_detail','no_mesin',$decoded_data->no_mesin,'kd_stnk_konsumen');
			$tgl_terima_stnk = get_data('tr_tandaterima_stnk_konsumen','kd_stnk_konsumen',$kd_stnk_konsumen,'created_at');
			if ($tgl_terima_stnk != '') {
				$class4 = 'sukses';
				$waktu4 = date('d-m-Y H:i:s',strtotime($tgl_terima_stnk));
			}

			$data = [
		        array(
		            'id' => '1',
		            'class' => $class1,
		            'judul'=>'Pembelian Sepeda Motor Honda',
		            'keterangan'=>$ket,
		            'waktu'=> $waktu1,
		        ),
		        array(
		            'id' => '2',
		            'class' => $class2,
		            'judul'=>'Pendaftaran BBN Samsat',
		            'keterangan'=>$ket,
		            'waktu'=>$waktu2,
		        ),
		        array(
		            'id' => '3',
		            'class' => $class3,
		            'judul'=>'Proses Serah Terima Dealer',
		            'keterangan'=>$ket,
		            'waktu'=>$waktu3,
		        ),
		        array(
		            'id' => '4',
		            'class' => $class4,
		            'judul'=>'Proses Serah Terima Konsumen',
		            'keterangan'=>'',
		            'waktu'=>$waktu4,
		        ),
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

/* End of file Tracking_stnk.php */
/* Location: ./application/controllers/Tracking_stnk.php */