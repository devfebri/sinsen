<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_level extends CI_Controller {

  var $tables =   "ms_user_level";	
	var $folder =   "master";
	var $page		=	"user_level";
  var $pk     =   "id_user_level";
  var $title  =   "Master Data User level";

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
		$data['set']		= "view";
		$data['dt_user_level'] = $this->db->query("SELECT ms_user_level.*,ms_user_group.code as group_code FROM ms_user_level INNER JOIN ms_user_group ON
																					ms_user_level.id_user_group=ms_user_group.id_user_group");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";
		$data['dt_user_group'] = $this->m_admin->getSort("ms_user_group","user_group","ASC");									
		$this->template($data);	
	}
	public function save()
	{		
		$tabel							= $this->tables;
		$data['code'] 			= $this->input->post('code');
		$data['user_level']	= $this->input->post('user_level');		
		$data['id_user_group']	= $this->input->post('id_user_group');		
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan']= "Data has been saved successfully";
		$_SESSION['tipe'] = "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user_level/add'>";
	}
	public function delete()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->get('id');
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user_level'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_user_level'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_user_group'] = $this->m_admin->getSort("ms_user_group","user_group","ASC");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');
		$data['code'] 			= $this->input->post('code');
		$data['user_level'] 	= $this->input->post('user_level');				
		$data['id_user_group']	= $this->input->post('id_user_group');		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user_level'>";
	}
}