<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_permohonan_stnk extends CI_Controller {

  var $tables =   "tr_permohonan_stnk";	
	var $folder =   "dealer";
	var $page		=		"form_permohonan_stnk";
  var $pk     =   "no_permohonan";
  var $title  =   "Form Permohonan Faktur STNK Konsumen";

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
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE id_dealer = '$id_dealer'");			
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
		$kode = $this->m_admin->cari_id("tr_permohonan_stnk","no_permohonan");
		echo $kode;
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
			$id_dealer = $this->m_admin->cari_dealer();
			$data['no_permohonan'] 				= $this->input->post('no_permohonan');
			$data['tgl_permohonan'] 			= $this->input->post('tgl_permohonan');	
			$data['nama_konsumen_lama'] 	= $this->input->post('nama_konsumen_lama');	
			$data['nama_konsumen_baru'] 	= $this->input->post('nama_konsumen_baru');	
			$data['no_mesin'] 						= $this->input->post('no_mesin');	
			$data['no_stnk'] 							= $this->input->post('no_stnk');	
			$data['no_polisi'] 						= $this->input->post('no_polisi');				
			$data['id_dealer'] 						= $id_dealer;				
			$data['status_stnk']					= "open";		
			$data['created_at']						= $waktu;		
			$data['created_by']						= $login_id;	
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/form_permohonan_stnk/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function close()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;				
		$id 	= $this->input->get('id');		
		$data['status_stnk'] 				= "close";		
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;
		$this->m_admin->update($tabel,$data,$this->pk,$id);
		$_SESSION['pesan'] 		= "Data has been closed successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/form_permohonan_stnk'>";		
	}
}