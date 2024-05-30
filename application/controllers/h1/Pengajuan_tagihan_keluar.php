<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengajuan_tagihan_keluar extends CI_Controller {
    var $tables =   "tr_pengajuan_tagihan_keluar";	
		var $folder =   "h1";
		var $page		=		"pengajuan_tagihan_keluar";
    var $pk     =   "id_pengajuan_tagihan_keluar";
    var $title  =   "Pengajuan Tagihan Keluar";
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
		$data['dt_tagihan']	= $this->db->query("SELECT * FROM tr_pengajuan_tagihan_keluar LEFT JOIN ms_dealer On tr_pengajuan_tagihan_keluar.id_dealer = ms_dealer.id_dealer");
		$this->template($data);			
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
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;						
		$data['tagihan_ke'] 				= $this->input->post('tagihan_ke');		
		$data['tgl_pengajuan'] 			= $this->input->post('tgl_pengajuan');		
		$data['id_dealer'] 					= $this->input->post('id_dealer');		
		$data['id_finance_company'] = $this->input->post('id_finance_company');		
		$data['total_tagihan'] 			= $this->input->post('total_tagihan');		
		$data['keterangan'] 				= $this->input->post('keterangan');				
		$data['active'] 						= "1";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;
		$this->m_admin->insert($tabel,$data);		
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengajuan_tagihan_keluar/add'>";		
	}
	public function view()
	{				
		$id = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$data['dt_tagihan'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$this->template($data);			
	}	
}