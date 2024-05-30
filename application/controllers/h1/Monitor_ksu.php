<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_ksu extends CI_Controller {

    var $tables =   "tr_penerimaan_ksu";	
		var $folder =   "h1";
		var $page		=		"monitor_ksu";
    var $pk     =   "id_penerimaan_ksu";
    var $title  =   "Monitor Penerimaan KSU";

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
		$data['dt_ksu'] = $this->db->query("SELECT DISTINCT(id_penerimaan_unit) FROM tr_penerimaan_ksu ORDER BY id_penerimaan_unit desc limit 5");	

		// $data['dt_ksu'] = $this->db->query("SELECT DISTINCT(id_penerimaan_unit) FROM tr_penerimaan_ksu where id_penerimaan_unit ='PU202408191' ORDER BY id_penerimaan_unit desc limit 5");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
		
	public function terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Konfirmasi Penerimaan KSU";	
		$id 						= $this->input->get("id");	
		$data['set']		= "insert";		
		$dq = "SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_scan_barcode 					
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_scan_barcode.no_shipping_list = '$id' AND tr_scan_barcode.status = '1' AND tr_scan_barcode.tipe = 'RFS'";
		$data['dt_rfs'] = $this->db->query($dq);		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail Penerimaan KSU";	
		$id 						= $this->input->get("id");	
		$data['set']		= "detail";		
		$dq = "SELECT DISTINCT(tr_scan_barcode.tipe_motor),tr_scan_barcode.no_shipping_list,tr_scan_barcode.tipe_motor,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.id_item 
					FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.`no_shipping_list` = tr_penerimaan_unit_detail.no_shipping_list		
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id' AND tr_scan_barcode.status = '1'
					ORDER BY tr_scan_barcode.no_shipping_list ASC";
		$data['dt_rfs'] = $this->db->query($dq);		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$id_ksu 		= $this->input->post('id_ksu');		
		$no_sl 			= $this->input->post('no_sl');		
		foreach($id_ksu AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$no_mesin = $_POST['no_mesin'][$key];
		 	$result[] = array(
				"id_ksu"  => $_POST['id_ksu'][$key],
				"qty"  => $_POST['qty'][$key],
				"no_mesin"  => $_POST['no_mesin'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id,
				"status"  => "1"
		 	); 

		 	$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$id_ksu' AND no_mesin = '$no_mesin'");
      if($rty->num_rows() > 0){
      	$this->db->query("DELETE FROM tr_penerimaan_ksu WHERE id_ksu = '$id_ksu' AND no_mesin = '$no_mesin'");
      }
		}
		$test2 = $this->db->insert_batch('tr_penerimaan_ksu', $result);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_ksu/terima?id=".$no_sl."'>";
	}
}