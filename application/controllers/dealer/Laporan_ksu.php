<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_ksu extends CI_Controller {

    var $tables =   "tr_penerimaan_ksu_dealer";	
		var $folder =   "dealer";
		var $page		=		"laporan_ksu";
    var $pk     =   "id_penerimaan_ksu_dealer";
    var $title  =   "Laporan Penerimaan KSU";

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
		$data['dt_ksu'] = $this->db->query("SELECT DISTINCT(tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer) FROM tr_penerimaan_ksu_dealer INNER JOIN 
				tr_penerimaan_unit_dealer ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer WHERE 
			  tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' ORDER BY tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer DESC");						
		$this->template($data);	
	}
	public function view(){
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;
		$data['set']		= "detail";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_ksu'] = $this->db->query("SELECT DISTINCT(tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) AS ID FROM tr_penerimaan_ksu_dealer INNER JOIN tr_penerimaan_unit_dealer
					ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer 
					WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND  tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");						
		$data['id_penerimaan_unit_dealer'] = $this->input->get('id');
		$id_penerimaan_unit_dealer 					= $this->input->get('id');
    $data['sj'] 		= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'")->row();
		$this->template($data);										
	}	
}