<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah_penagihan_detail extends CI_Controller {

    var $tables =   "ms_wilayah_penagihan_detail";	
		var $folder =   "master";
		var $page		=		"wilayah_penagihan_detail";
    var $pk     =   "id_wilayah_penagihan_detail";
    var $title  =   "Master Data Wilayah Penagihan Detail";

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
		$data['dt_wilayah_penagihan_detail'] = $this->db->query("SELECT * FROM ms_wilayah_penagihan INNER JOIN ms_wilayah_penagihan_detail
																								ON ms_wilayah_penagihan.id_wilayah_penagihan=ms_wilayah_penagihan_detail.id_wilayah_penagihan INNER JOIN ms_dealer
																								ON ms_wilayah_penagihan_detail.id_dealer=ms_dealer.id_dealer");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['dt_wilayah_penagihan']	= $this->m_admin->getSort("ms_wilayah_penagihan","wilayah_penagihan","ASC");
		$data['dt_dealer']	= $this->m_admin->getSort("ms_dealer","nama_dealer","ASC");
		$data['set']	= "insert";									
		$this->template($data);	
	}
	public function save()
	{		
		$tabel			= $this->tables;		
		$data['id_wilayah_penagihan'] 	= $this->input->post('id_wilayah_penagihan');				
		$data['id_dealer'] 	= $this->input->post('id_dealer');				
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/wilayah_penagihan_detail/add'>";
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
			$this->m_admin->delete($tabel,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been deleted successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/wilayah_penagihan_detail'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_wilayah_penagihan_detail'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_wilayah_penagihan']	= $this->m_admin->getSort("ms_wilayah_penagihan","wilayah_penagihan","ASC");
		$data['dt_dealer']	= $this->m_admin->getSort("ms_dealer","nama_dealer","ASC");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->post('id');		
		$data['id_wilayah_penagihan'] 	= $this->input->post('id_wilayah_penagihan');				
		$data['id_dealer'] 	= $this->input->post('id_dealer');				
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/wilayah_penagihan_detail'>";
	}
}