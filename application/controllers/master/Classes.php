<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends CI_Controller {

    var $tables =   "ms_classes";	
		var $folder =   "master";
		var $page		=		"classes";
    var $pk     =   "id_classes";
    var $title  =   "Master Data Classes";
    

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
		$data['dt_classes'] = $this->m_admin->getAll($this->tables);
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
		$tabel										= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_classes'] 		= $this->input->post('id_classes');
			$data['classes'] 		= $this->input->post('classes');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;						
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/classes/add'>";
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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/classes'>";
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
		$data['dt_classes'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan","kelurahan","ASC");		
		$data['dt_area_penjualan'] = $this->db->query("SELECT * FROM ms_area_penjualan WHERE active = 1 ORDER BY area_penjualan ASC");		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');

		$tabel					= $this->tables;
		$pk 						= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_classes'] 		= $this->input->post('id_classes');								
			$data['classes'] 		= $this->input->post('classes');								
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/classes'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}