<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_claim extends CI_Controller {

    var $tables =   "ms_kategori_claim";	
		var $folder =   "master";
		var $page		=		"kategori_claim";
    var $pk     =   "kode_claim";
    var $title  =   "Master Kategori Claim C3";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_kelurahan');		
		//===== Load Library =====
		$this->load->library('upload');
		
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
		$data['dt_kategori_claim'] = $this->db->query("SELECT * FROm ms_kategori_claim
																ORDER BY nama_claim ASC");							
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
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id					= $this->session->userdata('id_user');		

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			
			$data['kode_claim']				= $this->input->post('kode_claim');		
			$data['nama_claim'] 			= $this->input->post('nama_claim');		
			$data['tipe_claim']				= $this->input->post('tipe_claim');				
			if($this->input->post('active') == '1'){
				$data['active']				= $this->input->post('active');		
			}else{
				$data['active'] 			= "";
			}
			$data['created_at']			= $waktu;
			$data['created_by']			= $login_id;				
			
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kategori_claim/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');		
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{				
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kategori_claim'>";
		}
	}
	
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_kategori_claim'] = $this->m_admin->kondisi($tabel,$d);		
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']			= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('kode_claim');
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$id					= $this->input->post("kode_claim");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['kode_claim']				= $this->input->post('kode_claim');		
			$data['nama_claim'] 			= $this->input->post('nama_claim');		
			$data['tipe_claim']				= $this->input->post('tipe_claim');											
			
			$data['updated_at']			= $waktu;
			$data['updated_by']			= $login_id;

			if($this->input->post('active') == '1'){
				$data['active']	= $this->input->post('active');		
			}else{
				$data['active'] 			= "";
			}

			
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kategori_claim'>";		
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}	
}