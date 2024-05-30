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
		$this->m_admin->update_isi();
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
			$cek_isi = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND (status = 1 OR status = 2)");				
				if($cek_isi->num_rows() > 0){
					$t = $cek_isi->row();
					$isi_scan = $t->jum;
				}else{
					$isi_scan = 0;
				}
				if($isi_scan < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 							
						$sl = $i;
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2) ORDER BY slot ASC");
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

		$lokasi_baru = $isi_lokasi."-".$slot_baru;
				
		echo $c->no_mesin."|".$c->id_item."|".$c->tipe_motor."|".$c->warna."|".$c->lokasi."-".$c->slot."|".$lokasi_baru;
	}	
	public function get_slot(){
		$id_lokasi	= $this->input->post('lokasi_baru');

		$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$id_lokasi'")->row();		
		$data .= "<option value=''>- choose -</option>";
		for ($i=1; $i <= $cek_maks->qty; $i++) { 			
			$isi= $i;			
			$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$id_lokasi' AND slot = '$isi' AND status = '1'");
			if($cek_slot->num_rows() == 0){
				$data .= "<option value='$isi'>$isi</option>\n";	
			}			
		}			
		echo $data;
	}
	public function get_slot_new(){
		$lokasi_s	= $this->input->post('lokasi_s');
		$rt = explode("-", $lokasi_s);		
		$cek = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang WHERE ms_lokasi_unit.id_lokasi_unit = '$rt[0]'")->row();							
		$data .= "<option value='$rt[0]'>$rt[0] - $cek->gudang</option>\n";			
		$dt_lokasi = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");							
    foreach($dt_lokasi->result() as $val) {
      $data .= "<option value='$val->id_lokasi_unit'>$val->id_lokasi_unit - $val->gudang</option>\n";
    }                      
		echo $data;
	}
	public function get_slot_new2(){
		$lokasi_s	= $this->input->post('lokasi_s');
		$rt = explode("-", $lokasi_s);			
		$id_lokasi = $rt[0];
		$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$id_lokasi'")->row();		
		for ($i=1; $i <= $cek_maks->qty; $i++) { 

			
			$isi= $i;			

			if ($rt[1] == $isi) {
				$selected = 'selected';
			}else{
				$selected='';
			}
			$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$id_lokasi' AND slot = '$isi' AND status = '1'");
			if($cek_slot->num_rows() == 0){
				$data2 .= "<option value='$isi' $selected>$isi</option>\n";	
			}			
		}		
		//$data = "<option value='$rt[1]'>$rt[1]</option>\n";					
		echo $data2;
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
			$lokasi_slot 							= $this->input->post('lokasi_baru')."-".$this->input->post('slot');	
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

			$this->m_admin->set_log($no_mesin,$t->tipe,$lokasi_slot);			

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
	
	public function cetak_s(){
		$id 				= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$data['dt_stiker'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
		$this->load->view('h1/cetak_stiker',$data);
		
	}
	
}