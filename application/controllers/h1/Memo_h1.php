<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Memo_h1 extends CI_Controller
{
	var $tables = "tr_spk";
	var $folder = "h1";
	var $page   = "memo_h1";
	var $isi    = "memo_h1";
	var $pk     = "h1";
	var $title  = "Perbaikan Memo H1";

	var $table_prospek  = "tr_prospek";
	var $table_po_indent  = "tr_po_dealer_indent";
	var $table_os  = "tr_po_dealer_indent";
	var $table_hs  = "tr_po_dealer_indent";
	var $table_spk 		= "tr_spk";
	var $table_so 		= "tr_sales_order";
	var $table_cdb 		= "tr_cdb";
	var $table_cdb_kk 	= "tr_cdb_kk";
	var $table_invoice_tjs	= "tr_invoice_tjs";

	var $table_inv 		= "tr_invoice_pelunasan";
	var $table_faktur 	= "tr_faktur_stnk_detail";
	var $table_bbn 		= "tr_pengajuan_bbn_detail";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_memo_h1');	
		$this->load->model('m_kelurahan');
		//===== Load Library =====
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}

	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']       = $this->isi;
		$data['title']	   = $this->title;
		$data['page']  	   = $this->page;
		$data['set']	   = "view";
		$data['dt_pernikahan'] 	     	= $this->m_admin->getSortCond("ms_status_pernikahan","id_status_pernikahan","ASC");		
		$data['dt_sub_pekerjaan'] 		= $this->m_admin->getSortCond("ms_sub_pekerjaan","id_sub_pekerjaan","ASC");		
		$data['dt_pekerjaan_kk'] 		= $this->m_admin->getSortCond("ms_pekerjaan_kk","id_pekerjaan","ASC");		
		$data['dt_pekerjaan'] 		    = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");												
		$data['dt_agama'] 				= $this->m_admin->getSortCond("ms_agama","id_agama","ASC");												
		$data['dt_pendidikan'] 			= $this->m_admin->getActData("ms_pendidikan","id_pendidikan","ASC");								
		$data['dt_merk_sebelumnya'] 	= $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");								
		$data['dt_jenis_sebelumnya'] 	= $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");								
		$data['dt_digunakan'] 			= $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");															
		$data['dt_hobi'] 				= $this->m_admin->getSortCond("ms_hobi","hobi","ASC");	
		$data['dt_pendidikan'] 			= $this->m_admin->getSortCond("ms_pendidikan","pendidikan","ASC");	
		$data['dt_pengeluaran'] 	    = $this->m_admin->getSortCond("ms_pengeluaran_bulan","pengeluaran","ASC");	
		$this->template($data);
	}

	public function get_data_wilayah() {

	}

	public function get_data_master()
	{
		$data['status_pernikahan']= $this->db->query("SELECT id_status_pernikahan,status_pernikahan FROM  ms_status_pernikahan ")->result();
		$data['pekerjaan']= $this->db->query("SELECT id_pekerjaan, pekerjaan  FROM  ms_pekerjaan mp WHERE active ='1'")->result();
		$data['sub_pekerjaan']= $this->db->query("SELECT id_sub_pekerjaan,id_pekerjaan,sub_pekerjaan from ms_sub_pekerjaan WHERE active ='1'")->result();
		$data['pekerjaan_kk']= $this->db->query("SELECT id_pekerjaan, pekerjaan from ms_pekerjaan_kk mpk WHERE active ='1'")->result();
		$data['agama']= $this->db->query("SELECT id_agama, agama from ms_agama mpk WHERE active ='1'")->result();
		echo json_encode($data);
	}

	
	// memo #1
	public function update_set_value()
	{
		$velue_set       = $data = $this->input->post('data');

		$data['nosin']   =  $this->input->post('nosin');
		$data['prospek'] =  $this->input->post('prospek');
		$data['spk']     =  $this->input->post('spk');

		if(isset($data['po_check_update'])){
			$update['po'] = $data['po_check_update'];
		}

		if(isset($data['nik_check_update'])){
			$update['nik'] = $data['nik_check_update'];
		}
		
		if(isset($data['nama_check_update'])){
			$update['set_nama'] = $data['nama_check_update'];
		}

		if(isset($data['tempat_lahir_check_update'])){
			$update['set_tempat_lahir'] = $data['tempat_lahir_check_update'];
		}

		if(isset($data['tanggal_lahir_check_update'])){
			$update['set_tanggal_lahir'] = $data['tanggal_lahir_check_update'];
		}

		if(isset($data['jenis_kelamin_check_update'])){
			$update['set_jenis_kelamin'] = $data['jenis_kelamin_check_update'];
		}

		if(isset($data['alamat_check_update'])){
			$update['set_alamat'] = $data['alamat_check_update'];
		}

		if(isset($data['rt_check_update'])){
			$update['set_rt'] = $data['rt_check_update'];
		}

		if(isset($data['rw_check_update'])){
			$update['set_rw'] = $data['rw_check_update'];
		}

		if(isset($data['kelurahan_check_update'])){
			$update['set_kelurahan'] = $data['kelurahan_check_update'];
		}

		if(isset($data['kecamatan_check_update'])){
			$update['set_kecamatan'] = $data['kecamatan_check_update'];
		}

		if(isset($data['kabupaten_check_update'])){
			$update['set_kabupaten'] = $data['kabupaten_check_update'];
		}

		if(isset($data['provinsi_check_update'])){
			$update['set_provinsi'] = $data['provinsi_check_update'];
		}

		if(isset($data['agama_check_update'])){
			$update['set_agama'] = $data['agama_check_update'];
		}


		if(isset($data['status_pernikahan_check_update'])){
			$update['set_status_perkawinan'] = $data['status_pernikahan_check_update'];
		}

		if(isset($data['pekerjaan_check_update'])){
			$update['set_pekerjaan_sub'] = $data['pekerjaan_check_update'];
		}

		if(isset($data['pekerjaan_kk_check_update'])){
			$update['set_pekerjaan_kk'] = $data['pekerjaan_kk_check_update'];
		}
			$this->update_all_value($data);

	}



	public function update_all_value($data)
	{
		$nosin = $data['nosin'];
		$id_prospek = $data['prospek'];
		$id_spk = $data['spk'];
		// CHECK KONDISI TABLE 
		$select = $this->db->query("SELECT
		spk.no_spk, spk.jenis_beli,
		sum(case when pro.id_customer is not null then 1 else null end) as pro,
		sum(case when spk.no_spk is not null then 1 else null end) as spk,
		sum(case when os.no_spk is not null then 1 else null end) as os,
		sum(case when hs.no_spk is not null then 1 else null end) as hs,
		sum(case when poind.id_spk is not null then 1 else null end) as indent,
		sum(case when so.no_spk is not null then 1 else null end) as so,
		sum(case when cdb.no_spk is not null then 1 else null end) as cdb,
		sum(case when cdbkk.no_spk is not null then 1 else null end) as cdb_kk,
		sum(case when tjs.id_spk is not null then 1 else null end) as tjs,
		sum(case when dp.id_spk is not null then 1 else null end) as dp,
		sum(case when tinv1.id_spk is not null then 1 else null end) as tinv1,
		sum(case when delv.id_sales_order is not null then 1 else null end) as delv,
		sum(case when del.id_generate  is not null then 1 else null end) as del,
		sum(case when fakt.id_sales_order is not null then 1 else null end) as fakt,
		sum(case when bbn.no_mesin is not null then 1 else null end) as bbn,
		case when bbn.id_generate  is not null then 1 else null end as generate
		from tr_spk spk  
		left join tr_order_survey os on os.no_spk = spk.no_spk 
		left join tr_order_survey hs on hs.no_spk = spk.no_spk and hs.no_order_survey = os.no_order_survey 
		left join tr_prospek pro on pro.id_customer = spk.id_customer 
		left join tr_po_dealer_indent poind on poind.id_spk = spk.no_spk 
		left join tr_sales_order so on spk.no_spk= so.no_spk 
		left join tr_cdb cdb on cdb.no_spk=spk.no_spk 
		left join tr_cdb_kk cdbkk on cdbkk.no_spk = spk.no_spk 
		left join tr_invoice_dp dp on dp.id_spk = so.no_spk
		left join tr_invoice_tjs tjs on tjs.id_spk = so.no_spk 
		left join tr_invoice_pelunasan tinv1 on tinv1.id_spk = so.no_spk
		left join tr_generate_list_unit_delivery_detail delv on delv.id_sales_order =so.id_sales_order 
		left join tr_generate_list_unit_delivery del on del.id_generate = delv.id_generate 
		left join tr_faktur_stnk_detail fakt on fakt.id_sales_order =so.id_sales_order 
		left join tr_pengajuan_bbn_detail bbn on bbn.no_mesin  = so.no_mesin 
		where so.no_mesin ='$nosin'
		group by spk.no_spk ")->row();



if(isset($data['agama_check_update'])){
	$prospek['agama'] 		= $data['alamat_check_update'];			
	$cdb['agama']     		= $data['alamat_check_update'];
	$cdb_kk['id_agama']     		= $data['alamat_check_update'];
}


		if(isset($data['jenis_kelamin_check_update'])){
			if ($data['jenis_kelamin_check_update'] == 1){
				$prospek['jenis_kelamin'] ='Pria';
				$cdb_kk['jk'] 		      =1;
			}else{
				$cdb_kk['jenis_kelamin']  = 2;
				$prospek['jenis_kelamin'] ='Wanita';
			}
		}

		if(isset($data['nik_check_update'])){
			$prospek['no_ktp'] 		= $data['nik_check_update'];			
			$prospek['no_kk'] 		= $data['nik_check_update'];
			$spk['no_ktp']     		= $data['nik_check_update'];
			$spk['no_kk']           = $data['nik_check_update'];
			$spk['no_ktp_bpkb']     = $data['nik_check_update'];
			$os['no_ktp']        	= $data['nik_check_update'];
			$invoice_pelunasan['no_ktp']   = $data['nik_check_update'];
			$faktur_stnk_detail['no_ktp']  = $data['nik_check_update'];
			$faktur_stnk_detail['no_kk']   = $data['nik_check_update'];
		}


		if(isset($data['alamat_check_update'])){
			$prospek['alamat'] 		= $data['alamat_check_update'];			
			$spk['alamat']     		= $data['alamat_check_update'];
			$spk['alamat_ktp_bpkb'] = $data['alamat_check_update'];
			$spk['alamat_kk']     	= $data['alamat_check_update'];
			$spk['alamat2']     	= $data['alamat_check_update'];
			$os['alamat']     		= $data['alamat_check_update'];
			$os['alamat_2']     	= $data['alamat_check_update'];
			$os['alamat_penjamin']  = $data['alamat_check_update'];
			$so['lokasi_pengiriman']       = $data['alamat_check_update'];
			$invoice_pelunasan['alamat']   = $data['alamat_check_update'];
			$faktur_stnk_detail['alamat']  = $data['alamat_check_update'];
			$pengajuan_bbn_detail['alamat']= $data['alamat_check_update'];
		}

		
		if(isset($data['nama_check_update'])){
			$prospek['nama_konsumen'] 	= $data['nama_check_update'];
			$spk['nama_bpkb']     		= $data['nama_check_update'];
			$spk['nama_konsumen']     	= $data['nama_check_update'];
			$po_indent['nama_konsumen'] = $data['nama_check_update'];
			$os['nama_konsumen'] 		= $data['nama_check_update'];
			$cdb_kk['nama_lengkap']     = $data['nama_check_update'];
			$os['nama_konsumen']     	= $data['nama_check_update'];
			$so['nama_penerima']        = $data['nama_check_update'];
			$invoice_pelunasan['nama_konsumen']   = $data['nama_check_update'];
			$faktur_stnk_detail['nama_konsumen']  = $data['nama_check_update'];
			$pengajuan_bbn_detail['nama_konsumen']= $data['nama_check_update'];
		}


		if(isset($data['tanggal_lahir_check_update'])){
			$cdb_kk['tgl_lahir']              = $data['tanggal_lahir_check_update'];
			$os['tgl_lahir']				  = $data['tanggal_lahir_check_update'];
			$pengajuan_bbn_detail['tgl_lahir']= $data['tanggal_lahir_check_update'];
		}

		if(isset($data['tempat_lahir_check_update'])){
			$cdb_kk['tempat_lahir'] 	         = $data['tempat_lahir_check_update'];	
			$prospek['tempat_lahir'] 	         = $data['tempat_lahir_check_update'];	
			$os['tempat_lahir'] 	         	 = $data['tempat_lahir_check_update'];	
			$pengajuan_bbn_detail['tempat_lahir']= $data['tempat_lahir_check_update'];
		}

		if(isset($data['rt_check_update'])){
			$spk['rt']        = $data['rt_check_update'];
		}

		if(isset($data['rw_check_update'])){
			$spk['rw']        = $data['rw_check_update'];
		}


		if(isset($data['kecamatan_check_update'])){

			$prospek['id_kelurahan'] 	   = $data['kelurahan_check_update'];	
			$prospek['id_kecamatan']       = $data['kecamatan_check_update'];
			$prospek['id_kabupaten']       = $data['kabupaten_check_update'];
			$prospek['id_provinsi']        = $data['provinsi_check_update'];
			$prospek['kodepos']            = $data['kodepos_check_update'];

			$os['id_kelurahan']       = $data['kelurahan_check_update'];
			$os['id_kelurahan2']      = $data['kelurahan_check_update'];
			$os['id_kelurahan_bpkb']  = $data['kelurahan_check_update'];
			$os['id_kecamatan']       = $data['kecamatan_check_update'];
			$os['id_kecamatan2']      = $data['kecamatan_check_update'];
			$os['id_kabupaten']       = $data['kabupaten_check_update'];
			$os['id_kabupaten2']      = $data['kabupaten_check_update'];
			$os['id_provinsi']        = $data['provinsi_check_update'];
			$os['kodepos']            = $data['kodepos_check_update'];
			$os['kodepos2']           = $data['kodepos_check_update'];

			$spk['id_kelurahan']       = $data['kelurahan_check_update'];
			$spk['id_kelurahan2']      = $data['kelurahan_check_update'];
			$spk['id_kelurahan_bpkb']  = $data['kelurahan_check_update'];
			$spk['id_kecamatan']       = $data['kecamatan_check_update'];
			$spk['id_kecamatan2']      = $data['kecamatan_check_update'];
			$spk['id_kabupaten']       = $data['kabupaten_check_update'];
			$spk['id_kabupaten2']      = $data['kabupaten_check_update'];
			$spk['id_provinsi']        = $data['provinsi_check_update'];
			$spk['kodepos']            = $data['kodepos_check_update'];
			$spk['kodepos2']           = $data['kodepos_check_update'];
			$pengajuan_bbn_detail['id_kelurahan']= $data['kelurahan_check_update'];
		}

		if(isset($data['pekerjaan_check_update'])){
			$spk['pekerjaan']      = $data['pekerjaan_check_update'];
			$prospek['pekerjaan']            = $data['pekerjaan_check_update'];
		}

		
		if(isset($data['sub_pekerjaan_check_update'])){
			$prospek['sub_pekerjaan']            = $data['sub_pekerjaan_check_update'];
		}

				
		if(isset($data['sub_pekerjaan_kk_check_update'])){
			$cdb_kk['id_pekerjaan']              = $data['sub_pekerjaan_kk_check_update'];
		}

		if(isset($data['email_check_update'])){
			$spk['email']       = $data['email_check_update'];
			$prospek['email']   = $data['email_check_update'];
			$os['email']        = $data['email_check_update'];
		}

		// var_dump($data['email_check_update']);
		// die();


		if(isset($data['jenis_beli'])== 'Kredit'){
			var_dump('test');
			die();
		}


// KONDISI TABLE CHECK (UPDATE DATA)

$checking     = array();
$error_update = array();

		if(isset($select->pro)==1){
			if ($prospek !== null) {
				$check_table['prospek'] = 1;
				$table = $this->table_prospek;
				$pk    ='id_prospek';
				$where = $id_prospek;
				$checking[] = array($table, $prospek, $pk, $where);
				if ($table !== null && $table !== '' &&  $prospek !== null && $prospek !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
					$this->m_admin->update($table, $prospek, $pk, $where);
				} else {
					$error_update[] ='Gagal Diupdate';
				}
			}
		}

		// DOUBLE SPK
		// if(isset($select->indent)==1){
		// 	if ($prospek !== null) {
		// 		$check_table['po_indent'] = 1;
		// 		$table = $this->table_po_indent;
		// 		$pk    ='id_spk';
		// 		$where = $id_spk;
		// 		$checking[] = array($table, $po_indent, $pk, $where);
		// 		if ($table !== null && $table !== '' &&  $po_indent !== null && $po_indent !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
		// 			$this->m_admin->update($table, $po_indent, $pk, $where);
		// 		} else {
		// 			$error_update[] ='Gagal Diupdate';
		// 		}
		// 	}
		// }

		
		if(isset($select->spk)==1){
			if ($spk !== null) {
				$check_table['spk'] = 1;
				$table = $this->table_spk;
				$pk    ='no_spk';
				$where = $id_spk;
				$checking[] = array($table, $spk, $pk, $where);
				if ($table !== null && $table !== '' &&  $spk !== null && $spk !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
							$this->m_admin->update($table, $spk, $pk, $where);
				} else {
					$error_update[] ='Gagal Diupdate';
				}
			}

		}


		if(isset($select->so)==1){
			if ($so !== null) {
				$check_table['so'] = 1;
				$table = $this->table_so;
				$pk    ='no_spk';
				$where = $id_spk;
				$checking[] = array($table, $so, $pk, $where);
				// die();

				if ($table !== null && $table !== '' &&  $so !== null && $so !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
						$this->m_admin->update($table, $so, $pk, $where);
				} else {
					$error_update[] ='Gagal Diupdate';
				}
			}
		}

		if(isset($select->cdb_kk)==1){
			if ($cdb_kk !== null) {
				$check_table['cdb_kk'] = 1;
				$table = $this->table_cdb_kk;
				$pk    ='no_spk';
				$where = $id_spk;

				$checking[]= array($table, $cdb_kk, $pk, $where);

				if ($table !== null && $table !== '' &&  $cdb_kk !== null && $cdb_kk !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
				$this->m_admin->update($table, $cdb_kk, $pk, $where);
				} else {
					$error_update[] ='Gagal Diupdate';
				}
			}
		}


		// if(isset($select->tjs)==1){
		// 	if ($tjs !== null) {
		// 		$check_table['tjs'] = 1;
		// 		$table = $this->table_invoice_tjs;
		// 		$pk    ='no_spk';
		// 		$where = $id_spk;

		// 		$checking[]= array($table, $tjs, $pk, $where);

		// 		if ($table !== null && $table !== '' &&  $cdb_kk !== null && $cdb_kk !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
		// 		$this->m_admin->update($table, $cdb_kk, $pk, $where);
		// 		} else {
		// 			$error_update[] ='Gagal Diupdate';
		// 		}
		// 	}
		// }

		if(isset($select->tinv1)==1){
			if ($invoice_pelunasan !== null) {
				$check_table['invoice_pelunasan'] = 1;
				$table = $this->table_inv;
				$pk    ='id_spk';
				$where = $id_spk;
				$checking[]= array($table, $invoice_pelunasan, $pk, $where);
				// die();
				if ($table !== null && $table !== '' &&  $invoice_pelunasan !== null && $invoice_pelunasan !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
					$this->m_admin->update($table, $invoice_pelunasan, $pk, $where);
					} else {
						$error_update[] ='Gagal Diupdate';
					}
			}
		}

		if(isset($select->delv)==1){
			$check_table['delv'] = 1;
		}

		if(isset($select->fakt)==1){
			if ($pengajuan_bbn_detail !== null) {
				$check_table['faktur_stnk'] = 1;
				$table = $this->table_faktur;
				$pk    ='no_spk ';
				$where = $id_spk;
				$checking[]= array($table, $pengajuan_bbn_detail, $pk, $where);
				// die();
					if ($table !== null && $table !== '' &&  $pengajuan_bbn_detail !== null && $pengajuan_bbn_detail !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
					$this->m_admin->update($table, $pengajuan_bbn_detail, $pk, $where);
					} else {
						$error_update[] ='Gagal Diupdate';
					}
			}
		}

		if(isset($select->bbn)==1){
			if ($pengajuan_bbn_detail !== null) {
				$check_table['pengajuaan_bbn'] = 1;
				$table = $this->table_bbn;
				$pk    ='no_mesin';
				$where = $nosin;
				$checking[] = array($table, $pengajuan_bbn_detail, $pk, $where);
				// die();
					if ($table !== null && $table !== '' &&  $pengajuan_bbn_detail !== null && $pengajuan_bbn_detail !== '' &&  $pk !== null && $pk !== '' && $where !== null && $where !== '') {
					$this->m_admin->update($table, $so, $pk, $where);
					} else {
						$error_update[] ='Gagal Diupdate';
					}
			}
	
		}



		// $detail = [];

		// foreach ($update as $key => $value) {
		// 	$set_details=array(
		// 		'memo_id' 		=>  1,
		// 		'table' 		=> 'tr_prospek',
		// 		'key' 			=> $key,
		// 		'value' 	    => $value,  
		// 		'created_at' 	=> $currentDateTime,  
		// 	);
		// 	$detail[]=$set_details;
		// }

		// $this->create_update_log_activity_memo_detail($detail);


		// $this->output->set_content_type('application/json');
        // Encode the data as JSON and send it as the response
        // echo json_encode($check_table);


	}

	
	public function get_value_same()
	{
		$nomesin = $this->input->post('data');
		var_dump($nomesin);
		die();

	}


	public function get_data_first()
	{
		$nomesin = $this->input->post('nosin');
			if (!empty($nomesin)) {

				$select=$this->db->query("SELECT 
				pro.id_prospek,
				dl.nama_dealer,
				spk.*,so.no_mesin,so.id_sales_order
				 FROM tr_spk spk
				left join tr_prospek pro on pro.id_customer =  spk.id_customer 
				left join ms_dealer dl on dl.id_dealer = spk.id_dealer
				left join tr_cdb_kk cdbkk on spk.no_spk = cdbkk.no_spk 
				left join tr_sales_order so on so.no_spk = spk.no_spk 
				where  spk.no_mesin_spk ='$nomesin' limit 10 ");

				if ($select) {
					$rowCount = $select->num_rows();

					if ($rowCount > 0) {
						$row = $select->row();
						$filter = array(
							'no_spk' 			=> $row->no_spk,
							'no_mesin' 			=> $row->no_mesin,
							'prospek_status_1' 	=> NULL,      
							'spk_status_1' 		=> NULL,                 
							'cdb_status_1' 		=> NULL,                  
							'cdb_kk_status_1' 	=> NULL,      
							'so_dealer_status_1' 	=> NULL,      
							'invoice_pelunasan_status_1' 	=> NULL,    
							'faktur_stnk_detail_status_1' 	=> NULL,    
							'pengajuan_bbn_detail_status_1' => NULL,  
							'id_sales_order' 	=> NULL,               
							'nama_konsumen' 	=>  $row->nama_konsumen               
						);

						$this->create_update_log_activity_memo($filter);
					}

					$result = $select->row();
				} else {
					$result = array('error' => 'Database query error');
				}
			} else {
				$result = array('error' => 'Missing or empty input');
			}
			echo json_encode($result);
	}

	// memo History
	public function get_data_count_activity()
	{
		$nomesin = $this->input->post('nosin');
		$select = $this->db->query("SELECT 
		no_spk,
		no_mesin,
		prospek_status_1  as prospek,          
		spk_status_1   as spk,      
		so_dealer_status_1 as so,        
		cdb_status_1    as cdb,             
		cdb_kk_status_1    as cdb_kk,          
		invoice_pelunasan_status_1    as inv_1 ,  
		faktur_stnk_detail_status_1   as fak_stnk ,  
		pengajuan_bbn_detail_status_1 as bbn
		from ms_memo WHERE no_mesin ='$nomesin'")->row();
		echo json_encode($select);
	}

	public function getDatabaseFill()
	{
		$nomesin = $this->input->post('nosin');

		$select=$this->db->query("SELECT
		spk.no_spk, spk.jenis_beli,
		sum(case when pro.id_customer is not null then 1 else null end) as pro,
		sum(case when spk.no_spk is not null then 1 else null end) as spk,
		sum(case when sk.id_prospek is not null then 1 else null end) as sk,
		sum(case when os.no_spk is not null then 1 else null end) as os,
		sum(case when hs.no_spk is not null then 1 else null end) as hs,
		sum(case when ep.no_spk is not null then 1 else null end) as ep,
		sum(case when poind.id_spk is not null then 1 else null end) as po,
		sum(case when so.no_spk is not null then 1 else null end) as so,
		sum(case when cdb.no_spk is not null then 1 else null end) as cdb,
		sum(case when cdbkk.no_spk is not null then 1 else null end) as cdb_kk,
		sum(case when tjs.id_spk is not null then 1 else null end) as tjs,
		sum(case when dp.id_spk is not null then 1 else null end) as dp,
		sum(case when tinv1.id_spk is not null then 1 else null end) as tinv1,
		sum(case when delv.id_sales_order is not null then 1 else null end) as delv,
		sum(case when del.id_generate  is not null then 1 else null end) as del,
		sum(case when fakt.id_sales_order is not null then 1 else null end) as fakt,
		sum(case when bbn.no_mesin is not null then 1 else null end) as bbn,
		case when bbn.id_generate  is not null then 1 else null end as generate
		from tr_spk spk  
		left join tr_prospek pro on pro.id_customer = spk.id_customer 
		left join tr_skema_kredit sk on sk.id_prospek = pro.id_prospek 
		left join tr_order_survey os on os.no_spk = spk.no_spk 
		left join tr_hasil_survey hs on hs.no_spk = spk.no_spk AND os.no_order_survey = hs.no_order_survey
		left join tr_entry_po_leasing ep on ep.no_spk = spk.no_spk 
		left join tr_po_dealer_indent poind on poind.id_spk = spk.no_spk 
		left join tr_sales_order so on spk.no_spk= so.no_spk 
		left join tr_cdb cdb on cdb.no_spk=spk.no_spk 
		left join tr_cdb_kk cdbkk on cdbkk.no_spk = spk.no_spk 
		left join tr_invoice_dp dp on dp.id_spk = so.no_spk
		left join tr_invoice_tjs tjs on tjs.id_spk = so.no_spk 
		left join tr_invoice_pelunasan tinv1 on tinv1.id_spk = so.no_spk
		left join tr_generate_list_unit_delivery_detail delv on delv.id_sales_order =so.id_sales_order 
		left join tr_generate_list_unit_delivery del on del.id_generate = delv.id_generate 
		left join tr_faktur_stnk_detail fakt on fakt.id_sales_order =so.id_sales_order 
		left join tr_pengajuan_bbn_detail bbn on bbn.no_mesin  = so.no_mesin 
		where spk.no_mesin_spk ='$nomesin'
		group by spk.no_spk ")->row();


		header('Content-Type: application/json');
		echo json_encode($select);
	}


	public function create_update_log_activity_memo($set)
	{
		$no_mesin = $set['no_mesin'];
		$no_spk = $set['no_spk'];

		$this->db->where('no_mesin', $no_mesin);
		$this->db->where('no_spk', $no_spk);
		$query = $this->db->get('ms_memo');

		if ($query->num_rows() == 0) {
			$this->db->trans_start();
			$insert_result = $this->db->insert('ms_memo', $set);

			if ($insert_result !== false) {
				$this->db->trans_complete();
			} else {
				$this->db->trans_rollback();
			}
		} 
	}

	public function create_update_log_activity_memo_detail($set)
	{
		$this->db->trans_start();
		$insert_result = $this->db->insert_batch('ms_memo_detail', $set);
	
		if ($insert_result !== false) {
			$this->db->trans_complete();
		} else {
			$this->db->trans_rollback();
		}
	}

	public function get_data()
	{
		$nomesin = $this->input->post('no_mesin');
		$select=$this->db->query("select no_spk,id_customer from tr_spk WHERE no_mesin_spk ='$nomesin'")->row();

		$select_spk=$select->no_spk;
		$select_customer=$select->id_customer;
	    
		$jenis_menu = $this->input->post('menu');
		if (!empty($jenis_menu))  { $set_menu =$jenis_menu; }else{ $set_menu=NULL; }

		switch ($set_menu) {
			case "prospek":
			    return	$this->get_prospek($select_customer);
			break;
			case "spk":
				return	$this->get_spk($select_spk);
			  break;
			  case "cbd":
				return	$this->get_cdb($select_spk);
			  break;
			  case "cbd_kk":
				return	$this->get_cdb_kk($select_spk);
			  break;
			case "so":
				return	$this->get_sales_order($select_spk);
			  break;
			case "fak_stnk":
				return	$this->faktur_stnk_detail($select_spk);
			break;
			case "fak_stnk_detail":
				return	$this->faktur_stnk_detail($select_spk);
			break;
			case "fak_stnk_detail":
				return	$this->pengajuan_bbn_detail();
			break;
			case "inv_pelunasan":
				return	$this->invoice_pelunasan($select_spk);
			break;
			case "inv_pelunasan_recipt":
				return	$this->invoice_pelunasan_recipt();
			break;
			default:
			  echo "Eoooo";
		  }
		  
	}

	function get_prospek($select_customer){

		$prospek=$this->db->query("select * from tr_prospek where id_customer ='$select_customer'")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>Agama</th>";
		$output .= "<th scope='col'>Alamat</th>";
		$output .= "<th scope='col'>Email</th>";
		$output .= "<th scope='col'>ID Kabupaten</th>";
		$output .= "<th scope='col'>ID Kecamatan</th>";
		$output .= "<th scope='col'>ID Kelurahan</th>";
		$output .= "<th scope='col'>ID Provinsi</th>";
		$output .= "<th scope='col'>Kode Pos</th>";
		$output .= "<th scope='col'>Nama Konsumen</th>";
		$output .= "<th scope='col'>No HP</th>";
		$output .= "<th scope='col'>No KK</th>";
		$output .= "<th scope='col'>No KTP</th>";
		$output .= "<th scope='col'>Pekerjaan</th>";
		$output .= "<th scope='col'>Tempat Lahir</th>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($prospek as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->agama."</td>"; 
		$output.="<td>". $val->alamat."</td>"; 
		$output.="<td>". $val->email."</td>"; 
		$output.="<td>". $val->id_kabupaten."</td>"; 
		$output.="<td>". $val->id_kecamatan."</td>"; 
		$output.="<td>". $val->id_kelurahan."</td>"; 
		$output.="<td>". $val->id_provinsi."</td>"; 
		$output.="<td>". $val->kodepos."</td>"; 
		$output.="<td>". $val->nama_konsumen."</td>"; 
		$output.="<td>". $val->no_hp."</td>"; 
		$output.="<td>". $val->no_kk."</td>"; 
		$output.="<td>". $val->no_ktp."</td>"; 
		$output.="<td>". $val->pekerjaan."</td>"; 
		$output.="<td>". $val->tgl_lahir."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function get_spk($select_spk){
		$spk=$this->db->query("select * from tr_spk where no_spk='$select_spk' ")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>Alamat</th>";
		$output .= "<th scope='col'>Alamat 2</th>";
		$output .= "<th scope='col'>Alamat KK</th>";
		$output .= "<th scope='col'>Alamat KTP BPKB</th>";
		$output .= "<th scope='col'>Email</th>";
		$output .= "<th scope='col'>ID Kabupaten</th>";
		$output .= "<th scope='col'>ID Kabupaten 2</th>";
		$output .= "<th scope='col'>ID Kecamatan</th>";
		$output .= "<th scope='col'>ID Kecamatan 2</th>";
		$output .= "<th scope='col'>ID Kelurahan</th>";
		$output .= "<th scope='col'>ID Kelurahan 2</th>";
		$output .= "<th scope='col'>ID Kelurahan BPKB</th>";
		$output .= "<th scope='col'>ID Provinsi</th>";
		$output .= "<th scope='col'>ID Provinsi 2</th>";
		$output .= "<th scope='col'>Kode Pos</th>";
		$output .= "<th scope='col'>Kode Pos 2</th>";
		$output .= "<th scope='col'>Nama BPKB</th>";
		$output .= "<th scope='col'>Nama IBU</th>";
		$output .= "<th scope='col'>Nama Konsumen</th>";
		$output .= "<th scope='col'>No HP</th>";
		$output .= "<th scope='col'>No KK</th>";
		$output .= "<th scope='col'>No KTP</th>";
		$output .= "<th scope='col'>No KTP BPKB</th>";
		$output .= "<th scope='col'>Pekerjaan</th>";
		$output .= "<th scope='col'>Tempat Lahir</th>";
		$output .= "<th scope='col'>Tanggal Lahir</th>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($spk as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->alamat."</td>"; 
		$output.="<td>". $val->alamat."</td>"; 
		$output.="<td>". $val->email."</td>"; 
		$output.="<td>". $val->id_kabupaten."</td>"; 
		$output.="<td>". $val->id_kecamatan."</td>"; 
		$output.="<td>". $val->id_kelurahan."</td>"; 
		$output.="<td>". $val->id_provinsi."</td>"; 
		$output.="<td>". $val->kodepos."</td>"; 
		$output.="<td>". $val->nama_konsumen."</td>"; 
		$output.="<td>". $val->no_hp."</td>"; 
		$output.="<td>". $val->no_kk."</td>"; 
		$output.="<td>". $val->no_ktp."</td>"; 
		$output.="<td>". $val->pekerjaan."</td>"; 
		$output.="<td>". $val->tgl_lahir."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function get_cdb($select_spk){
	
		$cdb=$this->db->query("select * from tr_cdb cdb
		left join ms_hobi on cdb.hobi = ms_hobi.id_hobi  
		left join ms_agama on cdb.agama = ms_agama.id_agama 
		left join ms_pendidikan on cdb.pendidikan = ms_pendidikan.id_pendidikan where cdb.no_spk='$select_spk'")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>Agama</th>";
		$output .= "<th scope='col'>Hobi</th>";
		$output .= "<th scope='col'>Pendidikan</th>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($cdb as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->agama."</td>"; 
		$output.="<td>". $val->hobi."</td>"; 
		$output.="<td>". $val->pendidikan."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	
	function get_cdb_kk($select_spk){
	
		$cdb=$this->db->query("select * from tr_cdb_kk cdb
		left join ms_agama on cdb.id_agama = ms_agama.id_agama 
		left join ms_pendidikan on cdb.id_pendidikan = ms_pendidikan.id_pendidikan where cdb.no_spk='$select_spk'")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>ID Agama</th>";
		$output .= "<th scope='col'>ID Hubungan Keluarha</th>";
		$output .= "<th scope='col'>ID Pekerjaan</th>";
		$output .= "<th scope='col'>ID Pendidikan</th>";
		$output .= "<th scope='col'>ID Status Pernikahan</th>";
		$output .= "<th scope='col'>ID Jenis Kelamin</th>";
		$output .= "<th scope='col'>Nama Lengkap</th>";
		$output .= "<th scope='col'>NIK</th>";
		$output .= "<th scope='col'>Tempat Lahir</th>";
		$output .= "<th scope='col'>Tanggal Lahir</th>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($cdb as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->agama."</td>"; 
		$output.="<td>". $val->id_hub_keluarga."</td>"; 
		$output.="<td>". $val->id_pekerjaan."</td>"; 
		$output.="<td>". $val->id_pendidikan."</td>"; 
		$output.="<td>". $val->id_status_pernikahan."</td>"; 
		$output.="<td>". $val->jk."</td>"; 
		$output.="<td>". $val->nama_lengkap."</td>"; 
		$output.="<td>". $val->nik."</td>"; 
		$output.="<td>". $val->tempat_lahir."</td>"; 
		$output.="<td>". $val->tgl_lahir."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function get_sales_order($select_spk){
		$so=$this->db->query("select * from tr_sales_order where no_spk ='$select_spk'")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>NO SO</th>";
		$output .= "<th scope='col'>Lokasi Pengiriman</th>";
		$output .= "<th scope='col'>Nama Penerima</th>";
		$output .= "<th scope='col'>NO HP penerima</th>";
		$output .= "<th scope='col'>PO Leasing</th>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($so as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->id_sales_order."</td>"; 
		$output.="<td>". $val->lokasi_pengiriman."</td>"; 
		$output.="<td>". $val->nama_penerima."</td>"; 
		$output.="<td>". $val->no_hp_penerima."</td>"; 
		$output.="<td>". $val->no_po_leasing."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function invoice_pelunasan($select_spk){
	   // table tr_invoice_pelunasan
		$inv_pelunasan=$this->db->query("select * from tr_invoice_tjs where id_spk ='$select_spk' ")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>Alamat</th>";
		$output .= "<th scope='col'>Nama Konsumen</th>";
		$output .= "<th scope='col'>No HP>";
		$output .= "<th scope='col'>No KTP>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($inv_pelunasan as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->alamat."</td>"; 
		$output.="<td>". $val->nama_konsumen."</td>"; 
		$output.="<td>". $val->no_hp."</td>"; 
		$output.="<td>". $val->no_ktp."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function faktur_stnk_detail($select_spk){
		   // table tr_faktur_stnk_detail
		$inv_pelunasan=$this->db->query("select * from tr_faktur_stnk_detail where no_spk ='$select_spk' ")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>Alamat</th>";
		$output .= "<th scope='col'>Nama Konsumen</th>";
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($inv_pelunasan as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->alamat."</td>"; 
		$output.="<td>". $val->nama_konsumen."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function pengajuan_bbn_detail(){
			   // table tr_pengajuan_bbn_detail
		$inv_pelunasan=$this->db->query("select * from tr_pengajuan_bbn_detail limit 1 ")->result();
		$output = NULL;
		$output .= "<thead>";
		$output .= "<tr>";
		$output .= "<th scope='col'>NO Mesin </th>";
		$output .= "<th scope='col'>NO SPK</th>";
		$output .= "<th scope='col'>ID Kelurahan</th>";
		$output .= "<th scope='col'>Kabupaten (String)</th>";
		$output .= "<th scope='col'>Kecamatan (String)</th>";
		$output .= "<th scope='col'>Kelurahan (String)</th>";
	    $output .= "<th scope='col'>Nama Ibu (String)</th>";
		$output .= "<th scope='col'>Nama Konsumen</th>";
		$output .= "<th scope='col'>No HP</th>";
		$output .= "<th scope='col'>No KTP</th>";
		$output .= "<th scope='col'>No KK</th>";

		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach ($inv_pelunasan as $val) {
		$output.="<tr>";
		$output.="<td></td>"; 
		$output.="<td></td>"; 
		$output.="<td>". $val->alamat."</td>"; 
		$output.="<td>". $val->nama_konsumen."</td>"; 
		$output.="</tr>";
		}
		$output.="</tbody>";
		echo $output;
	}

	function invoice_pelunasan_recipt(){
		echo 'lala';
	}

	public function get_data_kelurahan(){
		// $id_series=$this->input->get('series'); 

		$kelurahan  = $this->m_memo_h1->get_kelurahan();
		$kelurahan_loop = NULL;
		foreach ($kelurahan->result() as $data ){
		$kelurahan_loop.= "<option value='$data->id_kelurahan'>$data->kelurahan</option>";
		}
		echo $kelurahan_loop;
	}


	
	public function get_data_kecamatan(){
		$kelurahan_ajax = $this->input->post('kelurahan'); 
		$kelurahan  = $this->m_memo_h1->get_kecamatan($kelurahan_ajax);
		$kelurahan_loop = NULL;
		foreach ($kelurahan->result() as $data ){
		$kelurahan_loop.= "<option value='$data->id_kelurahan'>$data->kelurahan</option>";
		}
		echo $kelurahan_loop;
	}

	function wilayah(){
		$kel  = $this->input->post('kel');
		$kec  = $this->input->post('kec');
		$kab  = $this->input->post('kab');
		$prov = $this->input->post('prov');
		
		$where = 'WHERE 1=1 ';
		$where .= "AND kel.id_kelurahan ='$kel' ";
		$where .= "AND kec.id_kecamatan ='$kec' ";
		$where .= "AND kab.id_kabupaten ='$kab' ";
		$where .= "AND prov.id_provinsi ='$prov'";
		$provinsi = $this->db->query("
		select 
		kel.id_kelurahan,
		kel.kelurahan,
		kec.id_kecamatan,
		kec.kecamatan,
		kab.id_kabupaten,
		kab.kabupaten,
		kab.kabupaten,
		prov.id_provinsi,
		prov.provinsi 
		  from ms_kelurahan kel 
		  inner join ms_kecamatan kec on kec.id_kecamatan= kec.id_kecamatan and kec.id_kecamatan = kel.id_kecamatan 
		  inner join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten 
		  join ms_provinsi prov on prov.id_provinsi = kab.id_provinsi
		$where 
		")->row();
		echo json_encode($provinsi);
	}

	function master(){
		
		$agama  = $this->input->post('agama');
		$status_pernikahan  = $this->input->post('status_pernikahan');
		$jenis_wn  = $this->input->post('jenis_wn');
		$jk = $this->input->post('jk');
		
		$data['status_pernikahan']	= $this->db->query("SELECT id_status_pernikahan,status_pernikahan FROM  ms_status_pernikahan where id_status_pernikahan ='$status_pernikahan ' ")->row();
		// $data['pekerjaan']			= $this->db->query("SELECT id_pekerjaan, pekerjaan  FROM  ms_pekerjaan mp WHERE active ='1'")->result();
		// $data['jk']			= $this->db->query("SELECT id_pekerjaan, pekerjaan  FROM  ms_pekerjaan mp WHERE active ='1'")->result();
		// $data['sub_pekerjaan']		= $this->db->query("SELECT id_sub_pekerjaan,id_pekerjaan,sub_pekerjaan from ms_sub_pekerjaan WHERE active ='1'")->result();
		// $data['pekerjaan_kk']		= $this->db->query("SELECT id_pekerjaan, pekerjaan from ms_pekerjaan_kk mpk WHERE active ='1'")->result();
		$data['agama']				= $this->db->query("SELECT id_agama, agama from ms_agama mpk WHERE id_agama ='$agama '")->row();
		echo json_encode($data);

	
	}


	// MEMO MANUAL 
	function fetch_process() {
		$post  = $this->input->post('value');
		$data  = $this->input->post('data');
		$set_data  = $this->input->post('data');

		$nosin = ($data['no_mesin']);
		$select=$this->db->query("select no_spk,id_customer from tr_spk WHERE no_mesin_spk ='$nosin'")->row();
		$select_customer=$select->id_customer;
		$select_spk     =$select->no_spk;

		switch ($post) {
			case "prospek":
				$set_data['tabel']   = 'tr_prospek';
				$set_data['primary'] = 'id_prospek';
				$set_data['where']   = $select_customer;
				$set_data['select']  = 'id_customer,id_prospek';
				$set_data['set_select_where'] = 'id_customer';
				$set_data['no_spk']  = $select_spk;
			    return	$this->update_prospek($set_data);
			break;
			case "cdb":
				$set_data['tabel']   = 'tr_cdb_kk';
				$set_data['no_spk']  = $select_spk;
				$set_data['primary'] = 'id';
				$set_data['where']   = $select_spk;
				$set_data['select']  = ' no_spk as id';
				$set_data['set_select_where'] = 'no_spk';
			    return	$this->update_cdb_kk($set_data);
			break;
			case "so":
				$set_data['tabel']   = 'tr_sales_order';
				$set_data['no_spk']  = $select_spk;
				$set_data['primary'] = 'no_spk';
				$set_data['where']   = $select_spk;
				$set_data['select']  = 'no_spk,id_sales_order_int';
				$set_data['set_select_where'] = 'no_spk';
			    return	$this->update_so($set_data);
			break;
			case "spk":
				$set_data['tabel']   = 'tr_spk';
				$set_data['no_spk']  = $select_spk;
				$set_data['primary'] = 'no_spk';
				$set_data['where']   = $select_spk;
				$set_data['select']  = 'no_spk';
				$set_data['set_select_where'] = 'no_spk';
			    return	$this->update_spk($set_data);
			break;
		
			default:
			  echo "Eoooo";
		  }
	}

	function history_memo(){
			$query= $this->db->query("SELECT * FROM  ms_memo_detail ")->result();
			header('Content-Type: application/json');
			echo json_encode($query);
	}

	function update_prospek($set_data) {

		date_default_timezone_set('Asia/Jakarta'); 
		$currentDateTime = date('Y-m-d H:i:s');
	
		$table 			= $set_data['tabel'];
		$pk  			= $set_data['primary'];

		$get_id = $this->search_pk_table($set_data)->row();
		$generate = $get_id->id_prospek;

		$update = [];
		if(isset($set_data['nama_pencarian'])){
			$update['nama_konsumen'] = $set_data['nama_pencarian'];
		}


		if (count($update) !== 0) {
			if ($generate !== null) {
				$this->db->trans_start();
				try {
					if (!is_null($table) && !is_null($update) && !is_null($pk) && !is_null($generate)) {
						$this->m_admin->update($table, $update, $pk, $generate);
					}

					
					$detail = [];

					foreach ($update as $key => $value) {
						$set_details=array(
							'memo_id' 		=>  1,
							'table' 		=> 'tr_prospek',
							'key' 			=> $key,
							'value' 	    => $value,  
							'created_at' 	=> $currentDateTime,  
						);
						$detail[]=$set_details;
					}

					$this->create_update_log_activity_memo_detail($detail);

					$detail['no_spk']=$set_data['no_spk'];
					$detail['set_table_atribut']='prospek_status_1';
					$this->update_log_activity_memo($detail);
					$this->db->trans_commit();	
					$response['status'] = 'success';
					$response['message'] = 'Data updated successfully';
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$response['status'] = 'error';
					$response['message'] = 'Error updating data';
				}
			}
		}

		header('Content-Type: application/json');
		echo json_encode($response); 
	}

	function update_log_activity_memo($set_update){

		$where = $set_update['set_table_atribut'];
		$update[$where]=1;

		$spk = $set_update['no_spk'];
		$table = 'ms_memo';
		$pk = 'no_spk'; 
		$generate = $spk;
		
		if ($generate !== null) {
			$this->db->trans_start();
			try {
				if (!is_null($table) && !is_null($update) && !is_null($pk) && !is_null($generate)) {
					$this->m_admin->update($table, $update, $pk, $generate);
				}
				$this->db->trans_commit();	
				$response['status'] = 'success';
				$response['message'] = 'Data updated successfully';
			} catch (Exception $e) {
				$this->db->trans_rollback();
				$response['status'] = 'error';
				$response['message'] = 'Error updating data';
			}
		}

	}


	function update_cdb_kk($set_data) {

		date_default_timezone_set('Asia/Jakarta'); 
		$currentDateTime = date('Y-m-d H:i:s');

		$table 			= $set_data['tabel'];
		$pk  			= 'no_spk';
		$get_id = $this->search_pk_table($set_data)->row();
		$generate = $get_id->id;

		$update = [];
		if(isset($set_data['nama_pencarian'])){
			$update['nama_konsumen'] = $set_data['nama_pencarian'];
		}

		if (count($update) !== 0) {
			if ($generate !== null) {
				$this->db->trans_start();
				try {
					$this->m_admin->update($table, $update, $pk, $generate);
					$detail = [];
					foreach ($update as $key => $value) {
						$set_details=array(
							'memo_id' 		=>  1,
							'table' 		=> 'tr_cdb_kk',
							'key' 			=> $key,
							'value' 	    => $value,  
							'created_at' 	=> $currentDateTime,  
						);
						$detail[]=$set_details;
					}

					$this->create_update_log_activity_memo_detail($detail);
					$detail['no_spk']=$set_data['no_spk'];
					$detail['set_table_atribut']='cdb_kk_status_1';
					$this->update_log_activity_memo($detail);

					$this->db->trans_commit();	
					$response['status'] = 'success';
					$response['message'] = 'Data updated successfully';
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$response['status'] = 'error';
					$response['message'] = 'Error updating data';
				}
			}
		}

		header('Content-Type: application/json');
		echo json_encode($response); 
	}

	function update_spk($set_data) {

		date_default_timezone_set('Asia/Jakarta'); 
		$currentDateTime = date('Y-m-d H:i:s');

		$table 			= $set_data['tabel'];
		$pk  			= 'no_spk';
		$get_id = $this->search_pk_table($set_data)->row();
		$generate = $set_data['no_spk'];

		$update = [];
		if(isset($set_data['nama_pencarian'])){
			$update['nama_bpkb']     = $set_data['nama_pencarian'];
			$update['nama_konsumen'] = $set_data['nama_pencarian'];
		}

		if (count($update) !== 0) {
			if ($generate !== null) {
				$this->db->trans_start();
				try {
					if (!is_null($table) && !is_null($update) && !is_null($pk) && !is_null($generate)) {
						$this->m_admin->update($table, $update, $pk, $generate);
					}
				
					$detail = [];
					foreach ($update as $key => $value) {
						$set_details=array(
							'memo_id' 		=>  1,
							'table' 		=> 'tr_spk',
							'key' 			=> $key,
							'value' 	    => $value,  
							'created_at' 	=> $currentDateTime,  
						);
						$detail[]=$set_details;
					}

					$this->create_update_log_activity_memo_detail($detail);
					$detail['no_spk']=$set_data['no_spk'];
					$detail['set_table_atribut']='spk_status_1';
					$this->update_log_activity_memo($detail);

					$this->db->trans_commit();	
					$response['status'] = 'success';
					$response['message'] = 'Data updated successfully';
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$response['status'] = 'error';
					$response['message'] = 'Error updating data';
				}
			}
		}

		header('Content-Type: application/json');
		echo json_encode($response); 
	}


	function update_so($set_data) {
		date_default_timezone_set('Asia/Jakarta'); 
		$currentDateTime = date('Y-m-d H:i:s');

		$table 			= $set_data['tabel'];
		$pk  			= $set_data['primary'];
		$get_id = $this->search_pk_table($set_data)->row();
		$generate = $set_data['no_spk'];

		if(isset($set_data['nama_pencarian'])){
			$update= array('nama_penerima' => $set_data['nama_pencarian']);
		}

		if (count($update) !== 0) {
			if ($generate !== null) {
				$this->db->trans_start();
				try {
					if (!is_null($table) && !is_null($update) && !is_null($pk) && !is_null($generate)) {
						$this->m_admin->update($table, $update, $pk, $generate);
					}
				
					$detail = [];
					foreach ($update as $key => $value) {
						$set_details=array(
							'memo_id' 		=>  1,
							'table' 		=> 'tr_sales_order',
							'key' 			=> $key,
							'value' 	    => $value,  
							'created_at' 	=> $currentDateTime,  
						);
						$detail[]=$set_details;
					}

					$this->create_update_log_activity_memo_detail($detail);
					$detail['no_spk']=$set_data['no_spk'];
					$detail['set_table_atribut']='so_dealer_status_1';
					$this->update_log_activity_memo($detail);

					$this->db->trans_commit();	
					$response['status'] = 'success';
					$response['message'] = 'Data updated successfully';
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$response['status'] = 'error';
					$response['message'] = 'Error updating data';
				}
			}
		}

		header('Content-Type: application/json');
		echo json_encode($response); 

	}


	function search_pk_table($search) {
		$this->db->select($search['select']);
		$this->db->where($search['set_select_where'], $search['where']);
		$query = $this->db->get($search['tabel']);
		return $query; 
	}

	function search($search) {
	// SELECT 'karyawan' AS source, name FROM karyawan WHERE name IS NOT NULL
	// UNION
	// SELECT 'pelanggan' AS source, name FROM pelanggan WHERE name IS NOT NULL
	// UNION
	// SELECT 'transaksi' AS source, name FROM transaksi WHERE name IS NOT NULL;

	}

	public function take_kec()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');		
		$sql = 'SELECT kelurahan, id_kecamatan, kode_pos FROM ms_kelurahan WHERE id_kelurahan = ?';
		$data = $this->db->query($sql, array(htmlentities($id_kelurahan)));
		if($data->num_rows()>0){
			$dt_kel			= $data->row();
			$kelurahan 		= $dt_kel->kelurahan;
			$kode_pos 		= $dt_kel->kode_pos;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$dt_kec				= $this->db->query("SELECT id_kabupaten, kecamatan FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
			$kecamatan 		= $dt_kec->kecamatan;
			$id_kabupaten = $dt_kec->id_kabupaten;
			$dt_kab				= $this->db->query("SELECT id_provinsi, kabupaten FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
			$kabupaten  	= $dt_kab->kabupaten;
			$id_provinsi  = $dt_kab->id_provinsi;
			$dt_pro				= $this->db->query("SELECT provinsi FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
			$provinsi  		= $dt_pro->provinsi;

			echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $kelurahan . "|" . $kode_pos;
		}else{
			echo  "|||||||";
		}
	}


	public function ajax_list()
	{
		$list = $this->m_kelurahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan", "id_kecamatan", $isi->id_kecamatan);
			$kabupaten = '';
			if ($cek->num_rows() > 0) {
				$t = $cek->row();
				$kecamatan_id = $t->id_kecamatan;
				$kecamatan = $t->kecamatan;
				$kab = $this->db->get_where('ms_kabupaten', ['id_kabupaten' => $t->id_kabupaten]);
				$kabupaten = $kab->num_rows() > 0 ? $kab->row()->kabupaten : '';
			} else {
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->id_kelurahan . '-' . $isi->kelurahan;
			$row[] = $kecamatan_id . '-' .  $kecamatan;
			$row[] = $kabupaten;
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_kelurahan->count_all(),
			"recordsFiltered" => $this->m_kelurahan->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}






	
	
	
	
}