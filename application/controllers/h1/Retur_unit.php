<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_unit extends CI_Controller {

    var $tables =   "tr_retur_unit";	
		var $folder =   "h1";
		var $page		=		"retur_unit";
    var $pk     =   "no_retur_unit";
    var $title  =   "Retur Unit";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}


	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{						
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_retur_dealer INNER JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer 
			WHERE tr_retur_dealer.status_retur_d = 'input' OR tr_retur_dealer.status_retur_d = 'approved' ORDER BY tr_retur_dealer.no_retur_dealer DESC");						
		$this->template($data);	
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$this->template($data);										
	}
	
	public function t_data(){
		$id = $this->input->post('no_retur_d');
		$dq = "SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			WHERE tr_retur_dealer_detail.no_retur_dealer = '$id'";
		$data['dt_data'] = $this->db->query($dq);		
		$this->load->view('dealer/t_retur_dealer',$data);
	}

	
	public function cek_nosin()
	{		
		$no_mesin	= $this->input->post('no_mesin');	
		$dt_so		= $this->db->query("SELECT * FROM tr_retur_konsumen 
				INNER JOIN ms_tipe_kendaraan ON tr_retur_konsumen.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_retur_konsumen.id_warna = ms_warna.id_warna WHERE tr_retur_konsumen.no_mesin = '$no_mesin'");								
		if($dt_so->num_rows() > 0){
			$da = $dt_so->row();			
			echo "ok|".$da->no_rangka."|".$da->tipe_ahm."|".$da->warna."|".$da->tahun_produksi."|".$da->tgl_retur."|".$da->id_item."|".$da->no_retur_konsumen;
		}else{
			echo "Data tidak ditemukan";
		}		
	}
	public function save_data(){
		$no_retur_konsumen						= $this->input->post('no_retur_k');			
		$no_retur_dealer							= $this->input->post('no_retur_d');				
		$data['keterangan']						= $this->input->post('keterangan');					
		$data['no_retur_dealer']			= $this->input->post('no_retur_d');					
		$data['no_retur_konsumen']		= $no_retur_konsumen;					
		$c = $this->db->query("SELECT * FROM tr_retur_dealer_detail WHERE no_retur_dealer = '$no_retur_dealer' AND no_retur_konsumen = '$no_retur_konsumen'");
		if($c->num_rows() > 0){
			$r = $c->row();
			$cek2 = $this->m_admin->update("tr_retur_dealer_detail",$data,"no_retur_dealer_detail",$r->no_retur_dealer_detail);						
		}else{
			$cek2 = $this->m_admin->insert("tr_retur_dealer_detail",$data);						
			echo "nihil";
		}							
	}
	public function delete_data(){
		$id = $this->input->post('id_retur_dealer_detail');		
		$this->db->query("DELETE FROM tr_retur_dealer_detail WHERE id_retur_dealer_detail = '$id'");			
		echo "nihil";
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;		
		$data['set']		= "detail";					
		$data['mode']		= "";
		$id = $this->input->get("id");
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_retur_dealer WHERE no_retur_dealer = '$id'");						
		$this->template($data);										
	}
	public function approval()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Approval ".$this->title;		
		$data['set']		= "detail";					
		$data['mode']		= "approval";
		$id = $this->input->get("id");
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_retur_dealer WHERE no_retur_dealer = '$id'");						
		$this->template($data);										
	}
	public function retur_ksu()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Retur KSU ".$this->title;		
		$data['set']		= "retur_ksu";							
		$id = $this->input->get("id");
		$data['no_retur_dealer'] = $this->input->get("id");
		$data['dt_retur'] = $this->db->query("SELECT ms_koneksi_ksu.id_tipe_kendaraan, ms_ksu.ksu, ms_ksu.id_ksu,COUNT(ms_ksu.id_ksu) AS jum FROM tr_retur_dealer_detail 
			INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_koneksi_ksu ON tr_scan_barcode.tipe_motor = ms_koneksi_ksu.id_tipe_kendaraan 
			INNER JOIN ms_koneksi_ksu_detail ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu
			INNER JOIN ms_ksu ON ms_koneksi_ksu_detail.id_ksu = ms_ksu.id_ksu
			WHERE tr_retur_dealer_detail.no_retur_dealer = '$id' GROUP BY ms_ksu.id_ksu ORDER BY ms_koneksi_ksu.id_tipe_kendaraan ASC");						
		$this->template($data);										
	}
	public function cetak_ulang()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak Ulang Stiker ".$this->title;		
		$data['set']		= "cetak_stiker";							
		$id = $this->input->get("id");		
		$data['no_retur_dealer'] = $id;
		$data['dt_scan'] = $this->db->query("SELECT tr_retur_dealer_detail.*,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_retur_dealer_detail INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 			 
			 LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			 LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			 WHERE tr_retur_dealer_detail.no_retur_dealer = '$id'");						
		$this->template($data);										
	}
	public function cetak_s(){
		$id 				= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$data['dt_stiker'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
		$this->load->view('h1/cetak_stiker',$data);
		
	}
	public function scan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Scan Unit ".$this->title;		
		$data['set']		= "scan";							
		$id = $this->input->get("id");
		$data['dt_gudang'] = $this->m_admin->getSortCond("ms_gudang","gudang","ASC");							
		$data['no_retur_dealer'] = $id;
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_retur_dealer WHERE no_retur_dealer = '$id'");						
		$this->template($data);										
	}
	public function save_scan(){
		$nrfs_text	= $this->input->post('no_mesin');
		$no_retur_dealer			= $this->input->post('no_retur_dealer');
		$gudang			= $this->input->post('gudang');
		$waktu 			= date("y-m-d");		
		$cek 	= $this->db->query("SELECT tr_scan_barcode.*,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail 
						INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin            
            WHERE tr_retur_dealer_detail.no_mesin = '$nrfs_text'");		
		if($cek->num_rows() > 0){
			$row = $cek->row();

			//cek status, gudang dan tipe dedicated
			$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.tipe_dedicated = '$row->tipe_motor' AND ms_lokasi_unit.status_unit = 'NRFS' 
							AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$gudang' 
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");
			
			//cek gudang, tipe kendaraan dan warna SAMA
			$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
							INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
							INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
							AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND tr_scan_barcode.warna = '$row->warna'
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND ms_gudang.gudang = '$gudang'
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
		
			//cek status, gudang dan tanpa dedicated dan lokasi kosong
			$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						  AND ms_gudang.gudang = '$gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						  AND ms_lokasi_unit.isi = ''
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

			//cek status, gudang dan tanpa dedicated dan lokasi tdk kosong
			$cek3_a = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						  AND ms_gudang.gudang = '$gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");


			//cek gudang, tipe kendaraan SAMA dan warna BEDA
			$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
							INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
							INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$gudang'
							AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

			//cek gudang, tipe kendaraan BEDA dan warna BEDA
			$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
							tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 							
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$gudang'
							AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND ms_lokasi_unit.tipe_dedicated = '' 
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

			if($cek1->num_rows() > 0){
				$amb = $cek1->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;				
			}elseif($cek2->num_rows() > 0){
				$amb = $cek2->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}elseif($cek3->num_rows() > 0){
				$amb = $cek3->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;						
			}elseif($cek3_a->num_rows() > 0){
				$amb = $cek3_a->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}elseif($cek4->num_rows() > 0){
				$amb = $cek4->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}elseif($cek5->num_rows() > 0){
				$amb = $cek5->row();				
				$isi_lokasi = $amb->id_lokasi_unit;
				$jum = $amb->isi + 1;			
			}
			else{
				$isi_lokasi = "";
				$jum = 0;
			}			

			if($isi_lokasi != ""){
				$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
				$cek_isi = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND (status = 1 OR status = 2 OR status = 7)");				
				if($cek_isi->num_rows() > 0){
					$t = $cek_isi->row();
					$isi_scan = $t->jum;
				}else{
					$isi_scan = 0;
				}
				if($isi_scan < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 							
						$sl = $i;
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2 OR status = 7) ORDER BY slot ASC");
						if($cek_slot2->num_rows() == 0){
							$isi_slot2 = $cek_slot2->row();
							$slot_baru = $sl;
							break;								
						}																			
					}					
				}else{
					$slot_baru = "";
				}			
			}else{
				$slot_baru = "";
			}						

			$data['terima']							= 'ya';			
			$data['lokasi']							= $isi_lokasi;
			$data['slot']								= $slot_baru;
			$data['tgl_scan']						= $waktu;						
			//$no_retur_konsumen 					= $row->no_retur_konsumen;
			$id_item										= $row->id_item;
			$c = $this->db->query("SELECT * FROM tr_retur_dealer_detail WHERE no_mesin = '$row->no_mesin'");
			//$c2 = $this->db->query("SELECT * FROM tr_retur_dealer_detail WHERE no_mesin = '$row->no_mesin' AND terima = 'ya'");
			if($c->num_rows() == 0){				
				echo "Gagal Simpan, No Mesin ini tidak termasuk dlm database retur ini";
			// }elseif($c2->num_rows() > 0){
			// 	echo "sudah";
			}elseif($cek3->num_rows() > 0 OR $cek2->num_rows() > 0 OR $cek1->num_rows() > 0 OR $cek4->num_rows() > 0 OR $cek5->num_rows() > 0 OR $slot_baru != ""){
				$this->m_admin->update("tr_retur_dealer_detail",$data,"no_mesin",$row->no_mesin);						
				$this->m_admin->update_stock($id_item,"NRFS",'+','1');
				$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum' WHERE id_lokasi_unit = '$isi_lokasi'");				
				$this->db->query("UPDATE tr_scan_barcode SET lokasi ='$isi_lokasi', slot='$slot_baru', status = '7', tipe = 'NRFS' WHERE no_mesin = '$nrfs_text'");				
				$this->m_admin->set_log($nrfs_text,"NRFS",$isi_lokasi."-".$slot_baru);				
				echo "ok";
			}else{
				echo "lokasi";
			}
		}else{			
			echo "none";
		}									
	}
	public function t_scan(){
		$id = $this->input->post('no_retur_dealer');
		$dq = "SELECT tr_scan_barcode.*, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
            INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
            WHERE tr_retur_dealer_detail.no_retur_dealer = '$id'";
		$data['dt_scan'] = $this->db->query($dq);		
		$this->load->view('h1/t_retur',$data);
	}

	public function cari_id(){		
		$tahun 					= date("Y");
		$id_dealer 			= $this->m_admin->cari_dealer();		
		// $pr_num 				= $this->db->query("SELECT * FROM tr_retur_unit ORDER BY no_retur_unit DESC LIMIT 0,1");	
		$pr_num 				= $this->db->query("SELECT no_retur_unit FROM tr_retur_unit	WHERE LEFT(created_at,4)='$tahun' ORDER BY created_at DESC LIMIT 0,1");					
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pisah = explode("/", $row->no_retur_unit);
			$id = $pisah[0] + 1;
			$kode = $id."/RETMD/".$tahun;						
		}else{
			$kode = "1/RETMD/".$tahun;						
		} 	
		return $kode;
	}
	function tes_id()
	{
		echo $this->cari_id();
	}

	public function tes_save_approval()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();		
		$mode = $this->input->post('approval');
		if($mode == 'approve'){
			$no_retur_unit 							= $this->cari_id();
			$data['no_retur_unit'] 			= $no_retur_unit;
			$no_retur_dealer 						= $this->input->post('no_retur_d');
			$data['no_retur_dealer'] 		= $this->input->post('no_retur_d');
			$data['status_retur_md'] 		= "input";			
			$data['tgl_retur'] 					= $tgl;			
			$data['created_at']					= $waktu;		
			$data['created_by']					= $login_id;	
			// $this->m_admin->insert($tabel,$data);

			$r = $this->m_admin->getByID("tr_retur_dealer_detail","no_retur_dealer",$no_retur_dealer);
			foreach ($r->result() as $isi) {
				$dq['no_retur_konsumen']			= $isi->no_retur_konsumen;
				$dq['no_retur_unit']					= $no_retur_unit;
				// $this->m_admin->insert("tr_retur_unit_detail",$dq);
				$scan[] = $isi->no_mesin;
				// $this->db->query("UPDATE tr_scan_barcode SET status = '1' WHERE no_mesin = '$isi->no_mesin'");				
			}

			$da['status_retur_d']			= 'approved';
			$da['updated_at']					= $waktu;		
			$da['updated_by']					= $login_id;	
			// $this->m_admin->update("tr_retur_dealer",$da,"no_retur_dealer",$no_retur_dealer);


			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_unit'>";		
		}else{
			$no_retur_dealer 						= $this->input->post('no_retur_d');
			$data['status_retur_d']			= 'rejected';
			$data['updated_at']					= $waktu;		
			$data['updated_by']					= $login_id;	
			// $this->m_admin->update("tr_retur_dealer",$data,"no_retur_dealer",$no_retur_dealer);

			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_unit'>";
		}

		echo json_encode($dq);
	}
	
	public function save_approval()
	{		
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();		
		$mode = $this->input->post('approval');
		if($mode == 'approve'){
			$no_retur_unit 							= $this->cari_id();
			$data['no_retur_unit'] 			= $no_retur_unit;
			$no_retur_dealer 						= $this->input->post('no_retur_d');
			$data['no_retur_dealer'] 		= $this->input->post('no_retur_d');
			$data['status_retur_md'] 		= "input";			
			$data['tgl_retur'] 					= $tgl;			
			$data['created_at']					= $waktu;		
			$data['created_by']					= $login_id;	
			$this->m_admin->insert($tabel,$data);

			$r = $this->m_admin->getByID("tr_retur_dealer_detail","no_retur_dealer",$no_retur_dealer);
			foreach ($r->result() as $isi) {
				$dq['no_retur_konsumen']			= $isi->no_retur_konsumen;
				$dq['no_retur_unit']					= $no_retur_unit;
				$this->m_admin->insert("tr_retur_unit_detail",$dq);
				
				$s = $this->m_admin->getByID("tr_retur_dealer","no_retur_dealer",$no_retur_dealer)->row();
				$no_mesin 	= $isi->no_mesin;
				$id_dealer 	= $s->id_dealer;
				$cek = $this->db->query("SELECT tr_surat_jalan.no_surat_jalan,tr_surat_jalan.id_surat_jalan,tr_surat_jalan.no_picking_list FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
					WHERE tr_surat_jalan.id_dealer = '$id_dealer' AND tr_surat_jalan_detail.no_mesin = '$no_mesin'");
				if($cek->num_rows() > 0){
					$id_sj = $cek->row()->id_surat_jalan;
					$no_surat_jalan = $cek->row()->no_surat_jalan;
					$no_picking_list = $cek->row()->no_picking_list;
					$this->db->query("UPDATE tr_surat_jalan_detail SET retur = '1' WHERE no_surat_jalan = '$no_surat_jalan' AND no_mesin = '$no_mesin'");				
					$this->db->query("UPDATE tr_picking_list_view SET retur = '1' WHERE no_picking_list = '$no_picking_list' AND no_mesin = '$no_mesin'");				
					$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET retur = '1' WHERE id_sj = '$id_sj' AND no_mesin = '$no_mesin'");				
				}
				$this->db->query("UPDATE tr_scan_barcode SET status = '1' WHERE no_mesin = '$isi->no_mesin'");				
				//$this->db->query("UPDATE tr_scan_barcode SET status = '1' WHERE no_mesin = '$isi->no_mesin'");				
			}

			$da['status_retur_d']			= 'approved';
			$da['updated_at']					= $waktu;		
			$da['updated_by']					= $login_id;	
			$this->m_admin->update("tr_retur_dealer",$da,"no_retur_dealer",$no_retur_dealer);


			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_unit'>";		
		}else{
			$no_retur_dealer 						= $this->input->post('no_retur_d');
			$data['status_retur_d']			= 'rejected';
			$data['updated_at']					= $waktu;		
			$data['updated_by']					= $login_id;	
			$this->m_admin->update("tr_retur_dealer",$data,"no_retur_dealer",$no_retur_dealer);

			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_unit'>";
		}
	}		
	function save_ksu(){
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();		
		$jum = $this->input->post('jum');
		for ($i=0; $i <= $jum; $i++) { 
			$no_retur_dealer = $this->input->post('no_retur_dealer');
			$id_ksu = $this->input->post('id_ksu_'.$i);
			$qty_terima = $this->input->post('qty_terima_'.$i);
			$data['qty_terima'] = $qty_terima;
			$data['no_retur_dealer'] = $no_retur_dealer;
			$data['id_ksu'] = $id_ksu;
			
			//$this->m_admin->update_ksu($id_ksu,$qty_terima,"+");

			$cek = $this->db->query("SELECT * FROM tr_retur_dealer_detail_ksu WHERE id_ksu = '$id_ksu' AND no_retur_dealer = '$no_retur_dealer'");
			if($cek->num_rows() > 0){
				$row = $cek->row();
				$this->m_admin->update("tr_retur_dealer_detail_ksu",$data,"id_retur_dealer_detail_ksu",$row->id_retur_dealer_detail_ksu);
			}else{
				$this->m_admin->insert("tr_retur_dealer_detail_ksu",$data);
			}
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_unit'>";		
	}
}