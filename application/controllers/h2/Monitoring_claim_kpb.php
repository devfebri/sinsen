<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_claim_kpb extends CI_Controller {

    var $table_head =   "tr_";
    var $pk_head     =   "id_";	
    var $table_det =   "tr_";
    var $pk_det     =   "id_";	
	var $folder =   "h2";
	var $page		=		"monitoring_claim_kpb";
    var $title  =   "Monitoring Claim KPB (Beban MD)";

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
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}


	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
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
		$data['dt_result'] = $this->db->query("SELECT tr_claim_kpb.*,ms_kpb_detail.*,tipe_ahm FROM tr_claim_kpb 
			JOIN ms_kpb_detail ON tr_claim_kpb.id_tipe_kendaraan=ms_kpb_detail.id_tipe_kendaraan AND tr_claim_kpb.kpb_ke=ms_kpb_detail.kpb_ke
			JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_claim_kpb.id_tipe_kendaraan
			");
		$this->template($data);	
	}	
}