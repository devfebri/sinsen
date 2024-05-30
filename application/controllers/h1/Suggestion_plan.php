<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suggestion_plan extends CI_Controller {

    var $tables =   "tr_suggestion_plan";	
		var $folder =   "h1";
		var $page		=		"suggestion_plan";
    var $pk     =   "id_suggestion_plan";
    var $title  =   "Suggestion Dist. Plan";

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
		$data['dt_sp'] = $this->db->query("SELECT * FROM tr_suggestion_plan INNER JOIN tr_suggestion_plan_detail ON tr_suggestion_plan.id_suggestion_plan=tr_suggestion_plan_detail.id_suggestion_plan
			INNER JOIN ms_tipe_kendaraan ON tr_suggestion_plan_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan");				
		$this->template($data);		
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$this->template($data);										
	}
	public function cari_id(){
		$kode = $this->m_admin->cari_id("tr_suggestion_plan","id_suggestion_plan");
		return $kode;
	}
	public function t_sd(){
		$id = $this->input->post('id_suggestion_plan');
		$data['bulan'] = $this->input->post('bulan');
		$data['tahun'] = $this->input->post('tahun');
		$data['ahm'] = $this->input->post('ahm');
		$data['md'] = $this->input->post('md');
		$data['dt_sd'] = $this->db->query("SELECT * FROM tr_suggestion_plan_detail WHERE id_suggestion_plan = '$id'");		 
		$this->load->view('h1/t_sd',$data);
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$id_suggestion_plan 	= $this->cari_id();
		$da['id_suggestion_plan'] 		= $id_suggestion_plan;
		$da['bulan'] 					= $this->input->post("bulan");
		$da['tahun'] 					= $this->input->post("tahun");
		$da['ahm'] 						= $this->input->post("ahm");
		$da['md'] 						= $this->input->post("md");	
		$da['status_sp'] = "input";		
		$da['created_at'] 		= $waktu;		
		$da['created_by'] 		= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 			
			$id_tipe_kendaraan		= $_POST["id_tipe_kendaraan_".$i];						
			$data['id_tipe_kendaraan'] 		= $id_tipe_kendaraan;
			$data['id_suggestion_plan'] 			= $id_suggestion_plan;			
			$data['stock_days'] 	= $_POST["stock_days_".$i];			
			$data['stock_md'] 		= $_POST["stock_md_".$i];			
			$data['intransit'] 		= $_POST["intransit_".$i];			
			$data['dist_d'] 			= $_POST["cek_md_".$i];									
			
			$this->m_admin->insert("tr_suggestion_plan_detail",$data);											
		}
			
		$ce = $this->db->query("SELECT * FROM tr_suggestion_plan WHERE id_suggestion_plan = '$id_suggestion_plan'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_suggestion_plan",$da,"id_suggestion_plan",$id_suggestion_plan);								
		}else{
			$this->m_admin->insert("tr_suggestion_plan",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/suggestion_plan'>";
	}	
}