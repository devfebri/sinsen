<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintain_lokasi extends CI_Controller {

    var $tables =   "tr_maintain_lokasi";	
		var $folder =   "h1";
		var $page		=		"maintain_lokasi";
    var $pk     =   "id_maintain_lokasi";
    var $title  =   "Maintain Lokasi";

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
		$data['set']	= "view";
		$data['dt_maintain_lokasi'] = $this->m_admin->getAll($this->tables);				
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}

	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_lokasi'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");							
		$data['set']		= "insert";					
		$this->template($data);										
	}
	
	public function cek_nosin(){
		$no_mesin		= $this->input->post('no_mesin');							
		$c = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'")->row();				

		$cek 	= $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'");

		$row = $cek->row();
		$cek_gudang = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_penerimaan_unit 
						ON tr_penerimaan_unit.id_penerimaan_unit=tr_penerimaan_unit_detail.id_penerimaan_unit 
						WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list'")->row();

		//cek status, gudang dan tipe dedicated
		$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.tipe_dedicated = '$row->tipe_motor' AND ms_lokasi_unit.status_unit = '$row->tipe' 
						AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang' 
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");	

		
		//cek gudang, tipe kendaraan dan warna SAMA
		$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
						INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
						INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.status_unit = '$row->tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND tr_scan_barcode.warna = '$row->warna'
						AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
				
		//cek status, gudang dan tanpa dedicated
		$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
					  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = '$row->tipe' AND ms_lokasi_unit.isi = 0
					  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						AND ms_lokasi_unit.isi = ''
					  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

		//cek status, gudang dan tanpa dedicated
		$cek3_a = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = '$row->tipe' AND ms_lokasi_unit.isi = 0
						  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

	
		//cek gudang, tipe kendaraan SAMA dan warna BEDA
		$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
						INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
						INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.status_unit = '$row->tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");								

		//cek gudang, tipe kendaraan BEDA dan warna BEDA
		$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
						tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang   
						WHERE ms_lokasi_unit.status_unit = '$row->tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' and ms_lokasi_unit.tipe_dedicated = ''
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
		}			

		//cek slot

		if($isi_lokasi != ""){
			$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
			if($cek_maks->isi < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 
						if($i < 10){
							$sl = "0".$i;
						}else{
							$sl = $i;
						}
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2) ORDER BY slot ASC");
						if($cek_slot2->num_rows() == ""){
							$isi_slot2 = $cek_slot2->row();
							$slot_baru = $sl;
							break;								
						}					

						
				}
				
			}else{
				$slot_baru = "01";
			}			
		}else{
			$slot_baru = "";
		}			

		$lokasi_baru = $isi_lokasi."-".$slot_baru;


		echo $c->no_mesin."|".$c->id_item."|".$c->tipe_motor."|".$c->warna."|".$c->lokasi."-".$c->slot."|".$lokasi_baru;								
	}	
	public function get_slot(){
		$id_lokasi	= $this->input->post('lokasi_baru');

		$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$id_lokasi'")->row();		
		$data .= "<option value=''>- choose -</option>";
		for ($i=1; $i <= $cek_maks->qty; $i++) { 
			if($i < 10){
				$isi = "0".$i;				
			}else{
				$isi= $i;
			}
			$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$id_lokasi' AND slot = '$isi' AND status = '1'");
			if($cek_slot->num_rows() == 0){
				$data .= "<option value='$isi'>$isi</option>\n";	
			}			
		}			
		echo $data;
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$no_mesin 								= $this->input->post('no_mesin');
			$data['no_mesin'] 				= $this->input->post('no_mesin');
			$data['lokasi_lama'] 			= $this->input->post('lokasi_lama');	
			$data['lokasi_baru'] 			= $this->input->post('lokasi_baru')."-".$this->input->post('slot');	
			$data['ket'] 							= $this->input->post('ket');				
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	

			$lokasi_lama							= $this->input->post('lokasi_lama');				
			$lokasi_baru							= $this->input->post('lokasi_baru');	

			$da['lokasi']							= $this->input->post('lokasi_baru');	
			$da['slot']								= $this->input->post('slot');	

			$t = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();			
			$this->db->query("UPDATE ms_lokasi_unit SET isi = isi-1 WHERE id_lokasi_unit = '$t->lokasi'");				
			$this->db->query("UPDATE ms_lokasi_unit SET isi = isi+1 WHERE id_lokasi_unit = '$lokasi_baru'");				

			$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);
			$this->m_admin->insert($tabel,$data);						

			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/maintain_lokasi/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	
}