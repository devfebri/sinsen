<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimasi_stnk extends CI_Controller {

  var $tables =   "tr_estimasi_stnk";	
	var $folder =   "dealer";
	var $page		=		"estimasi_stnk";
  var $pk     =   "id_estimasi_stnk";
  var $title  =   "Estimasi STNK";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

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
		$data['dt_prospek'] = $this->m_admin->getAll($this->tables);				
		$this->template($data);			
	}
	public function t_stnk(){
		$start_date = $this->input->post('start_date');
		$end_date 	= $this->input->post('end_date');
		$id_dealer 	= $this->m_admin->cari_dealer(); 
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_sales_order WHERE tgl_po_leasing BETWEEN '$start_date' AND '$end_date' AND id_dealer = '$id_dealer'");		 
		$data['status'] = "input";
		$this->load->view('dealer/t_stnk',$data);
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$this->template($data);										
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$no					= $this->input->post("no");

		foreach($no AS $key => $val){
			$no_mesin 	= $_POST['no_mesin'][$key];
			$no_rangka 	= $_POST['no_rangka'][$key];
			$nama_konsumen 	= $_POST['nama_konsumen'][$key];
			$alamat 		= $_POST['alamat'][$key];

			$data["no_mesin"] 			= $no_mesin;
			$data["no_rangka"] 			= $no_rangka;
			$data["nama_konsumen"] 	= $nama_konsumen;
			$data["alamat"] 				= $alamat;
			if(isset($_POST['check_ktp'][$key])){
				$data["ktp"] = "ya";									
			}else{
				$data["ktp"] 		= "tidak";									
			}
			if(isset($_POST['check_fisik'][$key])){
				$data["fisik"] = "ya";									
			}else{
				$data["fisik"] 		= "tidak";									
			}
			if(isset($_POST['check_stnk'][$key])){
				$data["stnk"] = "ya";									
			}else{
				$data["stnk"] 		= "tidak";									
			}
			if(isset($_POST['check_bpkb'][$key])){
				$data["bpkb"] = "ya";									
			}else{
				$data["bpkb"] 		= "tidak";									
			}
			if(isset($_POST['check_kuasa'][$key])){
				$data["kuasa"] = "ya";									
			}else{
				$data["kuasa"] 		= "tidak";									
			}
			if(isset($_POST['check_ckd'][$key])){
				$data["ckd"] = "ya";									
			}else{
				$data["ckd"] 		= "tidak";									
			}
			if(isset($_POST['check_permohonan'][$key])){
				$data["permohonan"] = "ya";									
			}else{
				$data["permohonan"] 		= "tidak";									
			}

			$cek = $this->db->query("SELECT * FROM tr_estimasi_stnk WHERE no_mesin = '$no_mesin'");
			if($cek->num_rows() > 0){						
				$this->m_admin->update("tr_estimasi_stnk",$data,"no_mesin",$no_mesin);								
			}else{
				$this->m_admin->update("tr_estimasi_stnk",$data,"no_mesin",$no_mesin);								
			}								
		}		
		
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/estimasi_stnk'>";
	}
}