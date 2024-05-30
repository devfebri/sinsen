<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_queue extends CI_Controller
{

	var $tables = "tr_h2_sa_form";
	var $folder = "dealer";
	var $page   = "manage_queue";
	var $title  = "Manage Queue";
	// var $order_column_part = array("id_part","nama_part",'kelompok_vendor',null); 

	public function __construct()
	{
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_master', 'm_h2');
		$this->load->model('m_h2_booking', 'm_bk');
		$this->load->model('m_h2_api', 'm_api');
		$this->load->model('m_h2_work_order', 'm_wo');

		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index";
		$filter['tgl_servis'] = tanggal();
		$filter['status_form_is_null'] = true;
		$data['antrian'] = $this->m_bk->getQueue($filter);
		$this->template($data);
	}

	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->db->order_by('nama_jasa', 'ASC');
		$data['jasa'] = $this->db->get('ms_jasa_servis');
		$this->template($data);
	}

	public function sa_form_add()
	{
		$data['isi']     = $this->page;
		$data['title']   = 'Create SA Form';
		$data['mode']    = 'insert';
		$data['set']     = "sa_form_add";
		$id_antrian = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$cek = $this->db->query("SELECT * FROM tr_h2_sa_form WHERE id_antrian='$id_antrian' AND id_dealer='$id_dealer' AND status_form IS NULL AND id_sa_form IS NULL");
		if ($cek->num_rows() > 0) {
			$row = $data['row'] = $cek->row();
			$data['cust'] = $this->db->query("SELECT ms_customer_h23.*,kelurahan,kecamatan,kabupaten,provinsi 
				FROM ms_customer_h23 
				JOIN ms_kelurahan ON ms_customer_h23.id_kelurahan=ms_kelurahan.id_kelurahan
				JOIN ms_kecamatan ON ms_customer_h23.id_kecamatan=ms_kecamatan.id_kecamatan
				JOIN ms_kabupaten ON ms_customer_h23.id_kabupaten=ms_kabupaten.id_kabupaten
				JOIN ms_provinsi ON ms_customer_h23.id_provinsi=ms_provinsi.id_provinsi
				WHERE id_customer='$row->id_customer'");
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_queue'>";
		}
	}

	public function get_id_sa_form()
	{
		$th        = date('y');
		$bln       = date('m');
		$tgl       = date('Y-m-d');
		$thbln     = date('ymd');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$get_data  = $this->db->query("SELECT * FROM tr_h2_sa_form
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$tgl'
			AND id_sa_form IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$last_number = substr($row->id_sa_form, -4);
			$new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/' . sprintf("%'.04d", $last_number + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_h2_sa_form', ['id_sa_form' => $new_kode])->num_rows();
				if ($cek > 0) {
					$gen_number    = substr($new_kode, -4);
					$new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/' . sprintf("%'.04d", $gen_number + 1);
					$i = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/0001';
		}
		return strtoupper($new_kode);
	}

	// function save_sa_form()
	// {
	// 	$waktu      = gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 	$tgl        = date("Y-m-d");
	// 	$login_id   = $this->session->userdata('id_user');
	// 	$id_dealer  = $this->m_admin->cari_dealer();
	// 	$id_sa_form = $this->get_id_sa_form();
	// 	$id_antrian = $this->input->post('id_antrian');
	// 	$tipe_coming = implode($this->input->post('tipe_coming'), ',');
	// 	$data 		= [ 'id_sa_form' => $id_sa_form,
	// 					'informasi_bensin'   => $this->input->post('informasi_bensin'),
	// 					'km_terakhir'        => $this->input->post('km_terakhir'),
	// 					'alasan_ke_ahass'    => $this->input->post('alasan_ke_ahass'),
	// 					'rekomendasi_sa'     => $this->input->post('rekomendasi_sa'),
	// 					'keluhan_konsumen'   => $this->input->post('keluhan_konsumen'),
	// 					'asal_unit_entry'    => $this->input->post('asal_unit_entry'),
	// 					'id_pit'             => $this->input->post('id_pit'),
	// 					'tipe_coming'        => $tipe_coming,
	// 					'created_sa_form_at' => $waktu,
	// 					'created_sa_form_by' => $login_id,
	// 					'status_form'        => 'open',
	// 				  ];

	// 	$details = $this->input->post('details');
	// 	foreach ($details as $keys => $val) {
	// 		$ins_details[$keys] = ['id_sa_form'=>$id_sa_form,
	// 								'estimasi_biaya_servis'  => $val['estimasi_biaya_servis'],
	// 								'estimasi_waktu_kerja'   => $val['estimasi_waktu_kerja'],
	// 								'estimasi_waktu_selesai' => $val['estimasi_waktu_selesai'],
	// 								'id_tipe_kendaraan'      => $val['id_tipe_kendaraan'],
	// 								'jenis_pekerjaan'        => $val['jenis_pekerjaan'],
	// 								'tipe_pekerjaan'         => $val['tipe_pekerjaan'],
	// 								'pekerjaan'              => $val['pekerjaan'],
	// 								'need_parts'             => $val['need_parts'],
	// 							 ];
	// 		if ($val['need_parts']=='yes') {
	// 			foreach ($val['parts'] as $ky => $prt) {
	// 				$ins_parts[]=['id_sa_form'=>$id_sa_form,
	// 										'jenis_pekerjaan'=>$val['jenis_pekerjaan'],
	// 									   'id_part'=>$prt['id_part'],
	// 									   'qty'=>$prt['qty'],
	// 									  ];
	// 			}
	// 		}
	// 	}
	// 	// $result = ['data'=>$data,'ins_details'=>$ins_details,'ins_parts'=>$ins_parts];
	// 	// echo json_encode($result);
	// 	// exit();
	// 	$this->db->trans_begin();
	// 		$this->db->update('tr_h2_sa_form',$data,['id_antrian'=>$id_antrian]);
	// 		if (isset($ins_parts)) {
	// 			$this->db->insert_batch('tr_h2_sa_form_parts',$ins_parts);
	// 		}
	// 		if (isset($ins_details)) {
	// 			$this->db->insert_batch('tr_h2_sa_form_pekerjaan',$ins_details);
	// 		}
	// 	if ($this->db->trans_status() === FALSE)
	//      	{
	// 		$this->db->trans_rollback();
	// 		$rsp = ['status'=> 'error',
	// 				'pesan'=> ' Something went wrong'
	// 			   ];
	//      	}
	//      	else
	//      	{
	//        	$this->db->trans_commit();
	//        	$rsp = ['status' => 'sukses',
	// 				'link' => base_url('dealer/sa_form')
	// 			   ];
	//        	$_SESSION['pesan'] 	= "Data SA Form selesai diproses";
	// 		$_SESSION['tipe'] 	= "success";
	// 		// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
	//      	}
	//      	echo json_encode($rsp);
	// }

	public function cetak()
	{
		$tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');
		$id_antrian = $this->input->get('id');
		$filter = ['id_antrian' => $id_antrian];
		$get_data = $this->m_bk->getQueue($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();
			$upd = [
				'cetak_antrian_ke' => $row->cetak_antrian_ke + 1,
				'cetak_antrian_at' => $waktu,
				'cetak_antrian_by' => $login_id,
			];
			$this->db->update('tr_h2_sa_form', $upd, ['id_antrian' => $id_antrian]);
			$this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print';
			$data['row'] = $row;

			$html = $this->load->view('dealer/manage_queue_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'manage_queue_cetak.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_queue'>";
		}
	}

	function cekKonsumen($nama_konsumen, $no_hp, $no_polisi, $no_mesin)
	{
		// Cari Customer H23
		$cari_nama_konsumen = $nama_konsumen != '' ? "AND nama_customer LIKE '%$nama_konsumen%' " : '';
		$cari_no_hp         = $no_hp != '' ? " AND no_hp LIKE '%$no_hp%' " : '';
		$cari_no_polisi     = $no_polisi != '' ? " AND no_polisi LIKE '%$no_polisi%' " : '';
		$cari_no_mesin     = $no_mesin != '' ? " AND no_mesin LIKE '%$no_mesin%' " : '';

		$customer = $this->db->query("SELECT no_spk,no_hp,nama_customer,alamat,no_polisi, CONCAT(ms_customer_h23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(ms_customer_h23.id_warna,' | ',warna) AS warna,ms_customer_h23.no_mesin,no_rangka,id_cdb,id_customer
			FROM ms_customer_h23 
			JOIN ms_tipe_kendaraan AS tk ON ms_customer_h23.id_tipe_kendaraan=tk.id_tipe_kendaraan
			JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
			WHERE no_polisi IS NOT NULL $cari_nama_konsumen $cari_no_hp $cari_no_polisi $cari_no_mesin");
		if ($customer->num_rows() > 0) {
			$response = ['row' => $customer->num_rows(), 'data' => $customer->result()];
		} else {
			$cari_nama_konsumen = $nama_konsumen != '' ? "AND nama_konsumen LIKE '%$nama_konsumen%' " : '';
			$cari_no_mesin = $no_mesin != '' ? "AND tr_sales_order.no_mesin LIKE '%$no_mesin%' " : '';
			$cari_no_hp = $no_hp != '' ? " AND no_hp LIKE '%$no_hp%' " : '';
			$cari_no_polisi = $no_polisi != '' ? " AND (SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1)='$no_polisi' " : '';
			$cek = $this->db->query("SELECT tr_spk.no_spk,tr_spk.no_hp,nama_konsumen AS nama_customer,alamat,(SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1) AS no_polisi,warna,CONCAT(tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(tr_spk.id_warna,' | ',warna) AS warna,tr_sales_order.no_mesin,(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_sales_order.no_mesin) AS no_rangka, tr_cdb.id_cdb,'' AS id_customer
				FROM tr_cdb
				JOIN tr_spk ON tr_cdb.no_spk=tr_spk.no_spk
				JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
				JOIN ms_tipe_kendaraan AS tk ON tr_spk.id_tipe_kendaraan=tk.id_tipe_kendaraan
				JOIN ms_warna ON tr_spk.id_warna=ms_warna.id_warna
				WHERE (SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1) IS NOT NULL
				$cari_nama_konsumen
				$cari_no_hp
				$cari_no_polisi
				$cari_no_mesin
				LIMIT 1
				");
			if ($cek->num_rows() > 0) {
				$response = ['row' => $cek->num_rows(), 'data' => $cek->result()];
			} else {
				$response = ['row' => $cek->num_rows];
			}
		}
		return $response;
	}

	function cekBooking()
	{
		$jenis_customer = $this->input->post('jenis_customer');
		$no_polisi      = $this->input->post('no_polisi');
		$no_hp          = $this->input->post('no_hp');
		$nama_konsumen  = $this->input->post('nama_konsumen');
		$no_mesin       = $this->input->post('no_mesin');
		$tanggal        = gmdate("Y-m-d", time() + 60 * 60 * 7);
		if ($jenis_customer == 'booking') {
			$booking = $this->db->query("SELECT tr_h2_manage_booking.*,ms_customer_h23.*, nama_customer AS nama_konsumen,CONCAT(ms_customer_h23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(ms_customer_h23.id_warna,' | ',warna) AS warna,ms_h2_jasa_type.deskripsi AS type_servis
						FROM tr_h2_manage_booking 	
								JOIN ms_customer_h23 ON tr_h2_manage_booking.id_customer=ms_customer_h23.id_customer
								JOIN ms_tipe_kendaraan ON ms_customer_h23.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
								JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
								JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=tr_h2_manage_booking.id_type
								WHERE  
								(nama_customer = '$nama_konsumen' OR no_hp = '$no_hp' OR no_polisi = '$no_polisi') 
								AND tr_h2_manage_booking.tgl_servis='$tanggal'
								AND id_booking NOT IN (SELECT id_booking FROM tr_h2_sa_form)
			");
			$result = [
				'row' => $booking->num_rows(),
				'data' => $booking->result()
			];
		} elseif ($jenis_customer == 'reguler') {
			$result = $this->cekKonsumen($nama_konsumen, $no_hp, $no_polisi, $no_mesin);
		}
		echo json_encode($result);
	}
	public function save()
	{
		$post           = $this->input->post();
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->m_admin->cari_dealer();
		$customer_from  = $this->input->post('customer_from');
		$customer       = $this->input->post('customer');
		$edit_cust      = $this->input->post('edit_cust');
		$jenis_customer = $this->db->escape_str($this->input->post('jenis_customer'));
		$lengkap        = $this->input->post('lengkap');
		$id_antrian     = $this->m_h2->get_id_antrian($jenis_customer);

		if ($lengkap == '0') {
			$ins = [
				'id_antrian' => $id_antrian,
				'id_dealer' => $id_dealer,
				'tgl_servis' => get_ymd(),
				'waktu_kedatangan' => jam_menit(),
				'created_at'     => waktu_full(),
				'created_by'     => $login_id,
				'lengkap' => $this->input->post('lengkap'),
				'no_mesin_antri' => $this->input->post('no_mesin_antri'),
				'no_polisi_antri' => $this->input->post('no_polisi_antri'),
				'jenis_customer' => $this->input->post('jenis_customer'),
				'status_monitor' => 'antrian',
			];
			$this->db->trans_begin();
			$this->db->insert('tr_h2_sa_form', $ins);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$rsp = [
					'status' => 'error',
					'pesan' => ' Something went wrong'
				];
			} else {
				$this->db->trans_commit();
				$rsp = [
					'status' => 'sukses',
					'link' => base_url('dealer/manage_queue')
				];
				$_SESSION['pesan'] 	= "Queue no. successfully created";
				$_SESSION['tipe'] 	= "success";
			}
			send_json($rsp);
		}

		$ins = [
			'id_dealer'      => $id_dealer,
			'id_antrian'     => $id_antrian,
			'tgl_servis'     => $this->input->post('tgl_servis'),
			'jam_servis'     => $this->input->post('jam_servis'),
			'jenis_customer' => $jenis_customer,
			'id_type'        => $this->input->post('id_type'),
			// 'nama_pembawa'   => $this->input->post('nama_pembawa'),
			'keluhan_konsumen'   => $this->input->post('keluhan'),
			'created_at'     => waktu_full(),
			'created_by'     => $login_id,
			'waktu_kedatangan' => jam_menit(),
			'status_monitor' => 'antrian',
		];
		if ($customer_from == 'h23' || $customer_from == 'booking') {
			$id_customer = $ins['id_customer'] = $customer['id_customer'];
			if ($customer_from == 'booking') {
				$ins['id_booking'] = $customer['id_booking'];
				// Cek Booking Apakah Dari Customer App
				$book = $this->db->query("SELECT id_booking 
								FROM tr_h2_manage_booking WHERE id_booking='{$customer['id_booking']}'")->row();
				if ($book != null) {
					$upd_booking_for_cust_app = ['customer_apps_status' => 1, 'updated_at' => waktu_full()];
				}
			}
			if ($edit_cust == 1) {
				$upd_cust = update_customer($customer);
				$upd_cust['id_dealer']  = $id_dealer;
				$upd_cust['updated_at'] = waktu_full();
				$upd_cust['updated_by'] = $login_id;
			}
		} elseif ($customer_from == 'h1' || $customer_from == 'baru') {
			$id_tipe_kendaraan = $customer['id_tipe_kendaraan'];
			
			//Cek apakah customer EV atau tidak
			$tipe_motor = $this->db->query("SELECT id_kategori from ms_tipe_kendaraan mtk where id_tipe_kendaraan='$id_tipe_kendaraan'")->row_array();
			if($tipe_motor['id_kategori'] == 'EV'){
				$id_customer = $ins['id_customer'] = $this->m_h2->get_id_customer_ev();
				$is_ev = 1;
			}else{
				$id_customer = $ins['id_customer'] = $this->m_h2->get_id_customer();
				$is_ev = null;
			}
			$ins_cust = insert_customer($customer);
			
			$ins_cust['is_ev']  = $is_ev;
			// $id_customer = $ins['id_customer'] = $this->m_h2->get_id_customer();

			// $ins_cust = insert_customer($customer);
			$ins_cust['id_customer'] = $id_customer;
			$ins_cust['id_dealer']   = $id_dealer;
			$ins_cust['created_at']  = waktu_full();
			$ins_cust['created_by']  = $login_id;

			$id_dealer_cus = $ins_cust['id_dealer'];
			if ($id_dealer_cus == '' || $id_dealer_cus == null || $id_dealer_cus == 0) {
				$result = ['status' => 'error', 'pesan' => 'ID Dealer kosong'];
				send_json($result);
			}
		}
		//Cek KPB
		$post = $this->input->post();
		if ($post['id_type'] == 'ASS1' || $post['id_type'] == 'ASS2' || $post['id_type'] == 'ASS3' || $post['id_type'] == 'ASS4') {
			if (empty($customer['no_mesin']) && empty($customer['id_type']) && empty($customer['tgl_pembelian'] && empty($customer['id_tipe_kendaraan']))) {
				$err = 1;
			} elseif ($customer['no_mesin'] == '' && $customer['id_type'] == '' && $customer['tgl_pembelian'] == '' && $customer['id_tipe_kendaraan'] == '') {
				$err = 1;
			}
			if (isset($err)) {
				$rsp = ['status' => 'error', 'pesan' => 'Pastikan no. mesin, tipe servis, tanggal pembelian dan tipe kendaraan tidak kosong. Karena digunakan untuk pengecekan KPB.'];
				send_json($rsp);
			}
			$params = [
				'kpb_ke' => $post['id_type'],
				'id_tipe_kendaraan' => $customer['id_tipe_kendaraan'],
				'no_mesin' => $customer['no_mesin'],
				'tgl_pembelian' => $customer['tgl_pembelian'],
			];
			$resp = $this->m_h2->cekKPB($params);
			if ($resp['status'] != 'oke') {
				$result = ['status' => 'error', 'pesan' => $resp['msg']];
				send_json($result);
			}
		}
		// $test = [
		// 	'ins' => $ins,
		// 	'ins_cust' => isset($ins_cust) ? $ins_cust : null,
		// 	'upd_cust' => isset($upd_cust) ? $upd_cust : null,
		// 	'customer' => $customer
		// ];
		// send_json($test);
		$this->db->trans_begin();
		$this->db->insert('tr_h2_sa_form', $ins);
		if (isset($ins_cust)) {
			$this->db->insert('ms_customer_h23', $ins_cust);
		}
		if (isset($upd_cust)) {
			$this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $id_customer]);
		}
		if (isset($upd_booking_for_cust_app)) {
			$this->load->library('mokita');
			$this->load->model('m_h2_booking');
			$this->db->update('tr_h2_manage_booking', $upd_booking_for_cust_app, ['id_booking' => $ins['id_booking']]);
			$request = $this->m_h2_booking->customer_app_booking_checkin($ins['id_booking']);
			$this->mokita->booking_checkin($request);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/manage_queue')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function cekRiwayatServis()
	{
		$id_customer = $this->input->post('id_customer');
		$result = $this->db->query("SELECT tr_h2_manage_booking.*,nama_jasa FROM tr_h2_manage_booking 
				JOIN ms_jasa_servis ON tr_h2_manage_booking.id_jasa_servis=ms_jasa_servis.id_jasa_servis
				WHERE id_customer='$id_customer'")->result();
		echo json_encode($result);
	}

	// public function approval_save()
	// {		
	// 	$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$tgl      = gmdate("y-m-d", time()+60*60*7);
	// 	$login_id = $this->session->userdata('id_user');
	// 	$tabel    = $this->tables;
	// 	$id_event       = $this->input->get('id');
	// 	$cek      = $this->db->get_where('ms_event',['id_event'=>$id_event,'status'=>'waiting_approval']);
	// 	if($cek->num_rows() == 1){						
	// 		$data['status']      = "approved";
	// 		$data['approved_at'] = $waktu;		
	// 		$data['approved_by'] = $login_id;	
	// 		$this->db->update('ms_event',$data,['id_event'=>$id_event]);
	// 		$_SESSION['pesan'] 	= "Data has been approved successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/event'>";
	// 	}else{
	// 		$_SESSION['pesan'] 	= "Data not found !";
	// 		$_SESSION['tipe'] 	= "danger";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/event'>";
	// 	}
	// }
	public function cancel_booking()
	{
		$waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id      = $this->session->userdata('id_user');
		$tabel         = $this->tables;
		$id_booking    = $this->input->get('id');
		$alasan_cancel = $this->input->get('alasan_cancel');
		$cek           = $this->db->get_where('tr_h2_manage_booking', ['id_booking' => $id_booking]);
		if ($cek->num_rows() == 1) {
			$data['status']        = "cancel";
			$data['alasan_cancel'] = $alasan_cancel;
			$data['cancel_at']     = $waktu;
			$data['cancel_by']     = $login_id;
			$this->db->update('tr_h2_manage_booking', $data, ['id_booking' => $id_booking]);
			$_SESSION['pesan'] 	= "Data has been cancelled successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		}
	}

	public function fetch_kelurahan()
	{
		$fetch_data = $this->make_query_kel();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array     = array();
			$sub_array[] = $rs->id_kelurahan;
			$sub_array[] = $rs->kelurahan;
			$sub_array[] = $rs->kecamatan;
			$sub_array[] = $rs->kabupaten;
			$sub_array[] = $rs->provinsi;
			$row         = json_encode($rs);
			$link        = '<button data-dismiss=\'modal\' onClick=\'return pilihKelurahan(' . $row . ')\' class="btn btn-success btn-xs btn-flat"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data_kel(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query_kel($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_kelurahan', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY kelurahan ASC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "";

		if ($search != '') {
			$searchs .= "AND (id_kelurahan LIKE '%$search%' 
	          OR kelurahan LIKE '%$search%'
	          OR kecamatan LIKE '%$search%'
	          OR kabupaten LIKE '%$search%'
	          OR provinsi LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("SELECT kel.*,kecamatan,ms_kecamatan.id_kecamatan,kabupaten,ms_kabupaten.id_kabupaten,provinsi,ms_provinsi.id_provinsi
   			FROM ms_kelurahan  AS kel
   			JOIN ms_kecamatan ON kel.id_kecamatan=ms_kecamatan.id_kecamatan
   			JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
   			JOIN ms_provinsi ON ms_provinsi.id_provinsi=ms_kabupaten.id_provinsi
   		 $searchs $order $limit ");
	}
	function get_filtered_data_kel()
	{
		return $this->make_query_kel('y')->num_rows();
	}

	function fetchTypeServis()
	{
		$search = $this->input->get('q');
		$query  = $this->db->query("SELECT id_type,deskripsi FROM ms_h2_jasa_type WHERE id_type LIKE '%$search%' OR deskripsi LIKE '%$search%'")->result();
		echo json_encode(['items' => $query]);
	}

	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$this->template($data);
	}

	public function fetch_history()
	{
		$fetch_data = $this->make_query_history();
		$data       = array();
		$tanggal    = gmdate("Y-m-d", time() + 60 * 60 * 7);

		foreach ($fetch_data->result() as $rs) {
			$status = '';
			if ($rs->status_form == 'cancel') {
				$status = '<span class="label label-danger">Canceled</span>';
			}
			$sub_array   = array();
			// $sub_array[] = "<a href=\"" . site_url('dealer/manage_booking/detail?id=' . $rs->id_booking) . "\">$rs->id_booking</a>";
			$sub_array[] = $rs->id_antrian;
			$sub_array[] = date_dmy($rs->tgl_servis);
			$sub_array[] = $rs->jenis_customer;
			$sub_array[] = $rs->no_polisi;
			$sub_array[] = $rs->nama_customer;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->tahun_produksi;
			// $sub_array[] = $status;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->make_query_history(true),
			"data"            => $data
		);
		echo json_encode($output);
	}

	public function make_query_history($no_limit = null)
	{
		$date   = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";

		if ($no_limit == 'y') $limit = '';

		$filter = [
			'search' => $this->input->post('search')['value'],
			'limit' => $limit,
			'order' => isset($_POST['order']) ? $_POST["order"] : '',
			// 'id_sa_form_not_null' => 1,
			// 'tgl_servis_lebih_kecil' => $date
		];
		if ($no_limit != null) {
			return $this->m_bk->fetch_historyQueue($filter)->num_rows();
		} else {
			return $this->m_bk->fetch_historyQueue($filter);
		}
	}

	public function edit()
	{
		$id_antrian = $this->db->escape_str($this->input->get('id'));
		$data['isi']     = $this->page;
		$data['title']   = 'Edit ' . $this->title;
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$filter = ['id_antrian' => $id_antrian];
		$row = $this->m_bk->getQueue($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$filter = ['id_customer' => $row->id_customer];
			$data['jenis_customer'] = $row->jenis_customer;

			if ($row->lengkap != '0') {
				$data['customer_from'] = 'h23';
				$data['customer'] = $this->m_api->getCustomerH23($filter)->row();
			}
			// send_json($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_queue'>";
		}
		$this->db->order_by('nama_jasa', 'ASC');
		$data['jasa'] = $this->db->get('ms_jasa_servis');
		$this->template($data);
	}
	public function save_edit()
	{
		$waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$post = $this->input->post();
		$login_id      = $this->session->userdata('id_user');
		$id_dealer     = $this->m_admin->cari_dealer();
		$customer_from = $this->input->post('customer_from');
		$customer      = $this->input->post('customer');
		$edit_cust     = $this->input->post('edit_cust');
		// $jenis_customer  = $this->input->post('jenis_customer');
		$id_antrian = $this->input->post('id_antrian');
		$lengkap = $this->input->post('lengkap');

		if ($lengkap == '0') {
			$cond = ['id_antrian' => $id_antrian];
			$update = [
				'tgl_servis'       => get_ymd(),
				'waktu_kedatangan' => jam_menit(),
				'created_at'       => waktu_full(),
				'created_by'       => $login_id,
				'lengkap'          => $this->input->post('lengkap'),
				'no_mesin_antri'   => $this->input->post('no_mesin_antri'),
				'no_polisi_antri'  => $this->input->post('no_polisi_antri'),
			];
			// send_json($update);
			$this->db->trans_begin();
			$this->db->update('tr_h2_sa_form', $update, $cond);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$rsp = [
					'status' => 'error',
					'pesan' => ' Something went wrong'
				];
			} else {
				$this->db->trans_commit();
				$rsp = [
					'status' => 'sukses',
					'link' => base_url('dealer/manage_queue')
				];
				$_SESSION['pesan'] 	= "Queue no. successfully created";
				$_SESSION['tipe'] 	= "success";
			}
			send_json($rsp);
		}

		$upd = [
			'tgl_servis'     => date_ymd($this->input->post('tgl_servis')),
			'jam_servis'     => $this->input->post('jam_servis'),
			// 'jenis_customer' => $jenis_customer,
			'id_type'        => $this->input->post('id_type'),
			// 'nama_pembawa'   => $this->input->post('nama_pembawa'),
			'keluhan_konsumen'   => $this->input->post('keluhan'),
			'updated_queue_at'     => $waktu,
			'updated_queue_by'     => $login_id,
		];
		if ($customer_from == 'h23' || $customer_from == 'booking') {
			$id_customer = $upd['id_customer'] = $customer['id_customer'];
			if ($customer_from == 'booking') {
				$upd['id_booking'] = $customer['id_booking'];
			}
			if ($edit_cust == 1) {
				$upd_cust = update_customer($customer);
				$upd_cust['id_dealer']  = $id_dealer;
				$upd_cust['updated_at'] = $waktu;
				$upd_cust['updated_by'] = $login_id;
			}
		} elseif ($customer_from == 'h1' || $customer_from == 'baru') {
			$id_customer = $upd['id_customer'] = $this->m_h2->get_id_customer();

			$ins_cust = insert_customer($customer);
			$ins_cust['id_customer'] = $id_customer;
			$ins_cust['id_dealer']   = $id_dealer;
			$ins_cust['created_at']  = $waktu;
			$ins_cust['created_by']  = $login_id;
		}
		//Cek KPB
		$post = $this->input->post();
		if ($post['id_type'] == 'ASS1' || $post['id_type'] == 'ASS2' || $post['id_type'] == 'ASS3' || $post['id_type'] == 'ASS4') {
			if (empty($customer['no_mesin']) && empty($customer['id_type']) && empty($customer['tgl_pembelian'] && empty($customer['id_tipe_kendaraan']))) {
				$err = 1;
			} elseif ($customer['no_mesin'] == '' && $customer['id_type'] == '' && $customer['tgl_pembelian'] == '' && $customer['id_tipe_kendaraan'] == '') {
				$err = 1;
			}
			if (isset($err)) {
				$rsp = ['status' => 'error', 'pesan' => 'Pastikan no. mesin, tipe servis, tanggal pembelian dan tipe kendaraan tidak kosong. Karena digunakan untuk pengecekan KPB.'];
				send_json($rsp);
			}
			$params = [
				'kpb_ke' => $post['id_type'],
				'id_tipe_kendaraan' => $customer['id_tipe_kendaraan'],
				'no_mesin' => $customer['no_mesin'],
				'tgl_pembelian' => $customer['tgl_pembelian'],
			];
			$resp = $this->m_h2->cekKPB($params);
			if ($resp['status'] != 'oke') {
				$result = ['status' => 'error', 'pesan' => $resp['msg']];
				send_json($result);
			}
		}
		// $test = [
		// 	'upd' => $upd,
		// 	'ins_cust' => isset($ins_cust) ? $ins_cust : null,
		// 	'upd_cust' => isset($upd_cust) ? $upd_cust : null,
		// 	'customer' => $customer
		// ];
		// send_json($test);
		$this->db->trans_begin();
		$this->db->update('tr_h2_sa_form', $upd, ['id_antrian' => $id_antrian]);
		if (isset($ins_cust)) {
			$this->db->insert('ms_customer_h23', $ins_cust);
		}
		if (isset($upd_cust)) {
			$this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $id_customer]);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/manage_queue')
			];
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
}
