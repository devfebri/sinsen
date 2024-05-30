<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kekurangan_ksu extends CI_Controller {

    var $tables =   "tr_kekurangan_ksu";	
		var $folder =   "h1";
		var $page		=		"kekurangan_ksu";
    var $pk     =   "id_kekurangan_ksu";
    var $title  =   "Kekurangan KSU";

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
		//$data['dt_shipping_list'] = $this->db->query("SELECT DISTINCT(no_shipping_list),tgl_sl,no_pol_eks,nama_eks FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
		
	public function terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Terima Kekurangan KSU";	
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
		$id_pu 			= $this->input->post('id_pu');	
		$cek="";
		foreach($id_ksu AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$id_tipe_kendaraan = $_POST['tipe_motor'][$key];
			$no_sl = $_POST['no_sl'][$key];
		 	$result[] = array(
				"id_penerimaan_unit"  => $id_pu,
				"id_ksu"  => $_POST['id_ksu'][$key],
				//"qty"  => $_POST['qty'][$key],
				"qty_ahm"  => $_POST['qty_ahm'][$key],
				"qty_eks"  => $_POST['qty_eks'][$key],
				"id_tipe_kendaraan"  => $_POST['tipe_motor'][$key],
				"no_sl"  => $_POST['no_sl'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id,
				"status"  => "1"
		 	); 

		 	$ambil = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_penerimaan_unit = '$id_pu' AND no_sl = '$no_sl' AND id_ksu = '$id_ksu'");
		 	if($ambil->num_rows() > 0){
		 		$pu = $ambil->row();
		 		$isi = $pu->qty;		 	
			 	$qty_baru = $_POST['qty'][$key] + $isi;			 					
			 	$this->db->query("UPDATE tr_penerimaan_ksu SET qty = '$qty_baru', updated_at = '$waktu',updated_by = '$login_id' 
			 			WHERE id_penerimaan_unit = '$id_pu' AND no_sl = '$no_sl' AND id_ksu = '$id_ksu' AND id_tipe_kendaraan = '$id_tipe_kendaraan'");
			}


		 	$rty = $this->db->query("SELECT * FROM tr_kekurangan_ksu WHERE id_ksu = '$id_ksu' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND no_sl = '$no_sl' AND id_penerimaan_unit = '$id_pu'");
      if($rty->num_rows() > 0){
      	$this->db->query("DELETE FROM tr_kekurangan_ksu WHERE id_ksu = '$id_ksu' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND no_sl = '$no_sl' AND id_penerimaan_unit = '$id_pu'");
      }
		}
		$test2 = $this->db->insert_batch('tr_kekurangan_ksu', $result);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/kekurangan_ksu/terima?id=".$id_pu."'>";
	}
}